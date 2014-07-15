<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;

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
    
    /**
     * 
     * @param array $search_fields [field_name] => value pairs
     * @return array with domain objects, indexed by id
     */
    public function search($search_fields)
    {
        $propelClassName = Helper::getPropelClassName($this->class);
        $peerClass = "{$propelClassName}Peer";        
        $c = new \Criteria();
        foreach ($search_fields as $field_name => $value)
        {
            $colName = $peerClass::translateFieldName($field_name, \BasePeer::TYPE_FIELDNAME, \BasePeer::TYPE_COLNAME);
            $c->add($colName, $value);
        }
        $ormObjects = $peerClass::doSelect($c);
        
        return array_combine(
          array_map(create_function('$object', 'return $object->getId();'), $ormObjects),
          array_map(array($this->transformer, 'fromOrm'), $ormObjects)
        );        
    }
}

