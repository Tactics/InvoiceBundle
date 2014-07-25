<?php

namespace Tactics\InvoiceBundle\Model;

class Vat
{
    protected $code;
    protected $name;
    protected $percentage;
    
    use \Tactics\InvoiceBundle\Model\NewTrait;    
    
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
    
    public function __toString() 
    {
        return sprintf('%2u%% : %s', $this->getPercentage(), $this->getName());
    }
}
