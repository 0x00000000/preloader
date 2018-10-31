<?php

namespace preloader;

abstract class ModelAbstract {
    
    abstract public function getById($id);
    
    abstract public function getByKey($key, $value);
    
    abstract public function save();
    
    abstract public function getDataAssoc();
    
}