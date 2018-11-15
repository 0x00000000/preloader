<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../init.php');

Core::loadModel('ModelRequest');

final class ModelRequestTest extends TestCase {
    protected $_modelRequest = null;
    protected $_modelSite = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->_modelSite = Factory::instance()->createModelSite();
        $this->_modelSite->create();
        
        $this->_modelRequest = Factory::instance()->createModelRequest($this->_modelSite);
    }
    
    public function testCreate(): void {
        $id = $this->_modelRequest->create();
        
        $this->assertEquals($this->_modelRequest->url, ModelRequest::UNKNOWN_REQUEST_URI);
        $this->assertEquals($this->_modelRequest->siteId, $this->_modelSite->id);
    }
    
    public function testGet(): void {
        $data = array();
        $this->_modelRequest->setGet($data);
        $testData = $this->_modelRequest->getGet();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true);
        $this->_modelRequest->setGet($data);
        $testData = $this->_modelRequest->getGet();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $this->_modelRequest->setGet($data);
        $testData = $this->_modelRequest->getGet();
        $this->assertEquals($data, $testData);
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $this->_modelRequest->setGet($data);
        $testData = $this->_modelRequest->getGet();
        $this->assertEquals($data, $testData);
        
    }
    
    public function testPost(): void {
        $data = array();
        $this->_modelRequest->setPost($data);
        $testData = $this->_modelRequest->getPost();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true);
        $this->_modelRequest->setPost($data);
        $testData = $this->_modelRequest->getPost();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $this->_modelRequest->setPost($data);
        $testData = $this->_modelRequest->getPost();
        $this->assertEquals($data, $testData);
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $this->_modelRequest->setPost($data);
        $testData = $this->_modelRequest->getPost();
        $this->assertEquals($data, $testData);
        
    }
    
    public function testSession(): void {
        $data = array();
        $this->_modelRequest->setSession($data);
        $testData = $this->_modelRequest->getSession();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true);
        $this->_modelRequest->setSession($data);
        $testData = $this->_modelRequest->getSession();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $this->_modelRequest->setSession($data);
        $testData = $this->_modelRequest->getSession();
        $this->assertEquals($data, $testData);
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $this->_modelRequest->setSession($data);
        $testData = $this->_modelRequest->getSession();
        $this->assertEquals($data, $testData);
        
    }
    
    public function testHeaders(): void {
        $data = array();
        $this->_modelRequest->setHeaders($data);
        $testData = $this->_modelRequest->getHeaders();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true);
        $this->_modelRequest->setHeaders($data);
        $testData = $this->_modelRequest->getHeaders();
        $this->assertEquals($data, $testData);
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $this->_modelRequest->setHeaders($data);
        $testData = $this->_modelRequest->getHeaders();
        $this->assertEquals($data, $testData);
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $this->_modelRequest->setHeaders($data);
        $testData = $this->_modelRequest->getHeaders();
        $this->assertEquals($data, $testData);
        
    }
    
    public function testModelSite(): void {
        $modelRequest = Factory::instance()->createModel('ModelRequest');
        
        $this->assertTrue($modelRequest->setModelSite($this->_modelSite));
        
    }
    
    public function testDatabase(): void {
        $modelRequestSave = Factory::instance()->createModelRequest($this->_modelSite);
        $modelRequestSave->create();
        
        $idSave = $modelRequestSave->save();
        $this->assertTrue(boolval($idSave));
        
        $dataAfterSave = $modelRequestSave->getDataAssoc();
        
        $modelRequestGet = Factory::instance()->createModelRequest($this->_modelSite);
        $modelRequestGet->loadById($idSave);
        $dataAfterGet = $modelRequestGet->getDataAssoc();
        
        $this->assertEquals($dataAfterSave, $dataAfterGet);
        
        $modelRequestGet->info = 'Test info.';
        $idGet = $modelRequestGet->save();
        $dataAfterUpdated = $modelRequestGet->getDataAssoc();
        
        $modelRequestUpdatedGet = Factory::instance()->createModelRequest($this->_modelSite);
        $modelRequestUpdatedGet->loadById($idGet);
        
        $dataAfterUpdatedGet = $modelRequestUpdatedGet->getDataAssoc();
        
        $this->assertEquals($dataAfterUpdated, $dataAfterUpdatedGet);
    }
    
    public function testCostants(): void {
        $this->assertTrue(! empty(ModelRequest::UNKNOWN_REQUEST_URI));
    }
    
}
