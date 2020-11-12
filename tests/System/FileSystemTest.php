<?php

declare(strict_types=1);

namespace PreloaderTest\System;

use PHPUnit\Framework\TestCase;

use Preloader\System\FileSystem;

include_once(dirname(__FILE__) . '/../init.php');

final class FileSystemTest extends TestCase {
    
    public function testGetRoot(): void {
        $this->assertEquals(FileSystem::getRoot(), dirname(dirname(dirname(__FILE__))));
    }
    
    public function testGetDirectorySeparator(): void {
        $this->assertEquals(FileSystem::getDirectorySeparator(), DIRECTORY_SEPARATOR);
    }
    
    public function testGetScriptExtension(): void {
        $this->assertEquals(FileSystem::getScriptExtension(), '.php');
    }
    
}
