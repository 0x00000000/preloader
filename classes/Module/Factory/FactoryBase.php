<?php

declare(strict_types=1);

namespace Preloader\Module\Factory;

use Preloader\System\Core;

use Preloader\Module\Application\Application;
use Preloader\Module\Checker\Checker;
use Preloader\Module\Config\Config;
use Preloader\Module\Database\Database;
use Preloader\Module\Factory\Factory;
use Preloader\Module\Logger\Logger;
use Preloader\Module\Registry\Registry;
use Preloader\Module\Router\Router;

use Preloader\Model\ModelDatabase;
use Preloader\Model\ModelLog;
use Preloader\Model\ModelRequest;
use Preloader\Model\ModelSite;

/**
 * Creates modules and models.
 */
abstract class FactoryBase extends Factory {
    
    /**
     * @var string $_moduleNamePostfix Postfix for some modules' names.
     */
    protected $_moduleNamePostfix = null;
    
    /**
     * @var bool $_isTestMode If test mode is turned on.
     */
    protected $_isTestMode = false;
    
    /**
     * @var Database $_database Database object.
     */
    protected $_database = null;
    
    /**
     * Turns on test mode.
     */
    public function setTestMode(): void {
        $this->_isTestMode = true;
    }
    
    /**
     * Checks if test mode is turned on.
     */
    protected function isTestMode(): bool {
        return $this->_isTestMode;
    }
    
    /**
     * Sets database object.
     */
    public function setDatabase(Database $database): bool {
        $result = false;
        
        if (is_object($database) && $database instanceof Database) {
            $this->_database = $database;
            $result = true;
        }
        
        return $result;
    }
    
    /**
     * Gets database object.
     */
    protected function getDatabase(): Database {
        return $this->_database;
    }
    
    /**
     * Creates module object.
     */
    public function createModule(string $moduleName, string $moduleBaseName = null): object {
        $result = null;
        
        $className = Core::getModuleClassName($moduleName, $moduleBaseName);
        if (class_exists($className)) {
            $result = new $className();
        }
        
        return $result;
    }
    
    /**
     * Creates module object. Module name is calculated from $_moduleNamePostfix property.
     */
    public function createTypedModule(string $moduleBaseName): object {
        $result = null;
        
        if ($moduleBaseName) {
            $moduleName = $moduleBaseName . $this->_moduleNamePostfix;
            $result = $this->createModule($moduleName, $moduleBaseName);
        }
        
        return $result;
    }
    
    /**
     * Creates model object.
     */
    public function createModel(string $modelName): object {
        $result = null;
        
        $className = Core::getModelClassName($modelName);
        if (class_exists($className)) {
            $result = new $className();
        }
        
        return $result;
    }
    
    /**
     * Creates site model object.
     */
    public function createModelSite(): ModelSite {
        $modelName = 'ModelSite';
        $model = $this->createModel($modelName);
        if ($this->getDatabase()) {
            $model->setDatabase($this->getDatabase());
        }
        return $model;
    }
    
    /**
     * Creates request model object.
     */
    public function createModelRequest(ModelSite $modelSite): ModelRequest {
        $modelName = 'ModelRequest';
        $model = $this->createModel($modelName);
        $model->setModelSite($modelSite);
        if ($this->getDatabase()) {
            $model->setDatabase($this->getDatabase());
        }
        return $model;
    }
    
    /**
     * Creates log model object.
     */
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
    
    /**
     * Creates application module object.
     */
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
    
    /**
     * Creates checker module object.
     */
    public function createChecker(ModelRequest $modelRequest, Router $router): Checker {
        $moduleBaseName = 'Checker';
        $object = $this->createTypedModule($moduleBaseName);
        $object->setModelRequest($modelRequest);
        $object->setRouter($router);
        return $object;
    }
    
    /**
     * Creates config module object.
     */
    public function createConfig(): Config {
        $type = $this->_moduleNamePostfix;
        Config::setType($type);
        $object = Config::instance();
        return $object;
    }
    
    /**
     * Creates database module object.
     */
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
    
    /**
     * Creates database logger object.
     */
    public function createLogger(ModelSite $modelSite, ModelRequest $modelRequest): Logger {
        $moduleName = 'Logger';
        $object = $this->createModule($moduleName );
        $object->setModelSite($modelSite);
        $object->setModelRequest($modelRequest);
        return $object;
    }
    
    /**
     * Creates registry logger object.
     */
    public function createRegistry(): Registry {
        $moduleName = 'Registry';
        $object = $this->createModule($moduleName);
        return $object;
    }
    
    /**
     * Creates router logger object.
     */
    public function createRouter(ModelRequest $modelRequest): Router {
        $moduleBaseName = 'Router';
        $object = $this->createTypedModule($moduleBaseName);
        $object->setModelRequest($modelRequest);
        return $object;
    }
    
}
