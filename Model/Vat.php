<?php

namespace Tactics\InvoiceBundle\Model;

class Vat
{
    private static $fieldNames = array(
        'Id', 
        'Name',
        'Percentage'
	);
    
    protected $id;
    protected $name;
    protected $percentage;
    
    
    // getters
    public function getId()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getPercentage()
	{
		return $this->percentage;
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
	
	public function setPercentage($v)
	{
        if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->percentage !== $v) {
			$this->percentage = $v;
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
