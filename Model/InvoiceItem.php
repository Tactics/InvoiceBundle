<?php

namespace Tactics\InvoiceBundle\Model;

class InvoiceItem
{    
    protected $id;
    protected $scheme_id;
    protected $quantity = 1;
    protected $unit_price;    
    protected $price_ex_vat;
    protected $price_incl_vat;
    protected $description;    
    protected $group_description;
    protected $type;
    
    protected $invoice;
    protected $vat;
    protected $gl_account;
    protected $analytical_1_account = null;
    protected $analytical_2_account = null;
    protected $analytical_3_account = null;
    protected $analytical_4_account = null;
    protected $analytical_5_account = null;

    // getters	
	  public function getId()
	  {
		    return $this->id;
    }
    
    public function getSchemeId()
    {
        return $this->scheme_id;
    }
	
    public function getQuantity()
    {
        return $this->quantity;
    }
	
    public function getUnitPrice()
    {
        return $this->unit_price;
    }

    public function getPriceExVat()
    {
        return $this->price_ex_vat;
    }

    public function getPriceInclVat()
    {
        return $this->price_incl_vat;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getGroupDescription()
    {
        return $this->group_description;
    }

    public function getType()
    {
        return $this->type;
    }
    
    /**
     * 
     * @return Tactics\InvoiceBundle\Model\Invoice
     */
    public function getInvoice()
	{
		return $this->invoice;
	}
    
    /**
     * 
     * @return Tactics\InvoiceBundle\Model\Vat
     */
    public function getVat()
	{
		return $this->vat;
	}
    
    public function getGlAccount()
    {
        return $this->gl_account;
    }
    
    public function getAnalytical1Account()
    {
        return $this->analytical_1_account;
    }
    
    public function getAnalytical2Account()
    {
        return $this->analytical_2_account;
    }
    
    public function getAnalytical3Account()
    {
        return $this->analytical_3_account;
    }
    
    public function getAnalytical4Account()
    {
        return $this->analytical_4_account;
    }
    
    public function getAnalytical5Account()
    {
        return $this->analytical_5_account;
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
    
    public function setSchemeId($scheme_id)
    {
        $this->scheme_id = $scheme_id;
	}
	
	public function setQuantity($v)
	{
        if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->quantity !== $v) {
			$this->quantity = $v;			
		}
	} 
	
	public function setUnitPrice($v)
	{
        if ($this->unit_price !== $v) {
			$this->unit_price = $v;			
		}
	}
	
	public function setPriceExVat($v)
	{
		if ($this->price_ex_vat !== $v) {
			$this->price_ex_vat = $v;			
		}
	} 
	
	public function setPriceInclVat($v)
	{
		if ($this->price_incl_vat !== $v) {
			$this->price_incl_vat = $v;
		}
	} 
	
	public function setDescription($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->description !== $v) {
			$this->description = $v;
		}
	}

    public function setGroupDescription($v)
    {
        if ($this->group_description !== $v) {
          $this->group_description = $v;
        }
    }

    public function setType($v)
    {
      if ($this->type !== $v) {
          $this->type = $v;
      }
    }
    
    public function setInvoice($v)
    {
        $this->invoice = $v;
    }
    
    public function setVat($v)
    {
        $this->vat = $v;
    }
    
    public function setGlAccount($gl_account)
    {
        $this->gl_account = $gl_account;
    }
    
    public function setAnalytical1Account($analytical_account)
    {
        $this->analytical_1_account = $analytical_account;
    }
    
    public function setAnalytical2Account($analytical_account)
    {
        $this->analytical_2_account = $analytical_account;
    }
    
    public function setAnalytical3Account($analytical_account)
    {
        $this->analytical_3_account = $analytical_account;
    }
    
    public function setAnalytical4Account($analytical_account)
    {
        $this->analytical_4_account = $analytical_account;
    }
    
    public function setAnalytical5Account($analytical_account)
    {
        $this->analytical_5_account = $analytical_account;
    }
    
    public function calculatePrices()
    {
        $vatPct = $this->getVat() ? $this->getVat()->getPercentage() : 0;
        
        $this->price_ex_vat = $this->quantity * $this->unit_price;
        $this->price_incl_vat = $this->price_ex_vat * ((100 + $vatPct)/100);
    }
}
