<?php

namespace Tactics\InvoiceBundle\Propel;

use Symfony\Component\DependencyInjection\Container;

class Helper
{
    /**
     * Geeft de propel classname terug op basis van de namespaced name
     * 
     * @param string $class_name
     * @return string the propel class name
     */
    public static function getPropelClassName($class_name)
    {
        $rflClass = new \ReflectionClass($class_name);
        $shortName = $rflClass->getShortName();
        
        return "\Propel{$shortName}";
    }
    
    /**
     * Geeft de properties terug van de gegeven class
     * in een 2 dim array indexed op phpName en fieldName
     * 
     * @param mixed $argument a class name or an object
     * @return array
     */
    public static function getProperties($argument)
    {
        $rflClass = new \ReflectionClass($argument);
        $properties = $rflClass->getProperties();
        
        $propertyNames = array();
        foreach ($properties as $rflProp)
        {
            $propertyNames[\BasePeer::TYPE_FIELDNAME][] = $rflProp->name;
            $propertyNames[\BasePeer::TYPE_PHPNAME][] = ucfirst(Container::camelize($rflProp->name));
        }
        
        return $propertyNames;
    }
    
    /**
     * Converts array with $className class and id to object
     * 
     * @param array $array
     * @param string $className
     * 
     * @return mixed The object
     */
    public static function classAndIdToObject($array, $className)
    {
        $objectClass = isset($array[$className . 'Class']) ? $array[$className . 'Class'] : '';
        $objectId = isset($array[$className . 'Id']) ? $array[$className . 'Id'] : '';
        
        if (!($objectClass && $objectId) || !method_exists($objectClass . 'Peer', 'retrieveByPK'))
        {
           return null;
        }

        return call_user_func($objectClass . 'Peer::retrieveByPK', $objectId);
    }
    
    /**
     * Converts object to array with object id and class
     * 
     * @param mixed $object
     *
     * @return array The object
     */
    public static function objectToClassAndId($object, $className)
    {
        return array(
          $className . 'Id' => isset($object) ? $object->getId() : null,
          $className . 'Class' => isset($object) ? get_class($object) : null
        );
    }
}

