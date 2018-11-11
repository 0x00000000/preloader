<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../../init.php');

final class FactoryTest extends TestCase {
    
    public function testCreateModule() {
        Core::loadModule('Factory');
        
        $router = Factory::instance()->createTypedModule('Router');
        $this->assertTrue(is_object($router));
        
        $logger = Factory::instance()->createModule('Logger');
        $this->assertTrue(is_object($logger));
        
        $database = Factory::instance()->createModule('DatabaseMysql', 'Database');
        $this->assertTrue(is_object($database));
        
    }
    
    public function testCreateModel() {
        Core::loadModule('Factory');
        
        $site = Factory::instance()->createModel('ModelSite');
        $this->assertTrue(is_object($site));
        
        $request = Factory::instance()->createModel('ModelRequest');
        $this->assertTrue(is_object($request));
        
        $log = Factory::instance()->createModel('ModelLog');
        $this->assertTrue(is_object($log));
        
    }
    
    public function testCreateDatabase() {
        Core::loadModule('Factory');
        
        $database = Factory::instance()->createDatabase();
        $this->assertTrue(is_object($database));
    }
    
    public function testCreateConfig() {
        
        Core::loadModule('Factory');
        
        $config = Factory::instance()->createConfig();
        $this->assertTrue(is_object($config));
        
    }

    
}
