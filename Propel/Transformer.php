<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Model\TransformableInterface;

class Transformer extends Model\Transformer
{
    /**
     * Geeft een orm object terug op basis van een domain object
     * 
     * @param \Tactics\InvoiceBundle\Model\TransformableInterface $domain_object
     * @return mixed orm_object
     */
    public function toOrm(TransformableInterface $domain_object)
    {
        $propelClassName = Helper::getPropelClassName($this->class);        
        $propelObject = new $propelClassName();
        $propelObject->fromArray($domain_object->toArray());

        return $propelObject;
    }

    /**
     * Geeft een domain object terug op basis van een orm object
     * 
     * @param mixed $orm_object
     * @return mixed domain object
     */
    public function fromOrm($orm_object)
    {
        $domain_object = new $this->class();
        $domain_object->fromArray($orm_object->toArray());

        return $domain_object;
    }
}

