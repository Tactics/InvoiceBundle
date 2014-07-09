<?php

namespace Tactics\InvoiceBundle\Model;

class Invoice
{
    protected $id;
    protected $number;   
    protected $total = 0;	
	protected $vat;	
	protected $date;	
	protected $date_due;	
	protected $date_paid;	
	protected $status;	
	protected $amount_paid = 0;	
	protected $structured_communication;	
	protected $currency = 'EUR';
	
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
    
    public function getCustomer()
    {
        return $this->customer;
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
    
    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;        
    }    
    
    public function addItem(InvoiceItem $item)
    {
        $this->items[] = $item;
        $item->setInvoice($this);
        
        $this->calculateTotalAndVat();
    }    
    
    public function calculateTotalAndVat()
    {
        $this->total = 0;
        $this->vat = 0;
        foreach ($this->getItems() as $item)
        {
            $item->calculatePrices();
            $this->total += $item->getPriceExVat();
            $this->vat += $item->getPriceInclVat() - $item->getPriceExVat();
        }
    }
}
