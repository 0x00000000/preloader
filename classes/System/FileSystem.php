<?php

declare(strict_types=1);

namespace preloader;

/**
 * Functionality for work with file system.
 */
class FileSystem {
    
    /**
     * @var string|null $_root File system path to script`s root directory.
     */
    private static $_root = null;
    
    /**
     * Gets file system path to script`s root directory.
     * 
     * @return string Script`s root directory.
     */
    public static function getRoot(): string {
        if (! self::$_root) {
            self::$_root = dirname(dirname(dirname(__FILE__)));
        }
        
        return self::$_root;
    }
    
    /**
     * Gets directory separator.
     * 
     * @return string Directory separator.
     */
    public static function getDirectorySeparator(): string {
        return DIRECTORY_SEPARATOR;
    }
    
    /**
     * Gets php scripts extension used in this project.
     * 
     * @return string Scripts extension.
     */
    public static function getScriptExtension(): string {
        return '.php';
    }
    
}