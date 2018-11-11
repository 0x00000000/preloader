<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../init.php');

Core::loadModel('ModelRequest');

final class ModelRequestTest extends TestCase {
    
    public function testGet(): void {
        $request = Factory::instance()->createModel('ModelRequest');
        
        $data = array();
        $request->setGet($data);
        $testData = $request->getGet();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true);
        $request->setGet($data);
        $testData = $request->getGet();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $request->setGet($data);
        $testData = $request->getGet();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $request->setGet($data);
        $testData = $request->getGet();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
    }
    
    public function testPost(): void {
        $request = Factory::instance()->createModel('ModelRequest');
        
        $data = array();
        $request->setPost($data);
        $testData = $request->getPost();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true);
        $request->setPost($data);
        $testData = $request->getPost();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $request->setPost($data);
        $testData = $request->getPost();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $request->setPost($data);
        $testData = $request->getPost();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
    }
    
    public function testSession(): void {
        $request = Factory::instance()->createModel('ModelRequest');
        
        $data = array();
        $request->setSession($data);
        $testData = $request->getSession();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true);
        $request->setSession($data);
        $testData = $request->getSession();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $request->setSession($data);
        $testData = $request->getSession();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $request->setSession($data);
        $testData = $request->getSession();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
    }
    
    public function testHeaders(): void {
        $request = Factory::instance()->createModel('ModelRequest');
        
        $data = array();
        $request->setHeaders($data);
        $testData = $request->getHeaders();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true);
        $request->setHeaders($data);
        $testData = $request->getHeaders();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('1', 'two', false, true, array(), array(0, '1', 2));
        $request->setHeaders($data);
        $testData = $request->getHeaders();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
        $data = array('a' => '1', 'b' => 'two', 'c' => false, 'd' => true, 'e' => array(), 'f' => array('x' => 0, 'y' => '1', 'z' => 2));
        $request->setHeaders($data);
        $testData = $request->getHeaders();
        $this->assertEquals(json_encode($data), json_encode($testData));
        
    }
    
}
