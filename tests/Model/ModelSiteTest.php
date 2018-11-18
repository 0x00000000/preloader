<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../init.php');

Core::loadModule('ModelSite');

final class ModelSiteTest extends TestCase {
    
    public function testCreate(): void {
        $modelSite = Factory::instance()->createModelSite();
        $modelSite->create();
        
        $this->assertEquals($modelSite->url, ModelSite::UNKNOWN_SERVER_NAME);
        $this->assertEquals($modelSite->name, ModelSite::UNKNOWN_SERVER_NAME);
    }
    
    public function testLoadByUrl(): void {
        $modelSite = Factory::instance()->createModelSite();
        $modelSite->create();
        $this->assertTrue($modelSite->loadByUrl(ModelSite::UNKNOWN_SERVER_NAME));
        $this->assertEquals($modelSite->url, ModelSite::UNKNOWN_SERVER_NAME);
        $this->assertEquals($modelSite->name, ModelSite::UNKNOWN_SERVER_NAME);
    }
    
    public function testSetUrl(): void {
        $modelSite = Factory::instance()->createModelSite();
        
        $modelSite->url = 'www.example.com';
        $this->assertEquals($modelSite->url, 'example.com');
        
        $modelSite->url = 'www2.example.com';
        $this->assertEquals($modelSite->url, 'www2.example.com');
        
        $modelSite->url = 'test.www.example.com';
        $this->assertEquals($modelSite->url, 'test.www.example.com');
        
        $modelSite->url = 'example.www.com';
        $this->assertEquals($modelSite->url, 'example.www.com');
        
        $modelSite->url = '/index.php?www.example.com';
        $this->assertEquals($modelSite->url, '/index.php?www.example.com');
        
        $modelSite->url = 'www/index.php?www.example.com';
        $this->assertEquals($modelSite->url, 'www/index.php?www.example.com');
        
        $modelSite->url = 'example.com';
        $this->assertEquals($modelSite->url, 'example.com');
        
    }
    
    public function testDatabase(): void {
        $modelSiteSave = Factory::instance()->createModelSite();
        $modelSiteSave->create();
        $idSave = $modelSiteSave->save();
        $this->assertTrue(boolval($idSave));
        $dataAfterSave = $modelSiteSave->getDataAssoc();
        
        $modelSiteGet = Factory::instance()->createModelSite();
        $modelSiteGet->create();
        $modelSiteGet->loadById($idSave);
        $dataAfterGet = $modelSiteGet->getDataAssoc();
        
        $this->assertEquals($dataAfterSave, $dataAfterGet);
        
        $modelSiteGet->name .= '!';
        $idGet = $modelSiteGet->save();
        $dataAfterUpdated = $modelSiteGet->getDataAssoc();
        
        $modelSiteUpdatedGet = Factory::instance()->createModelSite();
        $modelSiteUpdatedGet->create();
        $modelSiteUpdatedGet->loadById($idGet);
        
        $dataAfterUpdatedGet = $modelSiteUpdatedGet->getDataAssoc();
        
        $this->assertEquals($dataAfterUpdated, $dataAfterUpdatedGet);
    }
    
    public function testCostants(): void {
        $this->assertTrue(! empty(ModelSite::UNKNOWN_SERVER_NAME));
    }
    
}
