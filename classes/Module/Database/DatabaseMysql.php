<?php

namespace preloader;

include_once('Database.php');

class DatabaseMysql extends Database {
    protected $_data = array();
    private $_prefix = '';
    private $_mysqli = null;
    
    public function __construct() {
        $config = Registry::get('config');
        $server = $config->get('database', 'server');
        $login = $config->get('database', 'login');
        $password = $config->get('database', 'password');
        $dbname = $config->get('database', 'name');
        $this->_prefix = $config->get('database', 'prefix');
        
        $this->_mysqli = new \mysqli($server, $login, $password, $dbname);
        if ($this->_mysqli->connect_errno) {
            die('Can`t connect');
        }
        
    }
    
    public function getById($table, $id, $key = 'id') {
        $result = false;
        
        $query = 'select * from `' . $this->_mysqli->escape_string($this->_prefix . $table) . '`' . 
            ' where `' . $this->_mysqli->escape_string($key) . '` = "' . 
            $this->_mysqli->escape_string($id) . '"';
        $res = $this->_mysqli->query($query);
        if ($res) {
            $row = $res->fetch_assoc();
            if ($row) {
                $result = $row;
            }
        }
        
        return $result;
    }
    
    public function getByKey($table, $key, $value) {
        $result = false;
        
        $query = 'select * from `' . $this->_mysqli->escape_string($this->_prefix . $table) . '`' . 
            ' where `' . $this->_mysqli->escape_string($key) . '` = "' . 
            $this->_mysqli->escape_string($value) . '"';
        $res = $this->_mysqli->query($query);
        if ($res) {
            $row = $res->fetch_assoc();
            if ($row) {
                $result = $row;
            }
        }
        
        return $result;
    }
    
    public function addRecord($table, $data) {
        $result = false;
        
        if (is_array($data) && count($data)) {
            $query = 'insert into ' . $this->_mysqli->escape_string($this->_prefix . $table);
            $keysArray = array();
            $valsArray = array();
            foreach ($data as $key => $val) {
                $keysArray[] = '`' . $this->_mysqli->escape_string($key) . '`';
                $valsArray[] = '"' . $this->_mysqli->escape_string($val) . '"';
            }
            
            $query .= ' (' . implode(', ', $keysArray) . ') ' . 
                ' values (' . implode(', ', $valsArray) . ')';
            
            if ($this->_mysqli->query($query)) {
                $result = $this->_mysqli->insert_id;
            }
        }
        
        return $result;
    }
    
    public function updateRecord($table, $data, $primaryKey = 'id') {
        $result = false;
        
        if (is_array($data) && count($data) && array_key_exists($primaryKey, $data) && $data[$primaryKey]) {
            $query = 'update `' . $this->_mysqli->escape_string($this->_prefix . $table) . '` ';
            $valsArray = array();
            foreach ($data as $key => $val) {
                if ($key !== $primaryKey) {
                    $valsArray[] = '`' . $this->_mysqli->escape_string($key) . '`'
                        . ' = '
                        . '"' . $this->_mysqli->escape_string($val) . '"';
                }
            }
            
            if (count($valsArray)) {
                $query .= ' set ' . implode(', ', $valsArray)
                    . ' where `' . $this->_mysqli->escape_string($primaryKey)
                    . '` = "' . $data[$primaryKey] . '"';
                
                if ($this->_mysqli->query($query)) {
                    $result = $data[$primaryKey];
                }
            }
        }
        
        return $result;
    }
    
}
