<?php

namespace Tactics\InvoiceBundle\Model;

class InvoiceItem
{    
    protected $id;
    protected $quantity = 1;
    protected $unit_price;    
    protected $price_ex_vat;
    protected $price_incl_vat;
    protected $description = '';    
    protected $group_description = '';
    protected $type = 'invoice';
    protected $vat_code;
    protected $vat_percentage;
    protected $gl_account_code;
    protected $analytical_1_account_code = null;
    protected $analytical_2_account_code = null;
    protected $analytical_3_account_code = null;
    protected $analytical_4_account_code = null;
    protected $analytical_5_account_code = null;
    protected $analytical_6_account_code = null;
    protected $analytical_7_account_code = null;
    
    protected $invoice;
    protected $object;

    // getters	
	  public function getId()
	  {
		    return $this->id;
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

    public function getObject()
    {
        return $this->object;
    }
    
    public function getVatCode()
	  {
		    return $this->vat_code;
	  }
    
    public function getVatPercentage()
	  {
		    return $this->vat_percentage;
	  }
    
    public function getGlAccountCode()
    {
        return $this->gl_account_code;
    }
    
    public function getAnalytical1AccountCode()
    {
        return $this->analytical_1_account_code;
    }
    
    public function getAnalytical2AccountCode()
    {
        return $this->analytical_2_account_code;
    }
    
    public function getAnalytical3AccountCode()
    {
        return $this->analytical_3_account_code;
    }
    
    public function getAnalytical4AccountCode()
    {
        return $this->analytical_4_account_code;
    }
    
    public function getAnalytical5AccountCode()
    {
        return $this->analytical_5_account_code;
    }
    
    public function getAnalytical6AccountCode()
    {
        return $this->analytical_6_account_code;
    }
    
    public function getAnalytical7AccountCode()
    {
        return $this->analytical_7_account_code;
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

    public function setObject($v)
    {
        $this->object = $v;
    }
    
    public function setVatCode($v)
    {
        $this->vat_code = $v;
    }
    
    public function setVatPercentage($v)
    {
        $this->vat_percentage = $v;
    }
    
    public function setGlAccountCode($gl_account_code)
    {
        $this->gl_account_code = $gl_account_code;
    }
    
    public function setAnalytical1AccountCode($analytical_account_code)
    {
        $this->analytical_1_account_code = $analytical_account_code;
    }
    
    public function setAnalytical2AccountCode($analytical_account_code)
    {
        $this->analytical_2_account_code = $analytical_account_code;
    }
    
    public function setAnalytical3AccountCode($analytical_account_code)
    {
        $this->analytical_3_account_code = $analytical_account_code;
    }
    
    public function setAnalytical4AccountCode($analytical_account_code)
    {
        $this->analytical_4_account_code = $analytical_account_code;
    }
    
    public function setAnalytical5AccountCode($analytical_account_code)
    {
        $this->analytical_5_account_code = $analytical_account_code;
    }
    
    public function setAnalytical6AccountCode($analytical_account_code)
    {
        $this->analytical_6_account_code = $analytical_account_code;
    }
    
    public function setAnalytical7AccountCode($analytical_account_code)
    {
        $this->analytical_7_account_code = $analytical_account_code;
    }
    
    public function calculatePrices()
    {
        $this->price_ex_vat = bcmul($this->quantity, $this->unit_price, 2);
        $vatMultiplier = bcdiv(bcadd(100, $this->vat_percentage, 2), 100, 2);
        $this->price_incl_vat = bcmul($this->price_ex_vat, $vatMultiplier, 2);
    }
}
