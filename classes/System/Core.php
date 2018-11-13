<?php

namespace preloader;

include_once('FileSystem.php');

class Core {
    
    private static $_applicationType = null;
    
    private static $_isTestMode = false;
    
    private static $_root = null;
    
    private static $_namespace = 'preloader\\';
    
    public static function setApplicationType($applicationType, $isTestMode = false) {
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
    
    public static function getApplicationType() {
        return self::$_applicationType;
    }
    
    public static function getNamespace() {
        return self::$_namespace;
    }
    
    public static function loadModule($moduleName, $moduleBaseName = null) {
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
    
    public static function loadModel($modelName) {
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
    
    public static function FatalError($message = null) {
        $errorMessage = 'Fatal error.';
        if ($message) {
            $errorMessage .= ' ' . $message;
        }
        
        die($errorMessage);
    }
    
    private static function init() {
        $result = false;
        
        if (self::getApplicationType()) {
            $result = true;
            
            self::loadModule('Factory');
            Factory::setType(self::getApplicationType());
            if (self::$_isTestMode) {
                Factory::instance()->setTestMode();
            }
            
            Factory::instance()->createRegistry();
            
            $config = Factory::instance()->createConfig();
            if ($config) {
                Registry::set('config', $config);
                
                $database = Factory::instance()->createDatabase();
                Registry::set('database', $database);
            }
            
        }
        return $result;
    }
    
}