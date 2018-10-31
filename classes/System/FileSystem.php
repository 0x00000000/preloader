<?php

namespace preloader;

class FileSystem {
    private static $_root = null;
    
    public static function getRoot() {
        if (! self::$_root) {
            self::$_root = dirname(dirname(dirname(__FILE__)));
        }
        
        return self::$_root;
    }
    
    public static function getDirectorySeparator() {
        return DIRECTORY_SEPARATOR;
    }
    
    public static function getScriptExtension() {
        return '.php';
    }
    
}