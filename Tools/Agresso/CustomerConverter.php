<?php

namespace Tactics\InvoiceBundle\Tools\Agresso;

use Tactics\InvoiceBundle\Tools\CustomerFactoryInterface;
use Tactics\InvoiceBundle\Tools\Agresso\CustomerInterface as AgressoCustomerInterface;
use Tactics\InvoiceBundle\Tools\ConverterResult;

class CustomerConverter
{
    private $customerFactory;
    private $customer;
    private $invoice;
    
    /**
     * Constructor
     * @param CustomerFactoryInterface $this->customerFactory
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
        $now = new \myDate(time());
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= sprintf('<!--XML generated by Tactics on %s-->', $now->format('f'));
        $xml .= '<!--Link Agresso TAC_BRUGGE-->';
        $xml .= sprintf('<Customer xmlns="Tactics/Klanten/%u/%02u">', $now->format('Y'), $now->format('MM'));
        foreach ($invoices as $invoice)
        {
            $this->invoice = $invoice;
            $this->customer = $this->customerFactory->getCustomer($invoice);
            $xml .= $this->getCustomerXml();
        }
        $xml .= '</Customer>';
        
        return new ConverterResult('klanten_'.$now->format('yyyyMMddHHmmss').'.xml', 'text/xml', $xml);
    }
    
    /**
     * generates the Agresso XML for a customer
     * 
     * @return string An Agresso customer XML
     */
    private function getCustomerXml()
    {
        $schemeId = $this->invoice->getSchemeId();
        
        $xml = '<MasterFile>';        
        $xml .= sprintf('<ApArNo>%s</ApArNo>', $this->customer->getApArNo($schemeId));
        $xml .= $this->getSupplierCustomer($this->customer);        
        $xml .= $this->getAddressInfo($this->customer);
        $xml .= '</MasterFile>';
        
        return $xml;
    }
    
    private function getSupplierCustomer()
    {
        $schemeId = $this->invoice->getSchemeId();
        
        $xml = '<SupplierCustomer>';
        $xml .= sprintf('<Name>%s</Name>', substr($this->customer->getName(), 0, 255));
        $xml .= sprintf('<ApArGroup>%02u</ApArGroup>', $this->customer->getApArGroup());
        $xml .= sprintf('<ShortName>%s</ShortName>', $this->customer->getApArNo($schemeId));
        $xml .= sprintf('<CountryCode>%s</CountryCode>', $this->customer->getCountryCode());
        $xml .= $this->getInvoiceInfo($this->customer);        
        $xml .= '</SupplierCustomer>';
        
        return $xml;
    }
    
    private function getInvoiceInfo()
    {
        $schemeId = $this->invoice->getSchemeId();
        
        $xml = '<InvoiceInfo>';
        $xml .= sprintf('<HeadOffice>%s</HeadOffice>', $this->customer->getApArNo($schemeId));
        $xml .= sprintf('<Control>%s</Control>', $this->customer->getControl());
        $xml .= '</InvoiceInfo>';
        
        return $xml;
    }
    
    private function getAddressInfo()
    {
        $xml = '<AddressInfo>';
        $xml .= sprintf('<Address>%s</Address>', $this->customer->getAddress());
        $xml .= sprintf('<Place>%s</Place>', $this->customer->getPlace());
        $xml .= sprintf('<ZipCode>%s</ZipCode>', $this->customer->getZipCode());
        $xml .= sprintf('<CountryCode>%s</CountryCode>', $this->customer->getCountryCode());
        $xml .= '</AddressInfo>';
        
        return $xml;
    }
}