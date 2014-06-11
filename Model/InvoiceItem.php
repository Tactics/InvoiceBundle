<?php

namespace Tactics\InvoiceBundle\Model;

class InvoiceItem
{    
    private static $fieldNames = array(
        'Id', 
        'InvoiceId',
        'Quantity',
        'UnitPrice',
        'PriceExVat',
        'PriceInclVat', 
        'Description',        
        'CreatedAt',
        'UpdatedAt',
        'CreatedBy',
        'UpdatedBy'
	);
    
    protected $id;
    protected $invoice_id;
    protected $quantity;
    protected $unit_price;
    protected $price_ex_vat;
    protected $price_incl_vat;
    protected $description;
    protected $created_at;	
	protected $updated_at;	
	protected $created_by;	
	protected $updated_by;

    //protected $invoice;

    // getters	
	public function getId()
	{
		return $this->id;
	}
	
	public function getInvoiceId()
	{
		return $this->invoice_id;
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
    
    public function getCreatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->created_at === null || $this->created_at === '') {
			return null;
		} elseif (!is_int($this->created_at)) {
			$ts = strtotime($this->created_at);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [created_at] as date/time value: " . var_export($this->created_at, true));
			}
		} else {
			$ts = $this->created_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}
	
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->updated_at === null || $this->updated_at === '') {
			return null;
		} elseif (!is_int($this->updated_at)) {
			$ts = strtotime($this->updated_at);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [updated_at] as date/time value: " . var_export($this->updated_at, true));
			}
		} else {
			$ts = $this->updated_at;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}
	
	public function getCreatedBy()
	{
		return $this->created_by;
	}
	
	public function getUpdatedBy()
	{
		return $this->updated_by;
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
	
	public function setInvoiceId($v)
	{
        if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->invoice_id !== $v) {
			$this->invoice_id = $v;			
		}

//		if ($this->invoice !== null && $this->invoice->getId() !== $v) {
//			$this->invoice = null;
//		}
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
    
    public function setCreatedAt($v)
	{
        if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [created_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->created_at !== $ts) {
			$this->created_at = $ts;
		}
	} 
	
	public function setUpdatedAt($v)
	{
        if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [updated_at] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->updated_at !== $ts) {
			$this->updated_at = $ts;
		}
	} 
	
	public function setCreatedBy($v)
	{
        if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->created_by !== $v) {
			$this->created_by = $v;
		}
	} 
	
	public function setUpdatedBy($v)
	{
        if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->updated_by !== $v) {
			$this->updated_by = $v;			
		}
	}
    
    public function setInvoice($v)
    {
        if ($v === null) {
			$this->setInvoiceId(NULL);
		} else {
			$this->setInvoiceId($v->getId());
		}
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
