<?php

namespace preloader;

include_once('Factory.php');

abstract class FactoryBase extends Factory {
    
    protected $_moduleNamePostfix = null;
    
    public function createModule($moduleName, $moduleBaseName = null) {
        $result = false;
        
        $loaded = Core::loadModule($moduleName, $moduleBaseName);
        if ($loaded) {
            $className = Core::getNamespace() . $moduleName;
            $result = new $className();
        }
        
        return $result;
    }
    
    public function createTypedModule($moduleBaseName) {
        $result = false;
        
        if ($moduleBaseName) {
            $moduleName = $moduleBaseName . $this->_moduleNamePostfix;
            $result = $this->createModule($moduleName, $moduleBaseName);
        }
        
        return $result;
    }
    
    public function createModel($modelName) {
        $result = false;
        
        $loaded = Core::loadModel($modelName);
        if ($loaded) {
            $className = Core::getNamespace() . $modelName;
            $result = new $className();
        }
        
        return $result;
    }
    
    public function createModelSite() {
        $modelName = 'ModelSite';
        $model = Factory::instance()->createModel($modelName);
        return $model;
    }
    
    public function createModelRequest($modelSite) {
        $modelName = 'ModelRequest';
        $model = Factory::instance()->createModel($modelName);
        $model->setModelSite($modelSite);
        return $model;
    }
    
    public function createModelLog($modelSite, $modelRequest) {
        $modelName = 'ModelLog';
        $model = Factory::instance()->createModel($modelName);
        $model->setModelSite($modelSite);
        $model->setModelRequest($modelRequest);
        return $model;
    }
    
    public function createApplication() {
        $moduleBaseName = 'Application';
        $object = $this->createTypedModule($moduleBaseName);
        return $object;
    }
    
    public function createChecker($modelRequest, $router) {
        $moduleBaseName = 'Checker';
        $object = Factory::instance()->createTypedModule($moduleBaseName);
        $object->setModelRequest($modelRequest);
        $object->setRouter($router);
        return $object;
    }
    
    public function createConfig() {
        $moduleBaseName = 'Config';
        $object = $this->createTypedModule($moduleBaseName);
        return $object;
    }
    
    public function createDatabase() {
        $moduleBaseName = 'Database';
        $moduleName = $moduleBaseName . 'Mysql';
        $object = $this->createModule($moduleName, $moduleBaseName);
        return $object;
    }
    
    public function createLogger($modelSite, $modelRequest) {
        $moduleName = 'Logger';
        $object = Factory::instance()->createModule($moduleName );
        $object->setModelSite($modelSite);
        $object->setModelRequest($modelRequest);
        return $object;
    }
    
    public function createRegistry() {
        $moduleName = 'Registry';
        $object = Factory::instance()->createModule($moduleName);
        return $object;
    }
    
    public function createRouter($modelRequest) {
        $moduleBaseName = 'Router';
        $object = Factory::instance()->createTypedModule($moduleBaseName);
        $object->setModelRequest($modelRequest);
        return $object;
    }
    
}
