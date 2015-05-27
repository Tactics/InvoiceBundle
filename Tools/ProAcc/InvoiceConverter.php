<?php

namespace Tactics\InvoiceBundle\Tools\ProAcc;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Tools\CustomerFactoryInterface;
use Tactics\InvoiceBundle\Propel\ObjectManager;
use Tactics\InvoiceBundle\Tools\ConverterResult;

class InvoiceConverter
{
    private $customerFactory;
    private $journalMgr;

    /**
     * constructor
     *
     * @param ObjectManager $customerInfoMgr
     */
    public function __construct(CustomerFactoryInterface $customerFactory, ObjectManager $accountMgr, ObjectManager $vatMgr, ObjectManager $journalMgr)
    {
        $this->customerFactory = $customerFactory;
        $this->journalMgr = $journalMgr;
    }
    
    /**
     * 
     * @param array[Invoice] $invoices
     * @return ConverterResult
     */
    public function convert($invoices)
    {
        $data = array();
        foreach ($invoices as $invoice)
        {
            foreach ($this->getProAccVerkoopLijnen($invoice) as $verkoopLijn)
            {
               $data[] = implode("\t", $verkoopLijn);
            }
        }
        
        $data[] = "99"; // add last line
        
        return new ConverterResult('verkopen.txt', 'text/csv', implode("\r\n", $data));
    }
    
    private function getProAccVerkoopLijnen(Invoice $invoice)
    {
        $blancos = $this->getBlancos();
        $omschrijving = $this->getOmschrijving($invoice);
        $boekingsPeriode = $this->getBoekingsperiode($invoice);
        $withVat = $invoice->withVat();
        $isCreditNote = $invoice->isCreditNote();
        $vat = abs($invoice->getVat());
        $total = abs($invoice->getTotal());

        $lines = array();
        $first = true;
        foreach ($invoice->getItems() as $cnt => $item)
        {
            if ($item->getType() == 'text') continue;

            $lines[] = array_merge($blancos, array(
              'A' => $first ? ($isCreditNote ? '2' : '1') : '3',
              'B' => $this->getKlantcode($invoice),
              'C' => $invoice->getJournalCode(),
              'D' => $invoice->getNumber(),
              'E' => $invoice->getDate('d/m/Y'),
              'F' => $boekingsPeriode,
              'G' => '',
              'H' => $invoice->getDateDue('d/m/Y'),
              'I' => 'EUR',
              'J' => 1,
              'K' => number_format($total + $vat, 2, ',', ''),
              'L' => number_format($total + $vat, 2, ',', ''),
              'M' => number_format($total, 2, ',', ''),
              'N' => $withVat ? number_format($vat, 2, ',', '') : 0,
              'O' => !$withVat ? number_format($total, 2, ',', '') : 0,
              'X' => $withVat ? number_format($total, 2, ',', '') : 0, // maatstaf heffing 21% BTW hele dossier
              'Z' => $omschrijving,
              'AA' => $item->getGlAccountCode(),
              'AB' => $item->getAnalytical1AccountCode() ?: '',
              'AC' => number_format(abs($item->getPriceExVat()), 2, ',', ''),
              'AD' => number_format(abs($item->getPriceExVat()), 2, ',', ''), // idem als AC - fin.korting, maar fin.korting wordt niet gebruikt              
              'AE' => $withVat ? number_format($item->getVatPercentage(), 2, ',', '') : 0,
              'AG' => substr($item->getDescription(), 0, 50), // omschrijving, voor inovant moet hier de opleidingscode inkomen
              'AI' => $item->getAnalytical2AccountCode() ?: '',
              'AK' => '',
              'AL' => $invoice->getDatePaid() ? '1' : '0',
              'AM' => ''
            ));
            
            $first = false;
        }

        return $lines;
    }
    
    private function getBlancos()
    {
        $rangeAAToAM = array_map(create_function('$object', 'return "A{$object}";'), range('A', 'M'));
        $rangeAtoAM = array_merge(range('A', 'Z'), $rangeAAToAM);
        return array_combine($rangeAtoAM, array_fill(0, count($rangeAtoAM), '0'));
    }

    private function getOmschrijving(Invoice $invoice)
    {
        $omschrijving = $invoice->getRef() ?: ($invoice->getItems() ? $invoice->getItems()[0]->getGroupDescription() : '');
        return substr($omschrijving, 0, 20);
    }
    
    /**
     * Geeft de klantcode terug
     * 
     * @return string
     * @todo: proacc_number ophalen/genereren van nieuwe
     */
    private function getKlantcode(Invoice $invoice)
    {
        $customer = $this->customerFactory->getCustomer($invoice);
        
        return $customer->getExternalId($invoice->getSchemeId());
    }
    
    /**
     * 
     * @param Invoice $invoice
     * @return string
     * 
     * @todo: fix dependency on \Config::BOEKINGSPERIODE
     */
    private function getBoekingsperiode($invoice)
    {
        $journal = $this->journalMgr->find(array(
            $invoice->getJournalCode(),
            $invoice->getSchemeId()
        ));
        
        // facturen en creditnota's met BTW afh van config val
        if ($journal->getWithVat())
        {
            $ns = \sfContext::getInstance()->getUser()->getBedrijf()->getVarNaam();
            return \ConfigPeer::get(\Config::BOEKINGSPERIODE, '', $ns);
        }
        
        return $invoice->getDate('ym');
    }
}

