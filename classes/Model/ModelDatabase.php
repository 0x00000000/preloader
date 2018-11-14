<?php

namespace preloader;

include_once('ModelAbstract.php');

abstract class ModelDatabase extends ModelAbstract {
    protected $_table = null;
    protected $_hiddenPropsList = array('_table', '_hiddenPropsList', '_database');
    
    protected $_database = null;
    
    public function __construct() {
    }
    
    public function getById($id) {
        $result = false;
        
        if ($this->getDatabase()) {
            if ($this->_table && $id) {
                $dbData = $this->getDatabase()->getById($this->_table, $id);
                $result = $this->setDataFromDB($dbData);
            }
        }
        
        return $result;
    }
    
    public function getByKey($key, $value) {
        $result = false;
        
        if ($this->getDatabase()) {
            if ($this->_table && $key) {
                $dbData = $this->getDatabase()->getByKey($this->_table, $key, $value);
                $result = $this->setDataFromDB($dbData);
            }
        }
        
        return $result;
    }
    
    public function save() {
        $result = false;
        
        if ($this->getDatabase()) {
            if ($this->_table) {
                $data = $this->getDataForDB();
                
                if (count($data)) {
                    if (! $this->id) {
                        $id = $this->getDatabase()->addRecord($this->_table, $data);
                        if ($id) {
                            $this->id = $id;
                            $result = $this->id;
                        }
                    } else {
                        $this->getDatabase()->updateRecord($this->_table, $data);
                        $result = $this->id;
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function getDataAssoc() {
        $data = array();
        
        foreach ($this as $propName => $value) {
            if ($this->isDataProp($propName)) {
                if (! is_null($value)) {
                    $key = substr($propName, 1);
                    $data[$key] = $value;
                }
            }
        }
        
        return $data;
    }
    
    private function getDataForDB() {
        $data = $this->getDataAssoc();
        $dbData = array();
        
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $newKey = preg_replace_callback(
                    '/[A-Z]/',
                    function($matches) {
                        return '_' . strtolower($matches[0]);
                    },
                    $key
                );
                $dbData[$newKey] = $val;
            }
        }
        
        return $dbData;
    }
    
    private function setDataFromDB($dbData) {
        $result = false;
        $data = array();
        if (is_array($dbData)) {
            foreach ($dbData as $key => $value) {
                $propName = preg_replace_callback(
                    '/_\w/',
                    function($matches) {
                        return substr(strtoupper($matches[0]), 1);
                    },
                    $key
                );
                $propName = '_' . $propName;
                
                if ($this->isDataProp($propName)) {
                    $data[$propName] = $value;
                }
            }
            
            if (count($data)) {
                $result = true;
                foreach ($this as $propName => $value) {
                    if ($this->isDataProp($propName)) {
                        if (array_key_exists($propName, $data)) {
                            $this->$propName = $data[$propName];
                        } else {
                            $this->$propName = null;
                        }
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function __set($name, $value) {
        $propertyName = '_' . $name;
        if ($this->isDataProp($propertyName)) {
            $methodName = 'set' . ucfirst($name);
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            } else {
                $this->$propertyName = $value;
            }
        }
    }
    
    public function __get($name) {
        $result = null;
        $propertyName = '_' . $name;
        
        if ($this->isDataProp($propertyName)) {
            $methodName = 'get' . ucfirst($name);
            if (method_exists($this, $methodName)) {
                $result = $this->$methodName();
            } else {
                $result = $this->$propertyName;
            }
        }
        
        return $result;
    }
    
    public function setDatabase($database) {
        $result = false;
        if (is_object($database) && $database instanceof Database) {
            $this->_database = $database;
            $result = true;
        }
        
        return $result;
    }
    
    protected function getDatabase() {
        return $this->_database;
    }
    
    protected function isDataProp($propName) {
        $result = false;
        
        if (! in_array($propName, $this->_hiddenPropsList)) {
            if (substr($propName, 0, 1) === '_') {
                if (strpos($propName, '_', 1) === false) {
                    $result = true;
                }
            }
        }
        
        return $result;
    }
    
    protected function addHiddenProperty($propertyName) {
        $result = false;
        if (is_string($propertyName) && strlen($propertyName) && is_array($this->_hiddenPropsList)) {
            if (! in_array($propertyName, $this->_hiddenPropsList)) {
                $this->_hiddenPropsList[] = $propertyName;
            }
            $result = true;
        }
        
        return $result;
    }

}