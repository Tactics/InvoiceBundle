<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model\ObjectManager;
use Tactics\InvoiceBundle\Model\Vat;
use Tactics\InvoiceBundle\Model\TransformableInterface;

class VatManager extends ObjectManager
{ 
    /**
     * 
     * @return Vat
     */
    public function create()
    {
        return new Vat();
    }
    
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\Vat $vat
     */
    public function save(TransformableInterface $vat)
    {
        $propelVat = $this->transformer->toOrm($vat);
        $propelVat->setNew(!$vat->getId());
        $propelVat->save();
    }
    
    /**
     * 
     * @param int $id
     * @return Tactics\InvoiceBundle\Model\Vat
     */
    public function find($id)
    {
        $propelVat = \PropelVatPeer::retrieveByPK($id);        
        
        return $this->transformer->fromOrm($propelVat);
    }
    
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\Vat $vat
     */
    public function delete(TransformableInterface $vat)
    {        
        $propelVat = $this->transformer->toOrm($vat);
        $propelVat->delete();
    }
    
    public function getDomainObject($propelVat)
    {
      return $this->transformer->fromOrm($propelVat);
    }
}
