<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../../init.php');

final class LoggerTest extends TestCase {
    
    public function testLogCritical() {
        $logger = Factory::instance()->createModule('Logger');
        
        $result = $logger->logCritical(
            'Testing log critical caption', 'Testing log critical caption description'
        );
        
        $this->assertTrue($result);
    }
    
    public function testLogError() {
        $logger = Factory::instance()->createModule('Logger');
        
        $result = $logger->logError(
            'Testing log error caption', 'Testing log error caption description'
        );
        
        $this->assertTrue($result);
    }
    
    public function testLogWarning() {
        $logger = Factory::instance()->createModule('Logger');
        
        $result = $logger->logWarning(
            'Testing log warning caption', 'Testing log warning caption description'
        );
        
        $this->assertTrue($result);
    }
    
    public function testLogNotice() {
        $logger = Factory::instance()->createModule('Logger');
        
        $result = $logger->logNotice(
            'Testing log notice caption', 'Testing log notice caption description'
        );
        
        $this->assertTrue($result);
    }
    
}
