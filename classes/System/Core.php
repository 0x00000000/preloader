<?php

declare(strict_types=1);

namespace preloader;

include_once('FileSystem.php');

class Core {
    
    private static $_applicationType = null;
    
    private static $_isTestMode = false;
    
    private static $_root = null;
    
    private static $_namespace = 'preloader\\';
    
    public static function setApplicationType(string $applicationType, bool $isTestMode = false): bool {
        $result = false;
        
        if (! self::$_applicationType) {
            if ($applicationType) {
                if ($isTestMode) {
                    self::$_isTestMode = true;
                }
                self::$_applicationType = $applicationType;
                $result = self::init();
            }
        }
        
        return $result;
    }
    
    public static function getApplicationType(): ?string {
        return self::$_applicationType;
    }
    
    public static function getNamespace(): string {
        return self::$_namespace;
    }
    
    public static function loadModule(string $moduleName, string $moduleBaseName = null): bool {
        $result = false;
        
        if ($moduleName) {
            if (! $moduleBaseName) {
                $moduleBaseName = $moduleName;
            }
            
            $root = FileSystem::getRoot();
            $ds = FileSystem::getDirectorySeparator();
            $extension = FileSystem::getScriptExtension();
            
            $fileName = $root . $ds . 'classes' . $ds . 'Module' . $ds . $moduleBaseName . $ds . $moduleName . $extension;
            if (is_file($fileName)) {
                include_once($fileName);
                $result = true;
            }
        }
        
        return $result;
    }
    
    public static function loadModel(string $modelName): bool {
        $result = false;
        
        $root = FileSystem::getRoot();
        $ds = FileSystem::getDirectorySeparator();
        $extension = FileSystem::getScriptExtension();
        
        $fileName = $root . $ds . 'classes' . $ds . 'Model' . $ds . $modelName . $extension;
        if (is_file($fileName)) {
            include_once($fileName);
            $result = true;
        }
        
        return $result;
    }
    
    public static function FatalError(string $message = null): void {
        $errorMessage = 'Fatal error.';
        if ($message) {
            $errorMessage .= ' ' . $message;
        }
        
        die($errorMessage);
    }
    
    private static function init(): bool {
        $result = false;
        
        if (self::getApplicationType()) {
            $result = true;
            
            self::loadModule('Factory');
            Factory::setType(self::getApplicationType());
            if (self::$_isTestMode) {
                Factory::instance()->setTestMode();
            }
            
            Factory::instance()->createRegistry();
            
            Factory::instance()->createConfig();
            
            $database = Factory::instance()->createDatabase();
            Factory::instance()->setDatabase($database);
            
        }
        return $result;
    }
    
}