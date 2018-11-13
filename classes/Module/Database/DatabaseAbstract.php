<?php

namespace preloader;

abstract class DatabaseAbstract {
    
    abstract public function getById($table, $id);
    
    abstract public function getByKey($table, $key, $value);
    
    abstract public function addRecord($table, $data);
    
    abstract public function updateRecord($table, $data, $primaryKey = 'id');
    
}