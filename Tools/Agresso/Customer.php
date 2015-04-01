<?php

namespace Tactics\InvoiceBundle\Tools\Agresso;

use Tactics\InvoiceBundle\Tools\Agresso\CustomerInterface;

class Customer implements CustomerInterface
{
    private $customer;      // The customer object
    private $customerClass; // Persoon of Organisatie
    
    private static $apArGroupMap = array(
        'Organisatie' => array(
            'BE' => '01',   // belgische ondernemingen
            'other' => '02' // buitenlandse ondernemingen
        ),
        'Persoon' => array(
            'BE' => '03',   // belgische natuurlijke personen
            'other' => '04' // buitenlandse natuurlijke personen
        )
    );
    
    /**
     * Constructor
     * 
     * @param mixed $customer een Persoon of een Organisatie
     * @throws \Exception Indien invalid Customer
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
        $this->customerClass = get_class($customer);
        if (!in_array($this->customerClass, array('Persoon', 'Organisatie')))
        {
            throw new \Exception('Customer needs to be of class \'Persoon\' or \'Organisatie\'');
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getApArNo()
    {
        $apArNoGetter = sprintf('get%sApArNo', $this->customerClass);
                
        return $this->$apArNoGetter();
    }
    
    /**
     * 
     * @return string
     */
    public function getName()
    {
        return $this->customer->getNaam(true);
    }
    
    /**
     * 
     * @return string 01, 02, 03 or 04
     */
    public function getApArGroup()
    {
        return isset(self::$apArGroupMap[$this->customerClass][$this->getCountryCode()])
            ? self::$apArGroupMap[$this->customerClass][$this->getCountryCode()]
            : self::$apArGroupMap[$this->customerClass]['other']
        ; 
    }
    
    /**
     * 
     * @return string C (company) or P (private)
     */
    public function getControl()
    {
        return $this->isOrganisatie() ? 'C' : 'P';
    }
    
    /**
     * 
     * @return string
     */
    public function getAddress()
    {
        return $this->customer->getStraatNummerBus();
    }
    
    /**
     * 
     * @return string
     */
    public function getPlace()
    {
        return $this->customer->getGemeente();
    }
    
    /**
     * 
     * @return string
     */
    public function getZipCode()
    {
        return $this->customer->getPostcode();
    }
    
    /**
     * 
     * @return string
     */
    public function getCountryCode()
    {
        return $this->customer->getLandId();
    }
    
    /**
     * 
     * @return string het ondernemingsnummer
     */
    private function getOrganisatieApArNo()
    {
        return str_replace('.', '', trim($this->customer->getOndernemingsnummer()));
    }
    
    /**
     * 
     * @return string het rijksregisternummer
     */
    private function getPersoonApArNo()
    {
        return str_replace(' ', '', trim($this->customer->getRijksregisternummer()));
    }
    
    /**
     * 
     * @return bool
     */
    private function isOrganisatie()
    {
        return $this->customerClass === 'Organisatie';
    }
}