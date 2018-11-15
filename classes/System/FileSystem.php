<?php

declare(strict_types=1);

namespace preloader;

class FileSystem {
    private static $_root = null;
    
    public static function getRoot(): string {
        if (! self::$_root) {
            self::$_root = dirname(dirname(dirname(__FILE__)));
        }
        
        return self::$_root;
    }
    
    public static function getDirectorySeparator(): string {
        return DIRECTORY_SEPARATOR;
    }
    
    public static function getScriptExtension(): string {
        return '.php';
    }
    
}