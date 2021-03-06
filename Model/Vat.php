<?php

namespace Tactics\InvoiceBundle\Model;

class Vat
{
    protected $code;
    protected $name;
    protected $percentage;
    protected $scheme_id;
    protected $account_code;
    
    // getters
    public function getCode()
	{
		return $this->code;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getPercentage()
	{
		return $this->percentage;
	}
    
    public function getSchemeId()
    {
        return $this->scheme_id;
    }
    
    public function getAccountCode()
    {
        return $this->account_code;
    }
    
    // setters
    public function setCode($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->code !== $v) {
			$this->code = $v;
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
	
	public function setPercentage($v)
	{
        if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->percentage !== $v) {
			$this->percentage = $v;
		}
	}
    
    public function setSchemeId($v)
    {
        $this->scheme_id = $v;
    }
    
    public function setAccountCode($v)
    {
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->account_code !== $v) {
			$this->account_code = $v;            
        }   
    }
    
    public function __toString() 
    {
        return sprintf('%s: %s', $this->getCode(), $this->getName());
    }
}
