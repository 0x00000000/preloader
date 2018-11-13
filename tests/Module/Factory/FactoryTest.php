<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../../init.php');

final class FactoryTest extends TestCase {
    protected $_modelSite = null;
    
    protected $_modelRequest = null;
    
    protected $_router = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->_modelSite = Factory::instance()->createModelSite();
        $this->_modelRequest = Factory::instance()->createModelRequest($this->_modelSite);
        $this->_modelRequest->create();
        $this->_router = Factory::instance()->createRouter($this->_modelRequest);
    }
    
    public function testCreateModule() {
        $router = Factory::instance()->createTypedModule('Router');
        $this->assertTrue(is_object($router));
        
        $logger = Factory::instance()->createModule('Logger');
        $this->assertTrue(is_object($logger));
        
        $checker = Factory::instance()->createModule('CheckerEresus', 'Checker');
        $this->assertTrue(is_object($checker));
        
    }
    
    public function testCreateModel() {
        $site = Factory::instance()->createModel('ModelSite');
        $this->assertTrue(is_object($site));
        
        $request = Factory::instance()->createModel('ModelRequest');
        $this->assertTrue(is_object($request));
        
        $log = Factory::instance()->createModel('ModelLog');
        $this->assertTrue(is_object($log));
        
        $site = Factory::instance()->createModelSite();
        $this->assertTrue(is_object($site));
        
        $request = Factory::instance()->createModelRequest($site);
        $this->assertTrue(is_object($request));
        
        $log = Factory::instance()->createModelLog($site, $request);
        $this->assertTrue(is_object($log));
        
    }
    
    public function testCreateChecker() {
        $checker = Factory::instance()->createChecker($this->_modelRequest, $this->_router);
        $this->assertTrue(is_object($checker));
    }
    
    public function testCreateConfig() {
        $config = Factory::instance()->createConfig();
        $this->assertTrue(is_object($config));
    }
    
    public function testCreateDatabase() {
        $database = Factory::instance()->createDatabase();
        $this->assertTrue(is_object($database));
    }
    
    public function createLogger() {
        $logger = Factory::instance()->createLogger($this->_modelSite, $this->_modelRequest);
        $this->assertTrue(is_object($logger));
    }
    
    public function createRegistry() {
        $registry = Factory::instance()->createRegistry();
        $this->assertTrue(is_object($registry));
    }
    
    public function testCreateRouter() {
        $router = Factory::instance()->createRouter($this->_modelRequest);
        $this->assertTrue(is_object($router));
    }
    
}
