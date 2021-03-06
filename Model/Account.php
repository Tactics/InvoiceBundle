<?php

namespace Tactics\InvoiceBundle\Model;

class Account
{
    protected $code;
    protected $name;
    protected $type;
    protected $scheme_nr;
    protected $contra_account_code;
    
    protected $scheme_id;
    
    // getters
    public function getCode()
	{
        return $this->code;
	}
	
	public function getName()
	{
        return $this->name;
	}
	
	public function getType()
	{
        return $this->type;
	}
	
	public function getSchemeNr()
	{
        return $this->scheme_nr;
	}
  
    public function getContraAccountCode()
    {
        return $this->contra_account_code;
    }
    
    public function getSchemeId()
    {
        return $this->scheme_id;
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
	
	public function setType($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->type !== $v) {
			$this->type = $v;
		}
	}
	
	public function setSchemeNr($v)
	{
        if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->scheme_nr !== $v) {
			$this->scheme_nr = $v;
		}
	}
    
    public function setContraAccountCode($v)
    {
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->contra_account_code !== $v) {
			$this->contra_account_code = $v;            
        }   
    }
    
    public function setSchemeId($v)
    {
        $this->scheme_id = $v;
    }
    
    public function __toString()
    {
        return $this->getCode() . ': ' . $this->getName();
    }
}

