<?php

namespace Tactics\InvoiceBundle\Tools\ProAcc;

use Tactics\InvoiceBundle\Tools\CustomerFactoryInterface;
use Tactics\InvoiceBundle\Tools\ProAcc\CustomerInterface as ProAccCustomerInterface;
use Tactics\InvoiceBundle\Tools\ConverterResult;

class CustomerConverter
{
    private $customerFactory;
    private $customer;
    private $invoice;
    
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
            $this->invoice = $invoice;
            $this->customer = $this->customerFactory->getCustomer($invoice);
            $txtLines[] = $this->createCsvLine();
        }
        
        // add last line
        $txtLines[] = '99';
        
        return (new ConverterResult())->add('klanten.txt', 'text/plain', implode("\r\n", $txtLines));
    }
    
    /**
     * Geeft ProAcc lijn terug voor klantenexport
     * 
     * @return string
     */
    private function createCsvLine()
    {
        $schemeId = $this->invoice->getSchemeId();
        $line = array_merge($this->createBlancos(), array(
            'A' => 1,
            'B' => $this->customer->getKlantcode($schemeId),
            'C' => $this->customer->getOpzoeknaam($schemeId),
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
            'V' => $this->customer->getCode1($schemeId),
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
        $prefixWith = function ($prefix) {
            return function (string $letter) use ($prefix) {
                return $prefix . $letter;
            };
        };
        $rangeAAtoAZ = array_map($prefixWith("A"), range('A', 'Z'));
        $rangeBAtoBC = array_map($prefixWith("B"), range('A', 'C'));
        $rangeAtoBC = array_merge(range('A', 'Z'), $rangeAAtoAZ, $rangeBAtoBC);

        return array_combine($rangeAtoBC, array_fill(0, count($rangeAtoBC), ''));
    }
}
