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
    public function __construct(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }
    
    /**
     * Sets the classname
     * 
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
    
    /**
     * 
     * @return mixed
     */
    public function create()
    {
        $class = $this->class;
        
        return new $class();
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