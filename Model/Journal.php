<?php

namespace Tactics\InvoiceBundle\Model;

class Journal
{
    protected $code;    
    protected $name;
    protected $with_vat = false;
    protected $credit_notes = false;
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
    
    public function getWithVat()
    {
      return $this->with_vat;
    }
    
    public function getCreditNotes()
    {
      return $this->credit_notes;
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
  
    public function setWithVat($withVat)
    {
        $this->with_vat = $withVat;
    }
    
    public function setCreditNotes($creditNotes)
    {
        $this->credit_notes = $creditNotes;
    }
    
    public function __toString()
    {
        return $this->getCode() . ': ' . $this->getName();
    }
}

