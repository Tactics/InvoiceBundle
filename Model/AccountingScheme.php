<?php

namespace Tactics\InvoiceBundle\Model;

class AccountingScheme implements TransformableInterface
{
    private static $fieldNames = array(
        'Id', 
        'Name',        
        'Scheme1Name',
        'Scheme1Active',
        'Scheme2Name',
        'Scheme2Active',
        'Scheme3Name',
        'Scheme3Active',
        'Scheme4Name',
        'Scheme4Active',
        'Scheme5Name',
        'Scheme5Active'
	);
    
    protected $id;
    protected $name;
    
    // max 5 analytical schemes possible
    protected $scheme_1_name;
    protected $schema_1_active = true;
    protected $scheme_2_name;
    protected $schema_2_active = true;
    protected $scheme_3_name;
    protected $schema_3_active = true;
    protected $scheme_4_name;
    protected $schema_4_active = true;
    protected $scheme_5_name;
    protected $schema_5_active = true;
    
    // getters
    public function getId()
	{
        return $this->id;
	}
	
	public function getName()
	{
        return $this->name;
	}
	
	public function getScheme1Name()
	{
        return $this->scheme_1_name;
	}
	
	public function getSchema1Active()
	{
        return $this->schema_1_active;
	}
	
	public function getScheme2Name()
	{
        return $this->scheme_2_name;
	}
	
	public function getSchema2Active()
	{
        return $this->schema_2_active;
	}
	
	public function getScheme3Name()
	{
        return $this->scheme_3_name;
	}
	
	public function getSchema3Active()
	{
        return $this->schema_3_active;
	}
	
	public function getScheme4Name()
	{
        return $this->scheme_4_name;
	}
	
	public function getSchema4Active()
	{
        return $this->schema_4_active;
	}
	
	public function getScheme5Name()
	{
        return $this->scheme_5_name;
	}
	
	public function getSchema5Active()
	{
		return $this->schema_5_active;
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
	
	public function setName($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->name !== $v) {
			$this->name = $v;
		}
	}
	
	public function setScheme1Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_1_name !== $v) {
			$this->scheme_1_name = $v;
		}
	}
	
	public function setSchema1Active($v)
	{
        if ($this->schema_1_active !== $v || $v === true) {
			$this->schema_1_active = $v;			
		}
	}
	
	public function setScheme2Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_2_name !== $v) {
			$this->scheme_2_name = $v;
		}
	}
	
	public function setSchema2Active($v)
	{
        if ($this->schema_2_active !== $v || $v === true) {
			$this->schema_2_active = $v;
		}
	}
	
	public function setScheme3Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_3_name !== $v) {
			$this->scheme_3_name = $v;
		}
	}
	
	public function setSchema3Active($v)
	{
        if ($this->schema_3_active !== $v || $v === true) {
			$this->schema_3_active = $v;
		}
	}
	
	public function setScheme4Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_4_name !== $v) {
			$this->scheme_4_name = $v;
		}
	}
	
	public function setSchema4Active($v)
	{
        if ($this->schema_4_active !== $v || $v === true) {
			$this->schema_4_active = $v;
		}
	}
	
	public function setScheme5Name($v)
	{
        if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->scheme_5_name !== $v) {
			$this->scheme_5_name = $v;
		}
	}
	
	public function setSchema5Active($v)
	{
        if ($this->schema_5_active !== $v || $v === true) {
			$this->schema_5_active = $v;
		}
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