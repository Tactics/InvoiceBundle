<?php

namespace Tactics\InvoiceBundle\Model;

interface CustomerInterface
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return Tactics\InvoiceBundle\Model\Address
     */
    public function getAddress();
    
    /**
     * @return string
     */
    public function getVATNumber();
}
