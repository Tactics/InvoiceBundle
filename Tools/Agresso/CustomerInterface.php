<?php

namespace Tactics\InvoiceBundle\Tools\Agresso;

use Tactics\InvoiceBundle\Tools\CustomerInterface as GenericCustomerInterface;

interface CustomerInterface extends GenericCustomerInterface
{
    public function getApArNo($schemeId);    
    public function getApArGroup();
    public function getControl();
    public function getAddress();
    public function getPlace();
    public function getZipCode();
    public function getCountryCode();
}

