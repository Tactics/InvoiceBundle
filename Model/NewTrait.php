<?php

namespace Tactics\InvoiceBundle\Model;

trait NewTrait
{
    private $new = false;
    
    public function getNew()
    {
        return $this->new;
    }
    
    public function setNew($new)
    {
        $this->new = (boolean) $new;
    }
}