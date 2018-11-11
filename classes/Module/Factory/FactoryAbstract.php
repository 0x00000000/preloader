<?php

namespace preloader;

abstract class FactoryAbstract {
    
    abstract public static function setType($type);
    
    abstract public static function instance();
    
    abstract public function createModule($moduleName, $moduleBaseName = null);
    
    abstract public function createModel($model);
    
    abstract public function createConfig();
    
    abstract public function createDatabase();
    
}