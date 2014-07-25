<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;

class ObjectManager extends Model\ObjectManager
{
    private $propel_classname;
    private $pk_php_name;
    
    public function __construct($class, Model\TransformerInterface $transformer)
    {
        parent::__construct($class, $transformer);
        
        $this->propel_classname = Helper::getPropelClassName($this->class);
        $this->initPkPhpName();
    }
    
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
                $this->pk_php_name = $column->getPhpName();
                break;
            }
  		}
    }
    
    /**
     * 
     * @param mixed $pk a primary key value
     * @return mixed
     */
    public function find($pk)
    {
        if (!$pk)
        {
            return null;
        }
        
        $peerClass = "{$this->propel_classname}Peer";
        $ormObject = $peerClass::retrieveByPK($pk);
        
        return $ormObject ? $this->transformer->fromOrm($ormObject) : null;
    }
    
    /**
     * 
     * @param mixed $domain_object
     */
    public function save($domain_object)
    {
        $ormObject = $this->transformer->toOrm($domain_object);
        
        return $ormObject->save();
    }
    
    /**
     * 
     * @param mixed $domain_object
     */
    public function delete($domain_object)
    {        
        $ormObject = $this->transformer->toOrm($domain_object);
        
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
            array_map(create_function('$object', "return \$object->get{$this->pk_php_name}();"), $ormObjects),
            array_map(array($this->transformer, 'fromOrm'), $ormObjects)
        );
    }
    
    /**
     * 
     * @param array $search_fields [field_name] => value pairs  
     * @return mixed a domain object
     */
    public function searchOne($search_fields = array())
    {        
        $c = $this->createSearchCriteria($search_fields);
        
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
            $c->add($colName, $value);
        }
        
        // sorting
        $sortMethod = 'add' . ($sort_asc ? 'Asc' : 'Desc') . 'endingOrderByColumn';
        if (!$sort_by)
        {
            $sort_by = $peerClass::translateFieldName($this->pk_php_name, \BasePeer::TYPE_PHPNAME, \BasePeer::TYPE_FIELDNAME);
        }
        $sortByColName = $peerClass::translateFieldName($sort_by, \BasePeer::TYPE_FIELDNAME, \BasePeer::TYPE_COLNAME);
        $c->$sortMethod($sortByColName);
        
        return $c;
    }
}

