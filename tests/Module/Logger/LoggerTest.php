<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../../init.php');

final class LoggerTest extends TestCase {
    protected $_logger = null;
    
    protected $_modelRequest = null;
    
    public function __construct() {
        parent::__construct();
        
        $modelSite = Factory::instance()->createModelSite();
        
        $this->_modelRequest = Factory::instance()->createModelRequest($modelSite);
        $this->_modelRequest->create();
        
        $this->_logger = Factory::instance()->createLogger($modelSite, $this->_modelRequest);
    }
    
    public function testLogCritical() {
        $result = $this->_logger->logCritical(
            'Testing log critical caption', 'Testing log critical caption description'
        );
        
        $this->assertTrue($result);
    }
    
    public function testLogError() {
        $result = $this->_logger->logError(
            'Testing log error caption', 'Testing log error caption description'
        );
        
        $this->assertTrue($result);
    }
    
    public function testLogWarning() {
        $result = $this->_logger->logWarning(
            'Testing log warning caption', 'Testing log warning caption description'
        );
        
        $this->assertTrue($result);
    }
    
    public function testLogNotice() {
        $result = $this->_logger->logNotice(
            'Testing log notice caption', 'Testing log notice caption description'
        );
        
        $this->assertTrue($result);
    }
    
    public function testModelSite() {
        $logger = Factory::instance()->createModule('Logger');
        $notModelRequest = Factory::instance()->createModelSite();
        
        $this->assertFalse($logger->setModelRequest($notModelRequest));
        
        $this->assertTrue($logger->setModelRequest($this->_modelRequest));
    }
    
}
