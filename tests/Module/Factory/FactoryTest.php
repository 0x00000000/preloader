<?php

declare(strict_types=1);

namespace PreloaderTest\Module\Factory;

use PHPUnit\Framework\TestCase;

use Preloader\Module\Factory\Factory;

include_once(dirname(__FILE__) . '/../../init.php');

final class FactoryTest extends TestCase {
    protected $_modelSite = null;
    
    protected $_modelRequest = null;
    
    protected $_router = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->_modelSite = Factory::instance()->createModelSite();
        $this->_modelSite->create();
        $this->_modelRequest = Factory::instance()->createModelRequest($this->_modelSite);
        $this->_modelRequest->create();
        $this->_router = Factory::instance()->createRouter($this->_modelRequest);
    }
    
    public function testSetDatabase(): void {
        $moduleName = 'Logger';
        
        $moduleBaseName = 'Database';
        $moduleName = $moduleBaseName . 'Mysql';
        $database = Factory::instance()->createModule($moduleName, $moduleBaseName);
        $this->assertTrue(Factory::instance()->setDatabase($database));
        
        $moduleBaseName = 'Database';
        $moduleName = $moduleBaseName . 'Test';
        $database = Factory::instance()->createModule($moduleName, $moduleBaseName);
        $this->assertTrue(Factory::instance()->setDatabase($database));
    }
    
    public function testCreateModule(): void {
        $router = Factory::instance()->createTypedModule('Router');
        $this->assertTrue(is_object($router));
        
        $logger = Factory::instance()->createModule('Logger');
        $this->assertTrue(is_object($logger));
        
        $checker = Factory::instance()->createModule('CheckerEresus', 'Checker');
        $this->assertTrue(is_object($checker));
        
    }
    
    public function testCreateModel(): void {
        $site = Factory::instance()->createModel('ModelSite');
        $this->assertTrue(is_object($site));
        
        $request = Factory::instance()->createModel('ModelRequest');
        $this->assertTrue(is_object($request));
        
        $log = Factory::instance()->createModel('ModelLog');
        $this->assertTrue(is_object($log));
        
        $site = Factory::instance()->createModelSite();
        $site->create();
        $this->assertTrue(is_object($site));
        
        $request = Factory::instance()->createModelRequest($site);
        $this->assertTrue(is_object($request));
        
        $log = Factory::instance()->createModelLog($site, $request);
        $this->assertTrue(is_object($log));
        
    }
    
    public function testCreateChecker(): void {
        $checker = Factory::instance()->createChecker($this->_modelRequest, $this->_router);
        $this->assertTrue(is_object($checker));
    }
    
    public function testCreateConfig(): void {
        $config = Factory::instance()->createConfig();
        $this->assertTrue(is_object($config));
    }
    
    public function testCreateDatabase(): void {
        $database = Factory::instance()->createDatabase();
        $this->assertTrue(is_object($database));
    }
    
    public function createLogger(): void {
        $logger = Factory::instance()->createLogger($this->_modelSite, $this->_modelRequest);
        $this->assertTrue(is_object($logger));
    }
    
    public function createRegistry(): void {
        $registry = Factory::instance()->createRegistry();
        $this->assertTrue(is_object($registry));
    }
    
    public function testCreateRouter(): void {
        $router = Factory::instance()->createRouter($this->_modelRequest);
        $this->assertTrue(is_object($router));
    }
    
}
