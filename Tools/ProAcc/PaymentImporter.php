<?php
namespace Tactics\InvoiceBundle\Tools\ProAcc;

use Tactics\InvoiceBundle\Propel\InvoiceManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Events;

final class PaymentImporter
{
    private $invoiceMgr;
    private $eventDispatcher;

    private $logs = array();

    /**
     * constructor
     *
     * @param InvoiceManager $invoiceMgr
     */
    public function __construct(InvoiceManager $invoiceMgr, EventDispatcherInterface $eventDispatcher)
    {
        $this->invoiceMgr = $invoiceMgr;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Import proacc payments
     *
     * @param $file
     * @return array
     */
    public function import($file)
    {
        while (!feof($file)) {
            $line = explode("\t", trim(fgets($file)));
            if (!isset($line[0]) || !$line[0]) // skip emtpy lines
            {
              continue;
            }
            $invoice = $this->invoiceMgr->searchOne(array('number' => $line[0]));

            if (!$invoice) {
                $this->logs[] = $line[0] . ': <span style="color:red">Factuur niet gevonden</span>';
                continue;
            }

            $this->editInvoice($invoice, $line);
        }

        return $this->logs;
    }

    /**
     * edit invoice with new payments
     *
     * @param Invoice $invoice
     * @param array $line
     */
    private function editInvoice($invoice, $line)
    {
        $factuurNr = $line[0];
        $amountPaid = str_replace(',', '.', $line[1]);
        $cultureDate = $line[3];
      
        if ($invoice->isPaid())
        {
            $this->logs[] = $factuurNr.': Factuur is reeds betaald';
            return;
        }
        if (bccomp($invoice->getOutstandingAmount(), $amountPaid, 2) === -1)
        {
            $this->logs[] = $factuurNr.': Er is <b>te veel</b> betaald voor deze factuur. <span style="color:red">Factuur niet opgeslagen.</span>';
            return;
        }
        
        $invoice->addPayment($amountPaid, \myDateTools::cultureDateToPropelDate($cultureDate));
        $this->logs[] = $invoice->isPaid()
          ? $factuurNr.': Factuur volledig betaald'
          : $factuurNr.': Factuur deels betaald'
        ;        

        $this->invoiceMgr->save($invoice);
        
        $event = new Events\InvoicePaymentEvent($invoice, $amountPaid);
        $this->eventDispatcher->dispatch($event, Events\InvoiceEvents::PAYMENT);

    }
}