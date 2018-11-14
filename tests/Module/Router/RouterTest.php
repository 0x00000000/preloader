<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

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
    
    public function testModelSite() {
        $router = Factory::instance()->createTypedModule('Router');
        $notModelRequest = Factory::instance()->createModelSite();
        
        $this->assertFalse($router->setModelRequest($notModelRequest));
        
        $this->assertTrue($router->setModelRequest($this->_modelRequest));
    }
    
}
