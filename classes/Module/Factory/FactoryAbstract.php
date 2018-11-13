<?php

namespace preloader;

abstract class FactoryAbstract {
    
    abstract public static function setType($type);
    
    abstract public static function instance();
    
    abstract public function setTestMode();
    
    abstract public function createModule($moduleName, $moduleBaseName = null);
    
    abstract public function createModel($model);
    
    abstract public function createModelSite();
    
    abstract public function createModelRequest($modelSite);
    
    abstract public function createModelLog($modelSite, $modelRequest);
    
    abstract public function createApplication();
    
    abstract public function createChecker($modelRequest, $router);
    
    abstract public function createConfig();
    
    abstract public function createDatabase();
    
    abstract public function createLogger($modelSite, $modelRequest);
    
    abstract public function createRegistry();
    
    abstract public function createRouter($modelRequest);
    
}