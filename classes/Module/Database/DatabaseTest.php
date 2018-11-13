<?php

namespace preloader;

include_once('Database.php');

class DatabaseTest extends Database {
    protected $_data = array();
    
    protected $_lastId = 0;
    
    public function __construct() {
        
    }
    
    public function getById($table, $id) {
        $result = false;
        
        if (array_key_exists($table, $this->_data)) {
            if (array_key_exists($id, $this->_data[$table])) {
                $result = $this->_data[$table][$id];
            }
        }
        
        return $result;
    }
    
    public function getByKey($table, $key, $value) {
        $result = false;
        
        if ($key === 'id') {
            $result = $this->getById($table, $value);
        } else {
            if (array_key_exists($table, $this->_data)) {
                foreach ($this->_data[$table] as $id => $record) {
                    if (array_key_exists($key, $record) && $record[$key] === $value) {
                        $result = $record;
                        break;
                    }
                }
            }
        }
        
        return $result;
    }
    
    public function addRecord($table, $data) {
        $result = false;
        
        if (is_array($data) && count($data)) {
            if (! array_key_exists($table, $this->_data)) {
                $this->_data[$table] = array();
            }
            
            $this->_lastId++;
            $data['id'] = $this->_lastId;
            $this->_data[$table][$this->_lastId] = $data;
            $result = $this->_lastId;
        }
        
        return $result;
    }
    
    public function updateRecord($table, $data, $primaryKey = 'id') {
        $result = false;
        
        if (is_array($data) && count($data) && array_key_exists($primaryKey, $data) && $data[$primaryKey]) {
            // For this implementation DB we may ignore $primaryKey name and use it`s value.
            $record = $this->getById($table, $data[$primaryKey]);
            if ($record) {
                foreach ($data as $key => $val) {
                    if ($key !== $primaryKey) {
                        $record[$key] = $val;
                    }
                }
                
                $this->_data[$table][$data[$primaryKey]] = $record;
                $result = $data[$primaryKey];
            }
        }
        
        return $result;
    }
    
}
