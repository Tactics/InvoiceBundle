<?php

namespace Tactics\InvoiceBundle\Model;

class SchemeCustomerInfo
{
    protected $id;    
    protected $name;
    protected $value;
        
    protected $accounting_scheme;
    protected $customer;
    
    // getters
    public function getId()
	{
        return $this->id;
	}
    
    public function getName()
	{
        return $this->name;
	}
    
    public function getValue()
	{
        return $this->value;
	}
	
	public function getAccountingScheme()
	{
        return $this->accounting_scheme;
	}
    
    public function getCustomer()
	{
        return $this->customer;
	}
    
    // setters
    public function setId($v)
	{
        if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
		}
	}
    
    public function setName($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->name !== $v) {
			$this->name = $v;
		}
	}
    
    public function setValue($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->value !== $v) {
			$this->value = $v;
		}
	}
	
	public function setAccountingScheme($v)
    {
        $this->accounting_scheme = $v;
    }
    
    public function setCustomer($v)
    {
        $this->customer = $v;
    }
    
    public function __toString()
    {
        return $this->getName() . ': ' . $this->getValue();
    }
}

