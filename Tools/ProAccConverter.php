<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Propel\ObjectManager;

class ProAccConverter
{
    private $customerSchemeMgr;
    private $journalMgr;

    /**
     * constructor
     *
     * @param ObjectManager $customerSchemeMgr
     */
    public function __construct(ObjectManager $customerSchemeMgr, ObjectManager $journalMgr)
    {
        $this->customerSchemeMgr = $customerSchemeMgr;
        $this->journalMgr = $journalMgr;
    }

    private $invoice;
    
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\Invoice $invoice
     */
    public function setInvoice(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
    
    public function convert()
    {
        $rangeAAToAM = array_map(create_function('$object', 'return "A{$object}";'), range('A', 'M'));
        $rangeAtoAM = array_merge(range('A', 'Z'), $rangeAAToAM);
        $blancos = array_combine($rangeAtoAM, array_fill(0, count($rangeAtoAM), '0'));
        $omschrijving = $this->getOmschrijving();
        $boekingsPeriode = $this->getBoekingsperiode($this->invoice);
        $withVat = $this->invoice->withVat();
        $isCreditNote = $this->invoice->isCreditNote();
        
        $lines = array();
        foreach ($this->invoice->getItems() as $cnt => $item)
        {
          if ($item->getType() == 'text') continue;
          
            $lines[] = array_merge($blancos, array(
              'A' => $cnt === 0 ? ($isCreditNote ? '2' : '1') : '3',
              'B' => $this->getKlantcode(),
              'C' => $this->invoice->getJournalCode(),
              'D' => $this->invoice->getNumber(),
              'E' => $this->invoice->getDate('d/m/Y'),
              'F' => $boekingsPeriode,
              'G' => '',
              'H' => $this->invoice->getDateDue('d/m/Y'),
              'I' => 'EUR',
              'J' => 1,
              'K' => number_format($this->invoice->getTotal() + $this->invoice->getVat(), 2, ',', ''),
              'L' => number_format($this->invoice->getTotal() + $this->invoice->getVat(), 2, ',', ''),
              'M' => number_format($this->invoice->getTotal(), 2, ',', ''),
              'N' => $withVat ? number_format($this->invoice->getVat(), 2, ',', '') : 0,
              'O' => !$withVat ? number_format($this->invoice->getTotal(), 2, ',', '') : 0,
              'X' => $withVat ? number_format($this->invoice->getTotal(), 2, ',', '') : 0, // maatstaf heffing 21% BTW hele dossier
              'Z' => $omschrijving,
              'AA' => $item->getGlAccountCode(),
              'AB' => $item->getAnalytical1AccountCode() ?: '',
              'AC' => number_format($item->getPriceExVat(), 2, ',', ''),
              'AD' => number_format($item->getPriceExVat(), 2, ',', ''), // idem als AC - fin.korting, maar fin.korting wordt niet gebruikt              
              'AE' => $withVat ? number_format($item->getVatPercentage(), 2, ',', '') : 0,
              'AG' => substr($item->getDescription(), 0, 25), // omschrijving, voor inovant moet hier de opleidingscode inkomen
              'AI' => $item->getAnalytical2AccountCode() ?: '',
              'AK' => '',
              'AL' => $this->invoice->getDatePaid() ? '1' : '0',
              'AM' => ''
            ));
        }
        
        return $lines;
    }

    private function getOmschrijving()
    {
        $omschrijving =  $this->invoice->getItems() ? $this->invoice->getItems()[0]->getGroupDescription() : '';

        return substr($omschrijving, 0, 20);
    }
    
    /**
     * Geeft de klantcode terug
     * 
     * @return string
     * @todo: proacc_number ophalen/genereren van nieuwe
     */
    private function getKlantcode()
    {
        $customer = $this->invoice->getCustomer();
        $scheme = $this->customerSchemeMgr->searchOne(array('name' => 'proacc_nummer', 'customer_id' => $customer->getId(), 'customer_class' => get_class($customer)));
        
        return $scheme ? $scheme->getValue() : '';
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

