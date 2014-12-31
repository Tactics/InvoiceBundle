<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 31/12/2014
 * Time: 10:59
 */

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Propel\InvoiceManager;

class ProAccImporter
{

    private $invoiceMgr;

    private $logs = array();

    /**
     * constructor
     *
     * @param InvoiceManager $invoiceMgr
     */
    public function __construct(InvoiceManager $invoiceMgr)
    {
        $this->invoiceMgr = $invoiceMgr;
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
     * @param $invoice
     * @param $line
     */
    private function editInvoice($invoice, $line)
    {
        $amountPaid = $line[1];
        if ($invoice->getStatus() == Invoice::STATUS_BETAALD)
        {
            $this->logs[] = $line[0].': Factuur is alreeds betaald';
            return;
        }


        if (bccomp($invoice->getOutstandingAmount(), $amountPaid, 2) === -1)
        {
            $this->logs[] = $line[0].': Er is <b>te veel</b> betaald voor deze factuur. <span style="color:red">Factuur niet opgeslagen.</span>';
            return;
        }
        if (bccomp($invoice->getOutstandingAmount(), $amountPaid, 2) === 0)
        {
            $invoice->setAmountPaid(bcadd($invoice->getAmountPaid(), $amountPaid, 2));
            $invoice->setDatePaid(\myDateTools::cultureDateToPropelDate($line[3]));
            $invoice->setStatus(Invoice::STATUS_BETAALD);
            $this->logs[] = $line[0].': Factuur volledig betaald';
        }

        if (bccomp($invoice->getOutstandingAmount(), $amountPaid, 2) === 1)
        {
            $invoice->setAmountPaid(bcadd($invoice->getAmountPaid(), $amountPaid, 2));
            $invoice->setStatus(Invoice::STATUS_DEELS_BETAALD);
            $this->logs[] = $line[0].': Factuur deels betaald';
        }

         $this->invoiceMgr->save($invoice);

    }
}