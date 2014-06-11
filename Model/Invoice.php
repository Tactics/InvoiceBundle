<?php

namespace Tactics\InvoiceBundle\Model;

class Invoice
{
    private static $fieldNames = array(
        'Id', 
        'Number',
        'CustomerId',
        'CustomerClass',
        'Customer',
        'Total', 
        'Vat', 
        'Date', 
        'DateDue', 
        'DatePaid', 
        'Status', 
        'AmountPaid', 
        'StructuredCommunication', 
        'Currency', 
        'CreatedAt', 
        'UpdatedAt', 
        'CreatedBy', 
        'UpdatedBy'
	);
    
    protected $id;
    protected $number;
    protected $customer_id;
    protected $customer_class;
    protected $total;	
	protected $vat;	
	protected $date;	
	protected $date_due;	
	protected $date_paid;	
	protected $status;	
	protected $amount_paid = 0;	
	protected $structured_communication;	
	protected $currency = 'EUR';	
	protected $created_at;	
	protected $updated_at;	
	protected $created_by;	
	protected $updated_by;
	
    protected $customer;
    
	protected $items = array();
    
    // getters
    public function getId()
	{
		return $this->id;
	}
    
    public function getNumber()
    {
        return $this->number;
    }
    
    public function getCustomer()
    {
        return $this->customer;
    }
    
    public function getCustomerId()
    {
        return $this->customer_id;
    }
    
    public function getCustomerClass()
    {
        return $this->customer_class;
    }      
	
	public function getTotal()
	{
		return $this->total;
	}
	
	public function getVat()
	{
		return $this->vat;
	}
	
	public function getDate($format = 'Y-m-d')
	{
		if ($this->date === null || $this->date === '') {
			return null;
		} elseif (!is_int($this->date)) {
			$ts = strtotime($this->date);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [date] as date/time value: " . var_export($this->date, true));
			}
		} else {
			$ts = $this->date;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}
	
	public function getDateDue($format = 'Y-m-d')
	{
		if ($this->date_due === null || $this->date_due === '') {
			return null;
		} elseif (!is_int($this->date_due)) {
			$ts = strtotime($this->date_due);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [date_due] as date/time value: " . var_export($this->date_due, true));
			}
		} else {
			$ts = $this->date_due;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}
	
	public function getDatePaid($format = 'Y-m-d')
	{
		if ($this->date_paid === null || $this->date_paid === '') {
			return null;
		} elseif (!is_int($this->date_paid)) {
			$ts = strtotime($this->date_paid);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse value of [date_paid] as date/time value: " . var_export($this->date_paid, true));
			}
		} else {
			$ts = $this->date_paid;
		}
		if ($format === null) {
			return $ts;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $ts);
		} else {
			return date($format, $ts);
		}
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getAmountPaid()
	{
		return $this->amount_paid;
	}
	
	public function getStructuredCommunication()
	{
		return $this->structured_communication;
	}
	
	public function getCurrency()
	{
		return $this->currency;
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
    
    public function getItems()
    {
        return $this->items;
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
    
    public function setNumber($v)
    {
        if ($this->number !== $v) {
			$this->number = $v;
		}
    }
    
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
        $this->customer_id = $customer->getId();
        $this->customer_class = get_class($customer);
    }
	
	public function setTotal($v)
	{
		if ($this->total !== $v) {
			$this->total = $v;
		}
	} 
	
	public function setVat($v)
	{
		if ($this->vat !== $v) {
			$this->vat = $v;
		}
	} 
	
	public function setDate($v)
	{
		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [date] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->date !== $ts) {
			$this->date = $ts;
		}
	} 
	
	public function setDateDue($v)
	{
		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [date_due] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->date_due !== $ts) {
			$this->date_due = $ts;
		}
	}
	
	public function setDatePaid($v)
	{
		if ($v !== null && !is_int($v)) {
			$ts = strtotime($v);
			if ($ts === -1 || $ts === false) {
                throw new PropelException("Unable to parse date/time value for [date_paid] from input: " . var_export($v, true));
			}
		} else {
			$ts = $v;
		}
		if ($this->date_paid !== $ts) {
			$this->date_paid = $ts;
		}

	} 
	
	public function setStatus($v)
	{
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->status !== $v) {
			$this->status = $v;
		}
	} 
	
	public function setAmountPaid($v)
	{
		if ($this->amount_paid !== $v || $v === 0) {
			$this->amount_paid = $v;
		}
	} 
	
	public function setStructuredCommunication($v)
	{
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->structured_communication !== $v) {
			$this->structured_communication = $v;
		}
	} 
	
	public function setCurrency($v)
	{
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->currency !== $v || $v === 'EUR') {
			$this->currency = $v;
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
    
    public function addItem(InvoiceItem $item)
    {
        $this->items[] = $item;
        
        $item->setInvoice($this);
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
}
