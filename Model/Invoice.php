<?php

namespace Tactics\InvoiceBundle\Model;

class Invoice
{
    protected $id;
    protected $scheme_id;
    protected $number;
    protected $journal_code;
    protected $total = 0;	
    protected $vat = 0;
    protected $date;
    protected $date_due;
    protected $date_paid;
    protected $amount_paid = 0;
    protected $structured_communication;
    protected $currency = 'EUR';
    protected $send = 0;
    protected $exported = 0;
    protected $ref;
    protected $customer;
    protected $items = array();

		
    
    // getters
    public function getSend()
    {
        return $this->send;
    }

    public function getExported()
    {
        return $this->exported;
    }
    
    public function getRef()
    {
        return $this->ref;
    }

    public function getId()
    {
            return $this->id;
    }
    
    public function getSchemeId()
    {
            return $this->scheme_id;
    }

    public function getNumber()
    {
        return $this->number;
    }
    
    public function getJournalCode()
    {
    return $this->journal_code;
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
    
    public function clearItems()
    {
        $this->items = array();
    }
	
    // setters
    public function setSend($v)
    {
        if ($this->send !== $v) {
            $this->send = $v;
        }
    }

    public function setExported($v)
    {
        if ($this->exported !== $v) {
            $this->exported = $v;
        }
    }
    
    public function setRef($v)
    {
        if ($v !== null && !is_string($v)) {
            $v = (string) $v; 
        }

        if ($this->ref !== $v) {
            $this->ref = $v;
        }
    } 

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
    
    public function setJournalCode($journal_code)
    {
        $this->journal_code = $journal_code;
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
            $this->total = bcadd($this->total, $item->getPriceExVat(), 2);
            $this->vat = bcadd($this->vat, bcsub($item->getPriceInclVat(), $item->getPriceExVat(), 2), 2);
        }
    }
    
    public function __toString()
    {
        $type = $this->isCreditNote() ? 'Creditnota' : 'Factuur';
        return $this->getId()
            ? sprintf('%s %06u', $type, $this->getNumber())
            : sprintf('Nieuwe %s', $type)
        ;
    }
    
    /**
     * 
     * @param float $amount
     * @param string $cultureDate
     */
    public function addPayment($amount, $cultureDate)
    {
        $this->setAmountPaid(bcadd($this->getAmountPaid(), $amount, 2));
        if ($this->isPaid())
        {
            $this->setDatePaid(\myDateTools::cultureDateToPropelDate($cultureDate));
        }
    }

    public function getOutstandingAmount()
    {
        return bcsub(bcadd($this->total, $this->vat, 2), $this->getAmountPaid(), 2);
    }

    public function isSend()
    {
        if($this->send)
        {
            return true;
        }

        return false;
    }

    public function isExported()
    {
        if ($this->exported)
        {
            return true;
        }

        return false;
    }
    
    public function isPaid()
    {
        return bccomp($this->getOutstandingAmount(), 0, 2) === 0;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isCreditNote()
    {
      return bccomp($this->getTotal(), 0, 2) === -1;
    }
    
    /**
     * 
     * @return boolean
     */
    public function withVat()
    {
      return bccomp($this->getVat(), 0, 2) !== 0;
    }
}
