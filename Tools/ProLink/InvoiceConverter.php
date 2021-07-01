<?php

namespace Tactics\InvoiceBundle\Tools\ProLink;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Model\InvoiceItem;
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
     * @param array $options
     * @return ConverterResult
     */
    public function convert($invoices, $options = array())
    {
        $data = array();
        foreach ($invoices as $invoice)
        {
            foreach ($this->getProLinkVerkoopLijnen($invoice, $options) as $verkoopLijn)
            {
               $data[] = implode("\t", $verkoopLijn);
            }
        }
        
        $data[] = "99"; // add last line
        
        return new ConverterResult('verkopen.txt', 'text/csv', implode("\r\n", $data));
    }
    
    /**
     * 
     * @param Invoice $invoice
     * @param array $options
     * @return array
     */
    private function getProLinkVerkoopLijnen(Invoice $invoice, $options)
    {
        $blancos = $this->getBlancos($options);
        $isCreditNote = $invoice->isCreditNote();

        $lines = array();
        $first = true;
        /**
         * @var  $cnt
         * @var InvoiceItem $item
         */
      foreach ($invoice->getItems() as $cnt => $item)
        {
            if ($item->getType() == 'text') continue;

            $line = array_merge($blancos, array(
              'A' => $first ? ($isCreditNote ? '2' : '1') : '3',
              'B' => 'F',
              'C' => 'VF',
              'D' => $invoice->getNumber(),
              'E' => $this->getKlantcode($invoice),
              'F' => $invoice->getDate('d/m/Y'),
              'G' => substr($item->getDescription(), 0, 50),
              'I' => 'EUR',
              'J' => 1,
              'K' => 3,
              'M' => 15,
              'R' => substr($item->getGroupDescription(), 0, strpos($item->getGroupDescription(), '-')),
              'S' => substr($item->getGroupDescription(), strpos($item->getGroupDescription(), '-')+2, 50),
              'T' => $item->getQuantity(),
              'U' => number_format($item->getUnitPrice(), 2),
              'V' => 0,
              'X' => $item->getGlAccountCode(),
              'Y' => $item->getAnalytical2AccountCode(),
              'AD' => 0,
              'AM' => 1,
              'AN' => 0,
            ));
            
            $lines[] = $line;
            
            $first = false;
        }

        return $lines;
    }
    
    /**
     * 
     * @param array $options
     * @return type
     */
    private function getBlancos($options)
    {
        $rangeAA = array_map(create_function('$object', 'return "A{$object}";'), range('A', 'O'));
        $range = array_merge(range('A', 'Z'), $rangeAA);
        return array_combine($range, array_fill(0, count($range), ''));
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
     * @todo: ap_number ophalen
     */
    private function getKlantcode(Invoice $invoice)
    {
        $customer = $this->customerFactory->getCustomer($invoice);
        
        return $customer->getExternalId($invoice->getSchemeId());
    }
}

