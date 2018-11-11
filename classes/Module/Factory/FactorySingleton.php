<?php

namespace preloader;

include_once('FactoryAbstract.php');

abstract class FactorySingleton extends FactoryAbstract {
    
    protected static $_baseType = 'Factory';
    
    protected static $_type = null;
    
    protected static $_instance = null;
    
    protected static function getModuleName() {
        $moduleName = false;
        
        if (self::$_baseType && self::$_type) {
            $moduleName = self::$_baseType . self::$_type;
        }
        
        return $moduleName;
    }
    
    protected static function getBaseModuleName() {
        return self::$_baseType;
    }
    
    public static function setType($type) {
        $result = false;
        
        if (! self::$_type) {
            if ($type) {
                $result = true;
                self::$_type = $type;
            }
        }
        
        return $result;
    }
    
    public static function instance() {
        if (! self::$_instance && self::$_type) {
            $moduleName = self::getModuleName();
            $baseModuleName = self::getBaseModuleName();
            $loaded = Core::loadModule($moduleName, $baseModuleName);
            if ($loaded) {
                $className = Core::getNamespace() . $moduleName;
                self::$_instance = new $className();
            }
        }
        
        return self::$_instance;
    }
}