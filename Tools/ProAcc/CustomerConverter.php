<?php

namespace Tactics\InvoiceBundle\Tools\ProAcc;

use Tactics\InvoiceBundle\Tools\CustomerFactoryInterface;
use Tactics\InvoiceBundle\Tools\ProAcc\CustomerInterface as ProAccCustomerInterface;
use Tactics\InvoiceBundle\Tools\ConverterResult;

class CustomerConverter
{
    private $customerFactory;
    private $customer;
    
    /**
     * Constructor
     * @param CustomerFactoryInterface $customerFactory
     */
    public function __construct(CustomerFactoryInterface $customerFactory)
    {
        $this->customerFactory = $customerFactory;
    }
    
    /**
     * Converts the given invoices to an xml with the customers
     * 
     * @param type $invoices
     * @return string The XML for Agresso
     */
    public function convert($invoices)
    {
        $txtLines = array();
        
        foreach ($invoices as $invoice)
        {
            $this->customer = $this->customerFactory->getCustomer($invoice);
            $txtLines[] = $this->createCsvLine();
        }
        
        // add last line
        $txtLines[] = '99';
        
        return new ConverterResult('klanten.txt', 'text/plain', implode("\r\n", $txtLines));
    }
    
    /**
     * Geeft ProAcc lijn terug voor klantenexport
     * 
     * @return string
     */
    private function createCsvLine()
    {
        $line = array_merge($this->createBlancos(), array(
            'A' => 1,
            'B' => $this->customer->getKlantcode(),
            'C' => $this->customer->getOpzoeknaam(),
            'E' => $this->customer->getFirmanaam(),      
            'H' => $this->customer->getStraatNummerBus(),
            'I' => $this->customer->getPostcode(),
            'J' => $this->customer->getGemeente(),
            'K' => $this->customer->getLandcode(),
            'L' => $this->customer->getLandnaam(),
            'M' => $this->customer->getTelefoon(),  
            'N' => $this->customer->getFax(),
            'O' => $this->customer->getEmail(),
            'P' => $this->customer->getBtwNummer(),
            'S' => $this->customer->getBankrekening(),
            'T' => $this->customer->getCodeValuta(),
            'V' => $this->customer->getCode1(),
            'AG' => $this->customer->getBtwStatus(),
            'AW' => $this->customer->getOndernemingsnummer()
        ));
        
        return implode("\t", $line);
    }
    
    /**
     * Geeft lege indexed array terug
     * indexes gaan in excel stijl van A tem BC
     * 
     * @return array
     */
    private function createBlancos()
    {
        $rangeAAToAZ = array_map(create_function('$object', 'return "A{$object}";'), range('A', 'Z'));
        $rangeBAtoBC = array_map(create_function('$object', 'return "B{$object}";'), range('A', 'C'));
        $rangeAtoBC = array_merge(range('A', 'Z'), $rangeAAToAZ, $rangeBAtoBC);
        return array_combine($rangeAtoBC, array_fill(0, count($rangeAtoBC), ''));
    }
}