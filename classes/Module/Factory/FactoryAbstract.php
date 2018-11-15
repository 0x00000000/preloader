<?php

declare(strict_types=1);

namespace preloader;

abstract class FactoryAbstract {
    
    abstract public static function setType(string $type): bool;
    
    abstract public static function instance(): Factory;
    
    abstract public function setTestMode(): void;
    
    abstract public function setDatabase(Database $database): bool;
    
    abstract public function createModule(string $moduleName, string $moduleBaseName = null);
    
    abstract public function createModel(string $model);
    
    abstract public function createModelSite();
    
    abstract public function createModelRequest(ModelSite $modelSite): ModelRequest;
    
    abstract public function createModelLog(ModelSite $modelSite, ModelRequest $modelRequest): ModelLog;
    
    abstract public function createApplication(): Application;
    
    abstract public function createChecker(ModelRequest $modelRequest, Router $router): Checker;
    
    abstract public function createConfig(): Config;
    
    abstract public function createDatabase(): Database;
    
    abstract public function createLogger(ModelSite $modelSite, ModelRequest $modelRequest): Logger;
    
    abstract public function createRegistry(): Registry;
    
    abstract public function createRouter(ModelRequest $modelRequest): Router;
    
}