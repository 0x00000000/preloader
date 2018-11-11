<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../init.php');

Core::loadModel('ModelLog');

final class ModelLogTest extends TestCase {
    
    private function getTestData() {
        return array(
            'level' => ModelLog::LEVEL_ERROR,
            'message' => 'Test message',
            'description' => 'Test description',
            'data' => array('test' => true),
            'code' => E_ERROR,
            'file' => 'log.php',
            'line' => 255,
            'url' => 'http://test.example.com/',
        );
    }
    
    public function testData() {
        $log = Factory::instance()->createModel('ModelLog');
                
        $logData = $this->getTestData();
        
        $log->create(
            $logData['level'], $logData['message'], $logData['description'],
            $logData['data'],
            $logData['code'], $logData['file'], $logData['line'], $logData['url']
        );
        
        $data = array();
        $log->setData($data);
        $testData = $log->getData();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true);
        $log->setData($data);
        $testData = $log->getData();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $log->setData($data);
        $testData = $log->getData();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $log->setData($data);
        $testData = $log->getData();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
    }
        
    public function testCreate() {
        $log = Factory::instance()->createModel('ModelLog');
                
        $logData = $this->getTestData();
        
        $log->create(
            $logData['level'], $logData['message'], $logData['description'],
            $logData['data'],
            $logData['code'], $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, $logData['level']);
        $this->assertEquals($log->message, $logData['message']);
        $this->assertEquals($log->description, $logData['description']);
        $this->assertEquals(json_encode($log->data), json_encode($logData['data']));
        $this->assertEquals($log->code, $logData['code']);
        $this->assertEquals($log->file, $logData['file']);
        $this->assertEquals($log->line, $logData['line']);
        $this->assertEquals($log->url, $logData['url']);
        
    }
    
    public function testCritical() {
        $log = Factory::instance()->createModel('ModelLog');
                
        $logData = $this->getTestData();
        
        $log->createCritical(
            $logData['message'], $logData['description'],
            $logData['data'],
            $logData['code'], $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, ModelLog::LEVEL_CRITICAL);
        
        $log->createCritical(
            $logData['message'], $logData['description'],
            $logData['data'],
            null, $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, ModelLog::LEVEL_CRITICAL);
        $this->assertEquals($log->code, E_USER_ERROR);
    }
    
    public function testError() {
        $log = Factory::instance()->createModel('ModelLog');
                
        $logData = $this->getTestData();
        
        $log->createError(
            $logData['message'], $logData['description'],
            $logData['data'],
            $logData['code'], $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, ModelLog::LEVEL_ERROR);
        
        $log->createError(
            $logData['message'], $logData['description'],
            $logData['data'],
            null, $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, ModelLog::LEVEL_ERROR);
        $this->assertEquals($log->code, E_USER_ERROR);
    }
    
    public function testWarning() {
        $log = Factory::instance()->createModel('ModelLog');
                
        $logData = $this->getTestData();
        
        $log->createWarning(
            $logData['message'], $logData['description'],
            $logData['data'],
            $logData['code'], $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, ModelLog::LEVEL_WARNING);
        
        $log->createWarning(
            $logData['message'], $logData['description'],
            $logData['data'],
            null, $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, ModelLog::LEVEL_WARNING);
        $this->assertEquals($log->code, E_USER_WARNING);
    }
    
    public function testNotice() {
        $log = Factory::instance()->createModel('ModelLog');
                
        $logData = $this->getTestData();
        
        $log->createNotice(
            $logData['message'], $logData['description'],
            $logData['data'],
            $logData['code'], $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, ModelLog::LEVEL_NOTICE);
        
        $log->createNotice(
            $logData['message'], $logData['description'],
            $logData['data'],
            null, $logData['file'], $logData['line'], $logData['url']
        );
        
        $this->assertEquals($log->level, ModelLog::LEVEL_NOTICE);
        $this->assertEquals($log->code, E_USER_NOTICE);
    }
    
}
