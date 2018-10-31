<?php

namespace preloader;

class Registry {
    
    protected static $_data = array();
    
    public static function get($name) {
        $result = null;
        
        if (array_key_exists($name, self::$_data)) {
            $result = self::$_data[$name];
        }
        
        return $result;
    }
    
    public static function set($name, $object) {
        $result = false;
        
        if (! array_key_exists($name, self::$_data)) {
            self::$_data[$name] = $object;
            $result = true;
        }
        
        return $result;
    }
    
}
