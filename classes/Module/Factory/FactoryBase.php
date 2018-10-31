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
    
    public function createRegistry() {
        $moduleName = 'Registry';
        $object = $this->createModule($moduleName);
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
    
    public function createLogger() {
        $moduleName = 'Logger';
        $object = $this->createModule($moduleName);
        return $object;
    }
    
}
