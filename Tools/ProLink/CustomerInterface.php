<?php

namespace Tactics\InvoiceBundle\Tools\ProLink;

use Tactics\InvoiceBundle\Tools\CustomerInterface as GenericCustomerInterface;

interface CustomerInterface extends GenericCustomerInterface
{
    public function getNaam();
    public function getKlantcode($schemeId);
    public function getFirmanaam();
    public function getStraatNummerBus();
    public function getPostcode();
    public function getGemeente();
    public function getLandcode();
    public function getLandnaam();
    public function getTelefoon();
    public function getFax();
    public function getEmail();
    public function getFactuurEmail();
    public function getBtwNummer();
    public function getBankrekening();
    public function getCodeValuta();
    public function getBtwStatus();
    public function getOndernemingsnummer();
}

