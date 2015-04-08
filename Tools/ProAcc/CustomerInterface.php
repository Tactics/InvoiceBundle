<?php

namespace Tactics\InvoiceBundle\Tools\ProAcc;

use Tactics\InvoiceBundle\Model\Invoice;

interface CustomerInterface 
{
    public function getExternalId();
    public function getNaam();
    public function getKlantcode();
    public function getOpzoeknaam();
    public function getFirmanaam();
    public function getStraatNummerBus();
    public function getPostcode();
    public function getGemeente();
    public function getLandcode();
    public function getLandnaam();
    public function getTelefoon();
    public function getFax();
    public function getEmail();
    public function getBtwNummer();
    public function getBankrekening();
    public function getCodeValuta();
    public function getCode1();
    public function getBtwStatus();
    public function getOndernemingsnummer();
}

