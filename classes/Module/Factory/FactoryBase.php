<?php

declare(strict_types=1);

namespace preloader;

include_once('Factory.php');

abstract class FactoryBase extends Factory {
    
    protected $_moduleNamePostfix = null;
    
    protected $_isTestMode = false;
    
    protected $_database = null;
    
    public function setTestMode(): void {
        $this->_isTestMode = true;
    }
    
    protected function isTestMode(): bool {
        return $this->_isTestMode;
    }
    
    public function setDatabase(Database $database): bool {
        $result = false;
        
        if (is_object($database) && $database instanceof Database) {
            $this->_database = $database;
            $result = true;
        }
        
        return $result;
    }
    
    protected function getDatabase(): Database {
        return $this->_database;
    }
    
    public function createModule(string $moduleName, string $moduleBaseName = null) {
        $result = null;
        
        $loaded = Core::loadModule($moduleName, $moduleBaseName);
        if ($loaded) {
            $className = Core::getNamespace() . $moduleName;
            $result = new $className();
        }
        
        return $result;
    }
    
    public function createTypedModule(string $moduleBaseName) {
        $result = null;
        
        if ($moduleBaseName) {
            $moduleName = $moduleBaseName . $this->_moduleNamePostfix;
            $result = $this->createModule($moduleName, $moduleBaseName);
        }
        
        return $result;
    }
    
    public function createModel(string $modelName) {
        $result = null;
        
        $loaded = Core::loadModel($modelName);
        if ($loaded) {
            $className = Core::getNamespace() . $modelName;
            $result = new $className();
        }
        
        return $result;
    }
    
    public function createModelSite(): ModelSite {
        $modelName = 'ModelSite';
        $model = $this->createModel($modelName);
        if ($this->getDatabase()) {
            $model->setDatabase($this->getDatabase());
        }
        return $model;
    }
    
    public function createModelRequest(ModelSite $modelSite): ModelRequest {
        $modelName = 'ModelRequest';
        $model = $this->createModel($modelName);
        $model->setModelSite($modelSite);
        if ($this->getDatabase()) {
            $model->setDatabase($this->getDatabase());
        }
        return $model;
    }
    
    public function createModelLog(ModelSite $modelSite, ModelRequest $modelRequest): ModelLog {
        $modelName = 'ModelLog';
        $model = $this->createModel($modelName);
        $model->setModelSite($modelSite);
        $model->setModelRequest($modelRequest);
        if ($this->getDatabase()) {
            $model->setDatabase($this->getDatabase());
        }
        return $model;
    }
    
    public function createApplication(): Application {
        $moduleBaseName = 'Application';
        if (! $this->isTestMode()) {
            $object = $this->createTypedModule($moduleBaseName);
        } else {
            $moduleName = $moduleBaseName . 'Test';
            $object = $this->createModule($moduleName, $moduleBaseName);
        }
        return $object;
    }
    
    public function createChecker(ModelRequest $modelRequest, Router $router): Checker {
        $moduleBaseName = 'Checker';
        $object = $this->createTypedModule($moduleBaseName);
        $object->setModelRequest($modelRequest);
        $object->setRouter($router);
        return $object;
    }
    
    public function createConfig(): Config {
        Core::loadModule('Config');
        $type = $this->_moduleNamePostfix;
        Config::setType($type);
        $object = Config::instance();
        return $object;
    }
    
    public function createDatabase(): Database {
        $moduleBaseName = 'Database';
        if (! $this->isTestMode()) {
            $moduleName = $moduleBaseName . 'Mysql';
        } else {
            $moduleName = $moduleBaseName . 'Test';
        }
        $object = $this->createModule($moduleName, $moduleBaseName);
        return $object;
    }
    
    public function createLogger(ModelSite $modelSite, ModelRequest $modelRequest): Logger {
        $moduleName = 'Logger';
        $object = $this->createModule($moduleName );
        $object->setModelSite($modelSite);
        $object->setModelRequest($modelRequest);
        return $object;
    }
    
    public function createRegistry(): Registry {
        $moduleName = 'Registry';
        $object = $this->createModule($moduleName);
        return $object;
    }
    
    public function createRouter(ModelRequest $modelRequest): Router {
        $moduleBaseName = 'Router';
        $object = $this->createTypedModule($moduleBaseName);
        $object->setModelRequest($modelRequest);
        return $object;
    }
    
}
