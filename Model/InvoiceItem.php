<?php

namespace Tactics\InvoiceBundle\Model;

class InvoiceItem
{    
    private static $fieldNames = array(
        'Id',
        'Quantity',
        'UnitPrice',        
        'PriceExVat',
        'PriceInclVat', 
        'Description',        
	);
    
    protected $id;
    protected $quantity;
    protected $unit_price;    
    protected $price_ex_vat;
    protected $price_incl_vat;
    protected $description;    
    
    protected $invoice;
    protected $vat;
    
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
    
    public function getInvoice()
	{
		return $this->invoice;
	}
    
    public function getVat()
	{
		return $this->vat;
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
    
    public function setInvoice($v)
    {
        $this->invoice = $v;
    }
    
    public function setVat($v)
    {
        $this->vat = $v;
    } 
    
    /**
     * 
     * @return array
     */
    public function toArray()
	{
        $result = array();
        
        foreach (self::$fieldNames as $fieldName)
        {
            $getter = 'get' . $fieldName;        
            $result[$fieldName] = $this->$getter();
        }

		return $result;
	}
    
    /**
     * 
     * @param array $arr
     * @return Tactics\InvoiceBundle\Invoice
     */
    public function fromArray($arr)
	{   
        foreach (self::$fieldNames as $fieldName)
        {            
            if (array_key_exists($fieldName, $arr))
            {
                $setter = 'set' . $fieldName;
                $this->$setter($arr[$fieldName]);
            }
        }
        
        return $this;
	}
}
