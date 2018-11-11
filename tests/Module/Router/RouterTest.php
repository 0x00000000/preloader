<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../../init.php');

final class RouterTest extends TestCase {
    
    public function testGetRequestType(): void {
        $router = Factory::instance()->createTypedModule('Router');
        
        $type = $router->getRequestType();
        
        $this->assertEquals($type, Router::REQUEST_TYPE_CLIENT);
    }
    
    public function testGetSiteRoot(): void {
        $router = Factory::instance()->createTypedModule('Router');
        
        $testSiteRoot = $router->getSiteRoot();
        
        $dir = FileSystem::getRoot();
        $levels = Registry::get('config')->get('router', 'levelsToSiteRoot');
        for ($i = 0; $i < $levels; $i++) {
            $dir = dirname($dir);
        }
        $siteRoot = realpath($dir);
        
        $this->assertEquals($testSiteRoot, $siteRoot);
    }
    
    
}
