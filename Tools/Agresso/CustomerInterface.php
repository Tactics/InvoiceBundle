<?php

namespace Tactics\InvoiceBundle\Tools\Agresso;

interface CustomerInterface 
{
    public function getApArNo();    
    public function getName();
    public function getApArGroup();
    public function getControl();
    public function getAddress();
    public function getPlace();
    public function getZipCode();
    public function getCountryCode();
}

