<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../../init.php');

final class CheckerTest extends TestCase {
    protected $_checker;
    
    protected $_modelRequest = null;
    
    protected $_router = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->_modelSite = Factory::instance()->createModelSite();
        $this->_modelSite->create();
        $this->_modelRequest = Factory::instance()->createModelRequest($this->_modelSite);
        $this->_modelRequest->create();
        $this->_router = Factory::instance()->createRouter($this->_modelRequest);
        
        $this->_checker = Factory::instance()->createChecker($this->_modelRequest, $this->_router);
    }
    
    public function testModelSite() {
        $checker = Factory::instance()->createTypedModule('Checker');
        $notModelRequest = Factory::instance()->createModelSite();
        
        $this->assertFalse($checker->setModelRequest($notModelRequest));
        
        $this->assertTrue($checker->setModelRequest($this->_modelRequest));
    }
    
}
