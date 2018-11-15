<?php

declare(strict_types=1);

namespace preloader;

include_once('Database.php');

class DatabaseTest extends Database {
    protected $_data = array();
    
    protected $_lastId = 0;
    
    public function __construct() {
        
    }
    
    public function getById(string $table, string $id): ?array {
        $result = null;
        
        if (array_key_exists($table, $this->_data)) {
            if (array_key_exists($id, $this->_data[$table])) {
                $result = $this->_data[$table][$id];
            }
        }
        
        return $result;
    }
    
    public function getByKey(string $table, string $key, string $value): ?array {
        $result = null;
        
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
    
    public function addRecord(string $table, array $data): ?string {
        $result = null;
        
        if (is_array($data) && count($data)) {
            if (! array_key_exists($table, $this->_data)) {
                $this->_data[$table] = array();
            }
            
            $this->_lastId++;
            $stringId = (string) $this->_lastId;
            $data['id'] = $stringId;
            $this->_data[$table][$stringId] = $data;
            $result = $stringId;
        }
        
        return $result;
    }
    
    public function updateRecord(string $table, array $data, string $primaryKey = 'id'): ?string {
        $result = null;
        
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
                $result = (string) $data[$primaryKey];
            }
        }
        
        return $result;
    }
    
}
