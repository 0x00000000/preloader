<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../init.php');

final class CoreTest extends TestCase {
    
    public function testGetApplicationType(): void {
        $this->assertEquals(Core::getApplicationType('Eresus'), 'Eresus');
    }
    
    public function testGetNamespace(): void {
        $this->assertEquals(Core::getNamespace(), 'preloader\\');
    }
    
    public function testLoadModule(): void {
        $this->assertTrue(Core::loadModule('Factory'));
        $this->assertTrue(Core::loadModule('Router'));
        $this->assertTrue(Core::loadModule('DatabaseMysql', 'Database'));
        
    }
    
    public function testLoadModel(): void {
        $this->assertTrue(Core::loadModel('ModelSite'));
        $this->assertTrue(Core::loadModel('ModelRequest'));
        $this->assertTrue(Core::loadModel('ModelLog'));
    }
    
}
