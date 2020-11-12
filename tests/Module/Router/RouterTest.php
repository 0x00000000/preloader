<?php

declare(strict_types=1);

namespace PreloaderTest\Module\Router;

use PHPUnit\Framework\TestCase;

use Preloader\System\FileSystem;
use Preloader\Module\Config\Config;
use Preloader\Module\Factory\Factory;
use Preloader\Module\Router\Router;

include_once(dirname(__FILE__) . '/../../init.php');

final class RouterTest extends TestCase {
    protected $_router;
    
    protected $_modelRequest = null;
    
    public function __construct() {
        parent::__construct();
        
        $modelSite = Factory::instance()->createModelSite();
        $modelSite->create();
        
        $this->_modelRequest = Factory::instance()->createModelRequest($modelSite);
        $this->_modelRequest->create();
        
        $this->_router = Factory::instance()->createRouter($this->_modelRequest);
    }
    
    public function testGetRequestType(): void {
        
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_CLIENT);
        
        $this->_modelRequest->url = '/index.php';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_CLIENT);
        
        $this->_modelRequest->url = '/index.html';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_CLIENT);
        
        $this->_modelRequest->url = '/section1/section2';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_CLIENT);
        
        $this->_modelRequest->url = '/section1/section2/';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_CLIENT);
        
        $this->_modelRequest->url = '/section1/ajax';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_CLIENT);
        
        $this->_modelRequest->url = '/section1/admin/';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_CLIENT);
        
        $this->_modelRequest->url = '/ajax';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_AJAX);
        
        $this->_modelRequest->url = '/ajax/';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_AJAX);
        
        $this->_modelRequest->url = '/ajax.php';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_AJAX);
        
        $this->_modelRequest->url = '/ajax/section';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_AJAX);
        
        $this->_modelRequest->url = '/ajax?test=1';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_AJAX);
        
        $this->_modelRequest->url = '/ajax.php?test=1';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_AJAX);
        
        $this->_modelRequest->url = '/admin';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_ADMIN);
        
        $this->_modelRequest->url = '/admin/';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_ADMIN);
        
        $this->_modelRequest->url = '/admin.php';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_ADMIN);
        
        $this->_modelRequest->url = '/admin/section';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_ADMIN);
        
        $this->_modelRequest->url = '/admin?test=1';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_ADMIN);
        
        $this->_modelRequest->url = '/admin.php?test=1';
        $type = $this->_router->getRequestType();
        $this->assertEquals($type, Router::REQUEST_TYPE_ADMIN);
        
    }
    
    public function testGetSiteRoot(): void {
        $testSiteRoot = $this->_router->getSiteRoot();
        
        $dir = FileSystem::getRoot();
        $levels = Config::instance()->get('router', 'levelsToSiteRoot');
        for ($i = 0; $i < $levels; $i++) {
            $dir = dirname($dir);
        }
        $siteRoot = realpath($dir);
        
        $this->assertEquals($testSiteRoot, $siteRoot);
    }
    
    public function testModelSite(): void {
        $router = Factory::instance()->createTypedModule('Router');
        
        $this->assertTrue($router->setModelRequest($this->_modelRequest));
    }
    
    public function testCostants(): void {
        $this->assertTrue(! empty(Router::REQUEST_TYPE_ADMIN));
        $this->assertTrue(! empty(Router::REQUEST_TYPE_AJAX));
        $this->assertTrue(! empty(Router::REQUEST_TYPE_CLIENT));
    }
    
}
