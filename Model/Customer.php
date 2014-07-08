<?php

namespace Tactics\InvoiceBundle\Model;

class Customer
{
    private $name;
    
    private $vat_number;
    
    private $address;
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setVatNumber($vat_number)
    {
        $this->vat_number = $vat_number;
    }
    
    public function getVatNumber()
    {
        return $this->vat_number;
    }
    
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }
    
    public function getAddress()
    {
        return $this->address;
    }
}
