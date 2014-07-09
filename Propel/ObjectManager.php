<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Model\TransformableInterface;

class ObjectManager extends Model\ObjectManager
{
    /**
     * 
     * @param type $id
     * @return mixed
     */
    public function find($id)
    {
        $propelClassName = Helper::getPropelClassName($this->class);
        $peerClass = "{$propelClassName}Peer";
        $ormObject = $peerClass::retrieveByPK($id);
        
        return $this->transformer->fromOrm($ormObject);
    }
    
    /**
     * 
     * @param mixed $domain_object
     */
    public function save($domain_object)
    {
        $ormObject = $this->transformer->toOrm($domain_object);
        $ormObject->setNew(!$domain_object->getId());
        
        return $ormObject->save();
    }
    
    /**
     * 
     * @param mixed $domain_object
     */
    public function delete($domain_object)
    {        
        $ormObject = $this->transformer->toOrm($domain_object);
        
        return $ormObject->delete();
    }
}

