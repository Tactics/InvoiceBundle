<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Propel\Helper;

class Transformer extends Model\Transformer
{
    private $properties = array();

    private $domainObjects = array();
    private $ormObjects = array();
    
    public function __construct($class)
    {
        $this->properties = Helper::getProperties($class);
                
        parent::__construct($class);
    }
    
    /**
     * Geeft een orm object terug op basis van een domain object
     * 
     * @param mixed $domainObject The domain object or an array representing the domain object
     * @return mixed The propel object
     */
    public function toOrm($domainObject)
    {
        $ormObject = $this->getOrmObjectForDomainObject($domainObject);
              
        if ($ormObject)
        {
            $ormObject->fromArray($this->toArray($domainObject));
        }
        else
        {
            $ormObject = $this->createNewOrmObjectFromDomainObject($domainObject);
            
            $ormObjectHash = spl_object_hash($ormObject);
            
            $this->domainObjects[$ormObjectHash] = $domainObject;
            $this->ormObjects[spl_object_hash($domainObject)] = $ormObject;
        }
        
        return $ormObject;
    }

    /**
     * Geeft een domain object terug op basis van een orm object
     * 
     * @param mixed $ormObject The propel object
     * @return mixed The domain object
     */
    public function fromOrm($ormObject)
    {
        if (!$ormObject)
        {
          return null;
        }
        
        $ormObjectHash = spl_object_hash($ormObject);
    
        if (isset($this->domainObjects[$ormObjectHash]))
        {
            $domainObject = $this->domainObjects[$ormObjectHash];
        }
        else
        {
            $domainObject = $this->domainObjectFromArray($ormObject->toArray());
            
            $this->domainObjects[$ormObjectHash] = $domainObject;
            $this->ormObjects[spl_object_hash($domainObject)] = $ormObject;
        }
        
        return $domainObject;
    }
    
    /**
     * Geeft object array terug
     * 
     * @param mixed $domain_object The domain object
     * @return array [php_fieldname] => fieldvalue
     */
    protected function toArray($domain_object)
	{
        $result = array();        
        foreach ($this->properties[\BasePeer::TYPE_PHPNAME] as $property)
        {            
            $getter = 'get' . $property;            
            if (method_exists($domain_object, $getter))
            {
                $result[$property] = $domain_object->$getter();
            }            
        }

		return $result;
	}
    
    /**
     * Geeft domain object terug op basis van gegeven array
     * 
     * @param array $arr
     * @return mixed The domain object
     */
    protected function domainObjectFromArray($arr)
    {
        return $this->fromArray($arr);
    }
    
    /**
     * Geeft Propel object terug op basis van gegeven array
     * 
     * @param mixed $domainObject
     * @return mixed The propel object
     */
    protected function createNewOrmObjectFromDomainObject($domainObject)
    {
        $propelClassName = Helper::getPropelClassName($this->class);
        $ormObject = new $propelClassName();
        $ormObject->fromArray($this->toArray($domainObject));
        $ormObject->setNew(true);
        
        return $ormObject;
    }
    
    /**
     * Zet array values in object
     * 
     * @param array $arr
     * @return mixed The domain object
     */
    private function fromArray($arr)
	{   
        $domainObject = new $this->class();
        foreach ($this->properties[\BasePeer::TYPE_PHPNAME] as $property)
        {            
            $setter = 'set' . $property;
            if (array_key_exists($property, $arr) && method_exists($domainObject, $setter))
            {
                $domainObject->$setter($arr[$property]);
            }
        }
        
        return $domainObject;
	}
    
    /**
     * Geeft orm object terug uit cache
     * 
     * @param type $domainObject
     * @return mixed The orm object
     */
    public function getOrmObjectForDomainObject($domainObject)
    {
        if (isset($this->ormObjects[spl_object_hash($domainObject)]))
        {
            return $this->ormObjects[spl_object_hash($domainObject)];
        }
        
        return null;
    }
}

