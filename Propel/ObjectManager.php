<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Model\TransformerInterface;

class ObjectManager extends Model\ObjectManager
{
    private $propel_classname;
    protected $pk_php_name;
    
    /**
     * 
     * @param string $class a model class name
     * @param TransformerInterface $transformer
     */
    public function __construct($class, TransformerInterface $transformer)
    {
        parent::__construct($class, $transformer);
        
        $this->propel_classname = Helper::getPropelClassName($this->class);
        $this->initPkPhpName();
    }
    
    /**
     * finds the primary keys fields of the related propel class
     */
    private function initPkPhpName()
    {
        $tableName = eval("return " . $this->propel_classname . "Peer::TABLE_NAME;");
  		$tmpBuilder = eval("return new " . $this->propel_classname . "MapBuilder();");
  		$tmpBuilder->doBuild();
  		$table_map = $tmpBuilder->getDatabaseMap()->getTable($tableName);

  		foreach($table_map->getColumns() as $column)
        {
  			if ($column->isPrimaryKey())
            {
                $this->pk_php_name[] = $column->getPhpName();
            }
  		}
    }
    
    /**
     * 
     * @param mixed $pk a primary key value
     * @return mixed $domainObject
     */
    public function find($pk)
    {
        $pk = is_array($pk) ? $pk : array($pk);
        // fix zodat scheme_id altijd meegegeven kan worden als 2e PK
        $numberOfPks = count($this->pk_php_name);
        $pk = array_slice($pk, 0, $numberOfPks);
        
        $peerClass = "{$this->propel_classname}Peer";
        $ormObject = call_user_func_array("$peerClass::retrieveByPK", $pk);
        
        $domainObject = $ormObject ? $this->transformer->fromOrm($ormObject) : null;
        
        return $domainObject;
    }
    
    /**
     * 
     * @param mixed $domainObject
     */
    public function save($domainObject)
    {
        $ormObject = $this->transformer->toOrm($domainObject);
        $result = $ormObject->save();
        
        // setting the id
        foreach ($this->pk_php_name as $pkName)
        {
            $pkSetter = 'set' . $pkName;
            $pkGetter = 'get' . $pkName;
            $domainObject->$pkSetter($ormObject->$pkGetter());
        }
        
        return $result;
    }
    
    /**
     * 
     * @param mixed $domain_object
     */
    public function delete($domainObject)
    {   
        $ormObject = $this->transformer->getOrmObjectForDomainObject($domainObject);
        
        if (! $ormObject) {
            throw new \Exception('Cannot map domain object to orm object');
        }
        
        return $ormObject->delete();
    }
    
    /**
     * 
     * @param array $search_fields [field_name] => value pairs
     * @param string $sort_by field_name to sort on
     * @param bool $sort_asc default true sort asc or desc
     * @return array an array with sorted domain objects, indexed by id
     */
    public function search($search_fields = array(), $sort_by = null, $sort_asc = true)
    {
        $c = $this->createSearchCriteria($search_fields, $sort_by, $sort_asc);
        
        $peerClass = "{$this->propel_classname}Peer";
        $ormObjects = $peerClass::doSelect($c);
        
        return array_combine(
            array_map(create_function('$object', "return \$object->get{$this->pk_php_name[0]}();"), $ormObjects),
            array_map(array($this->transformer, 'fromOrm'), $ormObjects)
        );
    }
    
    /**
     * 
     * @param array $search_fields [field_name] => value pairs  
     * @return mixed a domain object
     */
    public function searchOne($search_fields = array(), $sort_by = null, $sort_asc = true)
    {        
        $c = $this->createSearchCriteria($search_fields, $sort_by, $sort_asc);
        
        $propelClassName = Helper::getPropelClassName($this->class);
        $peerClass = "{$propelClassName}Peer";
        $propelObject = $peerClass::doSelectOne($c);
        
        return $propelObject ? $this->transformer->fromOrm($propelObject) : null;
    }
    
    /**
     * 
     * @param array $search_fields [field_name] => value pairs
     * @param string $sort_by field_name to sort on
     * @param bool $sort_asc default true sort asc or desc
     * @return \Criteria
     */
    private function createSearchCriteria($search_fields = array(), $sort_by = null, $sort_asc = true)
    {
        // creating the propel criteria
        $propelClassName = Helper::getPropelClassName($this->class);
        $peerClass = "{$propelClassName}Peer";        
        $c = new \Criteria();
        foreach ($search_fields as $field_name => $value)
        {
            $colName = $peerClass::translateFieldName($field_name, \BasePeer::TYPE_FIELDNAME, \BasePeer::TYPE_COLNAME);
            $comparison = is_array($value)
              ? \Criteria::IN
              : (strpos($value, '%') !== null ? \Criteria::LIKE : \Criteria::EQUAL);
            $c->add($colName, $value, $comparison);
        }
        
        // sorting
        $sortMethod = 'add' . ($sort_asc ? 'Asc' : 'Desc') . 'endingOrderByColumn';
        if (!$sort_by)
        {
            $sort_by = $peerClass::translateFieldName($this->pk_php_name[0], \BasePeer::TYPE_PHPNAME, \BasePeer::TYPE_FIELDNAME);
        }
        $sortByColName = $peerClass::translateFieldName($sort_by, \BasePeer::TYPE_FIELDNAME, \BasePeer::TYPE_COLNAME);
        $c->$sortMethod($sortByColName);
        
        return $c;
    }
}

