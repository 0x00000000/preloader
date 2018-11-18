<?php

declare(strict_types=1);

namespace preloader;

include_once('FileSystem.php');

/**
 * Common functionality.
 */
class Core {
    
    /**
     * @var string|null $_applicationType Application type.
     */
    private static $_applicationType = null;
    
    /**
     * @var bool $_isTestMode Is application runs in test mode.
     */
    private static $_isTestMode = false;
    
    /**
     * @var string $_namespace Script's namespace.
     */
    private static $_namespace = 'preloader\\';
    
    /**
     * Sets application type.
     * This type will be used by Facroty for creating modules.
     * 
     * @param string $applicationType Application type.
     * @param bool $isTestMode Is application was launched in test mode. Used in unit tests.
     * @return bool Is application type was successfully set.
     */
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
    
    /**
     * Gets application type.
     * 
     * @return string|null Application type.
     */
    public static function getApplicationType(): ?string {
        return self::$_applicationType;
    }
    
    /**
     * Gets namespace.
     * 
     * @return string Current namespace.
     */
    public static function getNamespace(): string {
        return self::$_namespace;
    }
    
    /**
     * Loads module. Includes it's file, bun doesn't create objects of module's class.
     * 
     * @param string $moduleName Module's name.
     * @param string|null $moduleBaseName Module section name.
     * @return bool Is module was succsessfully loaded.
     */
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
    
    /**
     * Loads model. Includes it's file, bun doesn't create objects of model's class.
     * 
     * @param string $modelName Model's name.
     * @param string|null $moduleBaseName Module section name.
     * @return bool Is module was succsessfully loaded.
     */
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
    
    /**
     * Writes error message and ends script's execution.
     * 
     * @param string $message Error message to be shown.
     */
    public static function FatalError(string $message = null): void {
        $errorMessage = 'Fatal error.';
        if ($message) {
            $errorMessage .= ' ' . $message;
        }
        
        die($errorMessage);
    }
    
    /**
     * Creates necessary modules for script's work.
     */
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