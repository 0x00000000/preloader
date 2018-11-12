<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../init.php');

Core::loadModel('ModelLog');

final class ModelLogTest extends TestCase {
    protected $_modelLog = null;
    protected $_modelSite = null;
    protected $_modelRequest = null;
    protected $_logData = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->_modelSite = Factory::instance()->createModelSite();
        
        $this->_modelRequest = Factory::instance()->createModelRequest($this->_modelSite);
        $this->_modelRequest->create();
        
        $this->_modelLog = Factory::instance()->createModelLog($this->_modelSite, $this->_modelRequest);
        
        $this->_logData = $this->getTestData();
    }
    
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
        $this->_modelLog->create(
            $this->_logData['level'], $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            $this->_logData['code'], $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $data = array();
        $this->_modelLog->setData($data);
        $testData = $this->_modelLog->getData();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true);
        $this->_modelLog->setData($data);
        $testData = $this->_modelLog->getData();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $this->_modelLog->setData($data);
        $testData = $this->_modelLog->getData();
        $this->assertEquals($data, $testData);
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $this->_modelLog->setData($data);
        $testData = $this->_modelLog->getData();
        $this->assertEquals($data, $testData);
        
    }
        
    public function testCreate() {
        $this->_modelLog->create(
            $this->_logData['level'], $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            $this->_logData['code'], $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, $this->_logData['level']);
        $this->assertEquals($this->_modelLog->message, $this->_logData['message']);
        $this->assertEquals($this->_modelLog->description, $this->_logData['description']);
        $this->assertEquals($this->_modelLog->data, $this->_logData['data']);
        $this->assertEquals($this->_modelLog->code, $this->_logData['code']);
        $this->assertEquals($this->_modelLog->file, $this->_logData['file']);
        $this->assertEquals($this->_modelLog->line, $this->_logData['line']);
        $this->assertEquals($this->_modelLog->url, $this->_logData['url']);
        
    }
    
    public function testCritical() {
        $this->_modelLog->createCritical(
            $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            $this->_logData['code'], $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, ModelLog::LEVEL_CRITICAL);
        
        $this->_modelLog->createCritical(
            $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            null, $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, ModelLog::LEVEL_CRITICAL);
        $this->assertEquals($this->_modelLog->code, E_USER_ERROR);
    }
    
    public function testError() {
        $this->_modelLog->createError(
            $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            $this->_logData['code'], $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, ModelLog::LEVEL_ERROR);
        
        $this->_modelLog->createError(
            $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            null, $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, ModelLog::LEVEL_ERROR);
        $this->assertEquals($this->_modelLog->code, E_USER_ERROR);
    }
    
    public function testWarning() {
        $this->_modelLog->createWarning(
            $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            $this->_logData['code'], $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, ModelLog::LEVEL_WARNING);
        
        $this->_modelLog->createWarning(
            $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            null, $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, ModelLog::LEVEL_WARNING);
        $this->assertEquals($this->_modelLog->code, E_USER_WARNING);
    }
    
    public function testNotice() {
        $this->_modelLog->createNotice(
            $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            $this->_logData['code'], $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, ModelLog::LEVEL_NOTICE);
        
        $this->_modelLog->createNotice(
            $this->_logData['message'], $this->_logData['description'],
            $this->_logData['data'],
            null, $this->_logData['file'], $this->_logData['line'], $this->_logData['url']
        );
        
        $this->assertEquals($this->_modelLog->level, ModelLog::LEVEL_NOTICE);
        $this->assertEquals($this->_modelLog->code, E_USER_NOTICE);
    }
    
    public function testModelSite() {
        $modelLog = Factory::instance()->createModel('ModelLog');
        $notModelSite = Factory::instance()->createModel('ModelLog');
        
        $this->assertFalse($modelLog->setModelSite($notModelSite));
        
        $this->assertTrue($modelLog->setModelSite($this->_modelSite));
        
    }
    
    public function testModelRequest() {
        $modelLog = Factory::instance()->createModel('ModelLog');
        $notModelRequest = Factory::instance()->createModel('ModelLog');
        
        $this->assertFalse($modelLog->setModelRequest($notModelRequest));
        
        $this->assertTrue($modelLog->setModelRequest($this->_modelRequest));
        
    }
}
