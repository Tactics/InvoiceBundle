<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Propel\Helper;

class Transformer extends Model\Transformer
{
    private $properties = array();
    
    public function __construct($class)
    {
        $this->properties = Helper::getProperties($class);
                
        parent::__construct($class);
    }
    
    /**
     * Geeft een orm object terug op basis van een domain object
     * 
     * @param mixed $domain_object The domain object
     * @return mixed The propel object
     */
    public function toOrm($domain_object)
    {
        return $this->ormObjectFromArray($this->toArray($domain_object));
    }

    /**
     * Geeft een domain object terug op basis van een orm object
     * 
     * @param mixed $orm_object The propel object
     * @return mixed The domain object
     */
    public function fromOrm($orm_object)
    {
        return $this->domainObjectFromArray($orm_object->toArray());
    }
    
    /**
     * Geet object array terug
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
     * @param array $arr
     * @return mixed The propel object
     */
    protected function ormObjectFromArray($arr)
    {
        $propelClassName = Helper::getPropelClassName($this->class);
        $propelObject = new $propelClassName();
        $propelObject->fromArray($arr);
        $propelObject->setNew(!$propelObject->getId());
        
        return $propelObject;
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
}

