<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Model\TransformableInterface;

abstract class ObjectManager extends Model\ObjectManager
{
    /**
     * 
     * @param type $id
     * @return mixed
     */
    public function find($id)
    {
        $className = substr($this->class, strrpos($this->class, '\\') + 1);
        $peerClass = "\Propel{$className}Peer";        
        $ormObject = $peerClass::retrieveByPK($id);
        
        return $this->transformer->fromOrm($ormObject);
    }
    
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\TransformableInterface $domain_object
     */
    public function save(TransformableInterface $domain_object)
    {
        $ormObject = $this->transformer->toOrm($domain_object);
        $ormObject->setNew(!$domain_object->getId());
        
        return $ormObject->save();
    }
    
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\TransformableInterface $domain_object
     */
    public function delete(TransformableInterface $domain_object)
    {        
        $ormObject = $this->transformer->toOrm($domain_object);
        
        return $ormObject->delete();
    }
}

