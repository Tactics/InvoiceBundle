<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Model\InvoiceItem;
use Tactics\InvoiceBundle\Propel\ObjectManager;

class ProAccConverter
{
    private $customerSchemeMgr;

    /**
     * constructor
     *
     * @param ObjectManager $customerSchemeMgr
     */
    public function __construct(ObjectManager $customerSchemeMgr)
    {
        $this->customerSchemeMgr = $customerSchemeMgr;
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
        
        $lines = array();
        foreach ($this->invoice->getItems() as $cnt => $item)
        {
            $lines[] = array_merge($blancos, array(
              'A' => $cnt === 0 ? '1' : '3',
              'B' => $this->getKlantcode(),
              'C' => $this->getJournalNumber($item),
              'D' => $this->invoice->getNumber(),
              'E' => $this->invoice->getDate('d/m/Y'),
              'F' => $this->invoice->getDate('ym'),
              'G' => '',
              'H' => $this->invoice->getDateDue('d/m/Y'),
              'I' => 'EUR',
              'J' => 1,
              'K' => number_format($this->invoice->getTotal() + $this->invoice->getVat(), 2, ',', ''),
              'L' => number_format($this->invoice->getTotal() + $this->invoice->getVat(), 2, ',', ''),
              'M' => number_format($this->invoice->getTotal(), 2, ',', ''),
              'N' => number_format($this->invoice->getVat(), 2, ',', ''),
              'X' => $this->invoice->getVat() ? number_format($this->invoice->getTotal(), 2, ',', '') : 0,00,
              'Z' => $item->getAnalytical1Account() ? $item->getAnalytical1Account()->getName() : '',
              'AA' => $item->getGlAccount()->getCode(),
              'AB' => $item->getAnalytical1Account() ? $item->getAnalytical1Account()->getCode() : '',
              'AC' => number_format($item->getPriceExVat(), 2, ',', ''),
              'AD' => number_format($item->getPriceExVat(), 2, ',', ''), // idem als AC - fin.korting, maar fin.korting wordt niet gebruikt
              'AE' => number_format($item->getVat()->getPercentage(), 2, ',', ''),
              'AG' => substr($item->getDescription(), 0, 25), // omschrijving, voor inovant moet hier de opleidingscode inkomen
              'AI' => $item->getAnalytical2Account() ? $item->getAnalytical2Account()->getCode() : '',
              'AK' => '',
              'AL' => $this->invoice->getDatePaid() ? '1' : '0',
              'AM' => ''
            ));
        }
        
        $lines[] = $this->getLastLine($blancos);
        
        return $lines;
    }
    
    private function getLastLine($blancos)
    {
        return array_merge($blancos, array(
          'A' => '99'
        ));
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
        
        return $scheme->getValue();
    }
    
    /**
     * Geeft het dagboeknummer terug
     * 1. facturen verkoopdagboek
     * 2. creditnota's verkoopdagboek
     * 3. Facturen vrij van BTW
     * 4. CN vrij van BTW
     * 
     * @return int
     */
    private function getJournalNumber(InvoiceItem $item)
    {
      $isCN = bccomp($item->getPriceExVat(), 0, 2) === -1;
      $vrijVanBtw = $item->getPriceExVat() && !$item->getVat();
      
      if ($isCN && $vrijVanBtw)
      {
        return 4;
      }
      
      if ($isCN && !$vrijVanBtw)
      {
        return 2;
      }
      
      if (!$isCN && $vrijVanBtw)
      {
        return 3;
      }
      
      if (!$isCN && !$vrijVanBtw)
      {
        return 1;
      }
    }
}

