<?php

declare(strict_types=1);

namespace Preloader\System;

use Preloader\Module\Factory\Factory;

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
     * @var string $_namespacePrefix Namespace prefix.
     */
    private static $_namespacePrefix = 'Preloader\\';
    
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
     * Gets namespace prefix.
     * 
     * @return string Namespace prefix.
     */
    public static function getNamespacePrefix(): string {
        return self::$_namespacePrefix;
    }
    
    /**
     * Gets module class name.
     * 
     * @param string $moduleName Module's name.
     * @param string|null $moduleBaseName Module section name.
     * @return string|null Class name if class exists.
     */
    public static function getModuleClassName(string $moduleName, string $moduleBaseName = null): ?string {
        if (! $moduleBaseName) {
            $moduleBaseName = $moduleName;
        }
        
        $className = self::getNamespacePrefix() . 'Module\\' . $moduleBaseName . '\\' . $moduleName;
        
        return $className;
    }
    
    /**
     * Gets model class name.
     * 
     * @param string $modelName Model's name.
     * @return string|null Class name if class exists.
     */
    public static function getModelClassName(string $modelName): ?string {
        $className = self::getNamespacePrefix() . 'Model\\' . $modelName;
        
        return $className;
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
        
        self::setAutoloader();
        
        if (self::getApplicationType()) {
            $result = true;
            
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
    
    /**
     * Register classes autoload function.
     */
    private static function setAutoloader(): void {
        spl_autoload_register(function($class) {
            $namespacesList = [
                'PreloaderTest\\' => '/tests/',
                'Preloader\\' => '/classes/',
            ];
            
            $root = FileSystem::getRoot();
            $ds = FileSystem::getDirectorySeparator();
            
            $found = false;
            foreach ($namespacesList as $prefix => $path) {
                $length = strlen($prefix);
                if (strncmp($prefix, $class, $length) === 0) {
                    $found = true;
                    
                    // Get the relative class name.
                    $relativeClass = substr($class, $length);
                    
                    $file = $root . $path . str_replace('\\', $ds, $relativeClass) . '.php';
                    if (file_exists($file)) {
                        require $file;
                    }
                }
            }
            
            if (! $found)
                return; // Move to the next registered autoloader.
        });
    }
    
}