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

    public function __construct(InvoiceManager $invoiceMgr)
    {
        $this->invoiceMgr = $invoiceMgr;
    }

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

    private function editInvoice($invoice, $line)
    {
        $invoice->setAmountPaid($invoice->getAmountPaid() + $line[1]);
        if ($invoice->getTotal() == $invoice->getAmountPaid())
        {
            $invoice->setDatePaid(myDateTools::cultureDateToPropelDate($line[3]));
            $invoice->setStatus(Invoice::STATUS_BETAALD);
            $this->logs[] = $line[0].': Factuur volledig betaald';

            $this->invoiceMgr->save($invoice);
        }
        elseif ($invoice->getTotal() > $invoice->getAmountPaid())
        {
            $invoice->setStatus(Invoice::STATUS_DEELS_BETAALD);
            $this->logs[$line[0]] = $line[0].': Factuur deels betaald';

            $this->invoiceMgr->save($invoice);
        }
        elseif ($invoice->getTotal() < $invoice->getAmountPaid())
        {
            $this->logs[$line[0]] = $line[0].': Er is <b>te veel</b> betaald voor deze factuur. <span style="color:red">Factuur niet opgeslagen.</span>';
        }
    }
}