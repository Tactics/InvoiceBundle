<?php

namespace Tactics\InvoiceBundle\Tools\ProAcc;

use Tactics\InvoiceBundle\Tools\CustomerFactoryInterface;
use Tactics\InvoiceBundle\Tools\ProAcc\CustomerInterface as ProAccCustomerInterface;
use Tactics\InvoiceBundle\Tools\ConverterResult;

class CustomerConverter
{
    private $customerFactory;
    
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
            $txtLines[] = $this->createCsvLine($this->customerFactory->getCustomer($invoice));
        }
        
        // add last line
        $txtLines[] = '99';
        
        return new ConverterResult('klanten.txt', 'text/plain', implode("\r\n", $txtLines));
    }
    
    /**
     * Geeft ProAcc lijn terug voor klantenexport
     * 
     * @param ProAccCustomer $customer
     * @return string
     */
    private function createCsvLine(ProAccCustomerInterface $customer)
    {
        $line = array_merge($this->createBlancos(), array(
            'A' => 1,
            'B' => $customer->getKlantcode(),
            'C' => $customer->getOpzoeknaam(),
            'E' => $customer->getFirmanaam(),      
            'H' => $customer->getStraatNummerBus(),
            'I' => $customer->getPostcode(),
            'J' => $customer->getGemeente(),
            'K' => $customer->getLandcode(),
            'L' => $customer->getLandnaam(),
            'M' => $customer->getTelefoon(),  
            'N' => $customer->getFax(),
            'O' => $customer->getEmail(),
            'P' => $customer->getBtwNummer(),
            'S' => $customer->getBankrekening(),
            'T' => $customer->getCodeValuta(),
            'V' => $customer->getCode1(),
            'AG' => $customer->getBtwStatus(),
            'AW' => $customer->getOndernemingsnummer()
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