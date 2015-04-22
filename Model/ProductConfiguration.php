<?php

namespace Tactics\InvoiceBundle\Model;

class ProductConfiguration
{
    protected $id;
    protected $scheme_id;
    protected $product;
    protected $vat_code;
    protected $gl_account_code;
    protected $analytical_1_account_code = null;
    protected $analytical_2_account_code = null;
    protected $analytical_3_account_code = null;
    protected $analytical_4_account_code = null;
    protected $analytical_5_account_code = null;
    protected $analytical_6_account_code = null;
    protected $analytical_7_account_code = null;

    //setters
    public function getId()
	{
		return $this->id;
	}
    
    public function getSchemeId()
	{
		return $this->scheme_id;
	}
    
    public function getProduct()
    {
        return $this->product;
    }
    
    public function getVatCode()
    {
        return $this->vat_code;
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

		$this->id = $v;
	}
    
    public function setSchemeId($scheme_id)
    {
        $this->scheme_id = $scheme_id;
    }
    
    public function setProduct($product)
    {
        $this->product = $product;
    }
    
    public function setVatCode($vat_code)
    {
        $this->vat_code = $vat_code;
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
}