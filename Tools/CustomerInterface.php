<?php

namespace Tactics\InvoiceBundle\Tools;

interface CustomerInterface
{
    /**
     * return the application customer object
     */
    public function getCustomer();
    
    /**
     * 
     * @param int $schemeId Id van boekhoudschema
     */
    public function getCustomerInfo($schemeId);
    
    /**
     * @return array geeft de te configureren velden terug
     */
    public function getFields();
    
    /**
     * @return int de id van de customer in het externa boekhoudprogramma
     */
    public function getExternalId($schemeId);
    
    /**
     * @return string de naam van de klant
     */
    public function getName();
    
}

