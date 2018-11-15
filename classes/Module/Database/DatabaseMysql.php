<?php

declare(strict_types=1);

namespace preloader;

include_once('Database.php');

class DatabaseMysql extends Database {
    protected $_data = array();
    private $_prefix = '';
    private $_mysqli = null;
    
    public function __construct() {
        $config = Config::instance();
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
    
    public function getById(string $table, string $id): ?array {
        $result = $this->getByKey($table, 'id', $id);
        
        return $result;
    }
    
    public function getByKey(string $table, string $key, string $value): ?array {
        $result = null;
        
        $query = 'select * from `' . $this->_mysqli->escape_string($this->_prefix . $table) . '`' . 
            ' where `' . $this->_mysqli->escape_string((string) $key) . '` = "' . 
            $this->_mysqli->escape_string((string) $value) . '"';
        $res = $this->_mysqli->query($query);
        if ($res) {
            $row = $res->fetch_assoc();
            if ($row) {
                $result = $row;
            }
        }
        
        return $result;
    }
    
    public function addRecord(string $table, array $data): ?string {
        $result = null;
        
        if (is_array($data) && count($data)) {
            $query = 'insert into ' . $this->_mysqli->escape_string($this->_prefix . $table);
            $keysArray = array();
            $valsArray = array();
            foreach ($data as $key => $val) {
                $keysArray[] = '`' . $this->_mysqli->escape_string((string) $key) . '`';
                $valsArray[] = '"' . $this->_mysqli->escape_string((string) $val) . '"';
            }
            
            $query .= ' (' . implode(', ', $keysArray) . ') ' . 
                ' values (' . implode(', ', $valsArray) . ')';
            
            if ($this->_mysqli->query($query)) {
                $result = (string) $this->_mysqli->insert_id;
            }
        }
        
        return $result;
    }
    
    public function updateRecord(string $table, array $data, string $primaryKey = 'id'): ?string {
        $result = null;
        
        if (is_array($data) && count($data) && array_key_exists($primaryKey, $data) && $data[$primaryKey]) {
            $query = 'update `' . $this->_mysqli->escape_string($this->_prefix . $table) . '` ';
            $valsArray = array();
            foreach ($data as $key => $val) {
                if ($key !== $primaryKey) {
                    $valsArray[] = '`' . $this->_mysqli->escape_string((string) $key) . '`'
                        . ' = '
                        . '"' . $this->_mysqli->escape_string((string) $val) . '"';
                }
            }
            
            if (count($valsArray)) {
                $query .= ' set ' . implode(', ', $valsArray)
                    . ' where `' . $this->_mysqli->escape_string((string) $primaryKey)
                    . '` = "' . $data[$primaryKey] . '"';
                
                if ($this->_mysqli->query($query)) {
                    $result = (string) $data[$primaryKey];
                }
            }
        }
        
        return $result;
    }
    
}
