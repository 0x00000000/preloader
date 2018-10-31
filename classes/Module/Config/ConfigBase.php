<?php

namespace preloader;

include_once('Config.php');

class ConfigBase extends Config {
    protected $_data = array();
    
    public function __construct() {
    }
    
    public function get($section, $name = null) {
        $result = null;
        
        if (
            array_key_exists($section, $this->_data)
            && is_array($this->_data[$section])
        ) {
            if ($name && array_key_exists($name, $this->_data[$section])) {
                $result = $this->_data[$section][$name];
            } else {
                $result = $this->_data[$section];
            }
        }
        
        return $result;
    }
    
    public function add($section, $name, $value) {
        $result = false;

        if ($section && $name) {
            if (
                ! array_key_exists($section, $this->_data)
                || ! is_array($this->_data[$section])
            ) {
                $this->_data[$section] = array();
            }
            
            $this->_data[$section][$name] = $value;
            
            $result = true;
        }
        
        return $result;
    }
    
}
