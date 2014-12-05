<?php

namespace Tactics\InvoiceBundle\Model;

class Journal
{
    protected $code;    
    protected $name;
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

		$this->code = $v;
    }
	
	public function setName($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		$this->name = $v;
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

