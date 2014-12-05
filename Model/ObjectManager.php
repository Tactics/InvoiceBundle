<?php

namespace Tactics\InvoiceBundle\Model;

abstract class ObjectManager implements ObjectManagerInterface
{
    protected $transformer = null;
    protected $class = '';
    
    /**
     * constructor
     * 
     * @param \Tactics\InvoiceBundle\Model\TransformerInterface $transformer
     */
    public function __construct($class, TransformerInterface $transformer)
    {
        $this->class = $class;
        $this->transformer = $transformer;
    }
   
    /**
     * 
     * @return mixed
     */
    public function create()
    {
        $class = $this->class;
        $object = new $class();
        
        return $object;
    }
    
    /**
     * Geeft domain object op basis van orm object terug
     * 
     * @param mixed $orm_object
     * @return mixed
     */
    public function getDomainObject($orm_object)
    {
        return $this->transformer->fromOrm($orm_object);
    }
}