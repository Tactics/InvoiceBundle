<?php

namespace Tactics\InvoiceBundle\Propel;

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
}

