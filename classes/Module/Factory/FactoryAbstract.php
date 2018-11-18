<?php

declare(strict_types=1);

namespace preloader;

/**
 * Creates modules and models.
 */
abstract class FactoryAbstract {
    
    /**
     * Sets factory module name postrix for singleton object.
     */
    abstract public static function setType(string $type): bool;
    
    /**
     * Gets factory singleton object.
     */
    abstract public static function instance(): Factory;
    
    /**
     * Turns on test mode.
     */
    abstract public function setTestMode(): void;
    
    /**
     * Sets database object.
     */
    abstract public function setDatabase(Database $database): bool;
    
    /**
     * Creates module object.
     */
    abstract public function createModule(string $moduleName, string $moduleBaseName = null): object;
    
    /**
     * Creates module object. Module name is calculated from $_moduleNamePostfix property.
     */
    abstract public function createTypedModule(string $moduleBaseName): object;
    
    /**
     * Creates model object.
     */
    abstract public function createModel(string $model): object;
    
    /**
     * Creates site model object.
     */
    abstract public function createModelSite(): ModelSite;
    
    /**
     * Creates request model object.
     */
    abstract public function createModelRequest(ModelSite $modelSite): ModelRequest;
    
    /**
     * Creates log model object.
     */
    abstract public function createModelLog(ModelSite $modelSite, ModelRequest $modelRequest): ModelLog;
    
    /**
     * Creates application module object.
     */
    abstract public function createApplication(): Application;
    
    /**
     * Creates checker module object.
     */
    abstract public function createChecker(ModelRequest $modelRequest, Router $router): Checker;
    
    /**
     * Creates config module object.
     */
    abstract public function createConfig(): Config;
    
    /**
     * Creates database module object.
     */
    abstract public function createDatabase(): Database;
    
    /**
     * Creates database logger object.
     */
    abstract public function createLogger(ModelSite $modelSite, ModelRequest $modelRequest): Logger;
    
    /**
     * Creates registry logger object.
     */
    abstract public function createRegistry(): Registry;
    
    /**
     * Creates router logger object.
     */
    abstract public function createRouter(ModelRequest $modelRequest): Router;
    
}