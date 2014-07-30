<?php

namespace Tactics\InvoiceBundle\Model;

class Journal
{
    protected $code;    
    protected $name;
//    protected $accounting_scheme;
    
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
    
//    public function getAccountingScheme()
//    {
//        return $this->accounting_scheme;
//    }
  
    // setters
    public function setCode($v)
	{
        if ($v !== null && !is_string($v)) {
            $v = (string) $v; 
		}

		$this->code = $v;
    }
	
	public function setName($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		$this->name = $v;
	}
    
//    public function setAccountingScheme($v)
//	{
//        $this->accounting_scheme = $v;
//	}
    
    public function __toString()
    {
        return $this->getName();
    }
}

