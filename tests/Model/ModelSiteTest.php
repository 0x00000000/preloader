<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../init.php');

Core::loadModule('ModelSite');

final class ModelSiteTest extends TestCase {
    
    public function testGetByUrl(): void {
        $modelSite = Factory::instance()->createModelSite();
        $this->assertTrue($modelSite->getByUrl(ModelSite::UNKNOWN_SERVER_NAME));
        $this->assertEquals($modelSite->url, ModelSite::UNKNOWN_SERVER_NAME);
        $this->assertEquals($modelSite->name, ModelSite::UNKNOWN_SERVER_NAME);
    }
    
    public function testProperties(): void {
        $modelSite = Factory::instance()->createModelSite();
        $this->assertEquals($modelSite->url, ModelSite::UNKNOWN_SERVER_NAME);
        $this->assertEquals($modelSite->name, ModelSite::UNKNOWN_SERVER_NAME);
    }
    
    public function testDatabase() {
        $modelSiteSave = Factory::instance()->createModelSite();
        $idSave = $modelSiteSave->save();
        $this->assertTrue(boolval($idSave));
        $dataAfterSave = $modelSiteSave->getDataAssoc();
        
        $modelSiteGet = Factory::instance()->createModelSite();
        $modelSiteGet->getById($idSave);
        $dataAfterGet = $modelSiteGet->getDataAssoc();
        
        $this->assertEquals($dataAfterSave, $dataAfterGet);
        
        $modelSiteGet->name .= '!';
        $idGet = $modelSiteGet->save();
        $dataAfterUpdated = $modelSiteGet->getDataAssoc();
        
        $modelSiteUpdatedGet = Factory::instance()->createModelSite();
        $modelSiteUpdatedGet->getById($idGet);
        
        $dataAfterUpdatedGet = $modelSiteUpdatedGet->getDataAssoc();
        
        $this->assertEquals($dataAfterUpdated, $dataAfterUpdatedGet);
    }
    
}
