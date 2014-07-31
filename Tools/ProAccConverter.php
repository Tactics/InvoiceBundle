<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

class ProAccConverter
{
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
        $blancos = array_combine($rangeAtoAM, array_fill(0, count($rangeAtoAM), ''));
        
        $lines = array();
        foreach ($this->invoice->getItems() as $cnt => $item)
        {
            $lines[] = array_merge($blancos, array(
              'A' => $cnt === 0 ? '1' : '3',
              'B' => $this->getKlantcode(),
              'C' => $this->invoice->getJournalCode(),
              'D' => $this->invoice->getNumber(),
              'E' => $this->invoice->getDate('d/m/Y'),
              'F' => '1403', // 2014 kwartaal 3???
              'H' => $this->invoice->getDateDue('d/m/Y'),
              'I' => 'EUR',
              'L' => number_format($this->invoice->getTotal() + $this->invoice->getVat(), 2, ',', ''),
              'M' => number_format($this->invoice->getTotal(), 2, ',', ''),
              'N' => number_format($this->invoice->getVat(), 2, ',', ''),
              'AA' => $item->getGlAccount()->getCode(),
              'AB' => $item->getAnalytical1Account() ? $item->getAnalytical1Account()->getCode() : '',
              'AC' => number_format($item->getUnitPrice(), 2, ',', ''),
              'AD' => number_format($item->getUnitPrice(), 2, ',', ''),
              'AE' => number_format($item->getVat()->getPercentage(), 2, ',', ''),
              'AF' => $item->getQuantity(),
              'AG' => substr($item->getGlAccount()->getName(), 0, 25),
              'AI' => $item->getAnalytical2Account() ? $item->getAnalytical2Account()->getCode() : '',
              'AL' => $this->invoice->getDatePaid() ? '1' : '0'
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
    
    private function getKlantcode()
    {
        $prefix = get_class($this->invoice->getCustomer()) === 'Organisatie' ? 'O' : 'P';
        
        return sprintf("$prefix%06u", $this->invoice->getCustomer()->getId());
    }
}

