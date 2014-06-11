<?php

namespace Tactics\InvoiceBundle\Model;

class Address
{
    private $street;

    private $number;
    
    private $bus;

    private $postal_code;

    private $city;

    private $country = 'BE';

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }
    
    public function getBus()
    {
        return $this->bus;
    }

    public function setBus($bus)
    {
        $this->bus = $bus;
    }

    public function getPostalCode()
    {
        return $this->postal_code;
    }

    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }
}

