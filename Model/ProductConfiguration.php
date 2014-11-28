<?php

namespace Tactics\InvoiceBundle\Model;

class ProductConfiguration
{
    protected $id;
    protected $scheme_id;
    protected $product;
    protected $vat;
    protected $gl_account;
    protected $analytical_1_account = null;
    protected $analytical_2_account = null;
    protected $analytical_3_account = null;
    protected $analytical_4_account = null;
    protected $analytical_5_account = null;
    
    use \Tactics\InvoiceBundle\Model\NewTrait;
  
    //setters
    public function getId()
	{
		return $this->id;
	}
    
    public function getSchemeId()
	{
		return $this->id;
	}
    
    public function getProduct()
    {
        return $this->product;
    }
    
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
    
    public function setVat($vat)
    {
        $this->vat = $vat;
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
}