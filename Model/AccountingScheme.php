<?php

namespace Tactics\InvoiceBundle\Model;

class AccountingScheme
{
    protected $id;
    protected $name;
    
    // max 5 analytical schemes possible
    protected $scheme_1_name;
    protected $scheme_1_active = false;
    protected $scheme_2_name;
    protected $scheme_2_active = false;
    protected $scheme_3_name;
    protected $scheme_3_active = false;
    protected $scheme_4_name;
    protected $scheme_4_active = false;
    protected $scheme_5_name;
    protected $scheme_5_active = false;
    
    use \Tactics\InvoiceBundle\Model\NewTrait;
    
    // getters
    public function getId()
	{
        return $this->id;
	}
	
	public function getName()
	{
        return $this->name;
	}
	
	public function getScheme1Name()
	{
        return $this->scheme_1_name;
	}
	
	public function getScheme1Active()
	{
        return $this->scheme_1_active;
	}
	
	public function getScheme2Name()
	{
        return $this->scheme_2_name;
	}
	
	public function getScheme2Active()
	{
        return $this->scheme_2_active;
	}
	
	public function getScheme3Name()
	{
        return $this->scheme_3_name;
	}
	
	public function getScheme3Active()
	{
        return $this->scheme_3_active;
	}
	
	public function getScheme4Name()
	{
        return $this->scheme_4_name;
	}
	
	public function getScheme4Active()
	{
        return $this->scheme_4_active;
	}
	
	public function getScheme5Name()
	{
        return $this->scheme_5_name;
	}
	
	public function getScheme5Active()
	{
		return $this->scheme_5_active;
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
	
	public function setScheme1Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_1_name !== $v) {
			$this->scheme_1_name = $v;
		}
	}
	
	public function setScheme1Active($v)
	{
        if ($this->scheme_1_active !== $v || $v === true) {
			$this->scheme_1_active = $v;			
		}
	}
	
	public function setScheme2Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_2_name !== $v) {
			$this->scheme_2_name = $v;
		}
	}
	
	public function setScheme2Active($v)
	{
        if ($this->scheme_2_active !== $v || $v === true) {
			$this->scheme_2_active = $v;
		}
	}
	
	public function setScheme3Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_3_name !== $v) {
			$this->scheme_3_name = $v;
		}
	}
	
	public function setScheme3Active($v)
	{
        if ($this->scheme_3_active !== $v || $v === true) {
			$this->scheme_3_active = $v;
		}
	}
	
	public function setScheme4Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_4_name !== $v) {
			$this->scheme_4_name = $v;
		}
	}
	
	public function setScheme4Active($v)
	{
        if ($this->scheme_4_active !== $v || $v === true) {
			$this->scheme_4_active = $v;
		}
	}
	
	public function setScheme5Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_5_name !== $v) {
			$this->scheme_5_name = $v;
		}
	}
	
	public function setScheme5Active($v)
	{
        if ($this->scheme_5_active !== $v || $v === true) {
			$this->scheme_5_active = $v;
		}
	}
  
    public function __toString() 
    {
        return $this->getName();
    }
}