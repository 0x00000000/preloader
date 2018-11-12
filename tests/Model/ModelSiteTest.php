<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../init.php');

Core::loadModule('ModelSite');

final class ModelSiteTest extends TestCase {
    
    public function testGetByUrl(): void {
        $site = Factory::instance()->createModelSite();
        $this->assertTrue($site->getByUrl(ModelSite::UNKNOWN_SERVER_NAME));
        $this->assertEquals($site->url, ModelSite::UNKNOWN_SERVER_NAME);
        $this->assertEquals($site->name, ModelSite::UNKNOWN_SERVER_NAME);
    }
    
    public function testProperties(): void {
        $site = Factory::instance()->createModelSite();
        $this->assertEquals($site->url, ModelSite::UNKNOWN_SERVER_NAME);
        $this->assertEquals($site->name, ModelSite::UNKNOWN_SERVER_NAME);
    }
    
}
