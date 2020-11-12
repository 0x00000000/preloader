<?php

declare(strict_types=1);

namespace PreloaderTest\Module\Checker;

use PHPUnit\Framework\TestCase;

use Preloader\Module\Checker\Checker;
use Preloader\Module\Config\Config;
use Preloader\Module\Factory\Factory;
use Preloader\Model\ModelRequest;

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
    
    protected function createBlankRequest(): ModelRequest {
        $modelRequest = Factory::instance()->createModelRequest($this->_modelSite);
        $modelRequest->url = '/';
        $modelRequest->ip = '127.0.0.1';
        $modelRequest->userAgent = 'Test UA';
        $modelRequest->setGet(array());
        $modelRequest->setPost(array());
        $modelRequest->setSession(array());
        $modelRequest->setHeaders(array());
        $modelRequest->setModelSite($this->_modelSite);
        
        return $modelRequest;
    }
    
    protected function createChecker(ModelRequest $modelRequest): Checker {
        $checker = Factory::instance()->createTypedModule('Checker');
        $checker->setModelRequest($modelRequest);
        $router = Factory::instance()->createRouter($modelRequest);
        $checker->setRouter($router);
        
        return $checker;
    }
    
    public function testCheckRequestByUrl(): void {
        $modelRequest = $this->createBlankRequest();
        $checker = $this->createChecker($modelRequest);
        
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->url = '/sec1/sec2/sec3/sec4/sec5/sec6/';
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->url = '/sec1/sec2/sec3/sec4/sec5/sec6/sec7/';
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->url = '/sec30chars01234567890123456789';
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->url = '/sec31chars012345678901234567890';
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->url = '//';
        $this->assertTrue($checker->checkRequest());
        
    }
    
    public function testCheckRequestByGet(): void {
        $modelRequest = $this->createBlankRequest();
        $checker = $this->createChecker($modelRequest);
        
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->setGet(array('a' => '1', 'b' => '2', 'c' => '3'));
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->setGet(array('a' => '1', 'b' => '2', 'c' => '3', 'd' => '4'));
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->setGet(array('key20chars0123456789' => '1'));
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->setGet(array('key21chars01234567890' => '1'));
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->setGet(array('key' => 'val20chars0123456789'));
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->setGet(array('key' => 'val21chars01234567890'));
        $this->assertFalse($checker->checkRequest());
        
    }
    
    public function testCheckRequestByPost(): void {
        $modelRequest = $this->createBlankRequest();
        $checker = $this->createChecker($modelRequest);
        
        $modelRequest->setPost(array('a' => '1', 'b' => '2', 'c' => '3'));
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->setPost(array('a' => '1', 'b' => '2', 'c' => '3', 'd' => '4'));
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->setPost(array('key20chars0123456789' => '1'));
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->setPost(array('key21chars01234567890' => '1'));
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->setPost(array('key' => 'val20chars0123456789'));
        $this->assertTrue($checker->checkRequest());
        
        $modelRequest->setPost(array('key' => 'val21chars01234567890'));
        $this->assertFalse($checker->checkRequest());
        
    }
    
    public function testCheckRequestAdminAccess(): void {
        $modelRequest = $this->createBlankRequest();
        $checker = $this->createChecker($modelRequest);
        
        $modelRequest->url = '/admin';
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->url = '/admin.jpg';
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->url = '/admin/test';
        $this->assertFalse($checker->checkRequest());
        
        $modelRequest->url = '/admin/test?test=1';
        $this->assertFalse($checker->checkRequest());
        
        $adminHeaderKey = Config::instance()->get('checker', 'admin_header_key');
        $adminHeaderValue = Config::instance()->get('checker', 'admin_header_value');
        $modelRequest->setHeaders(array($adminHeaderKey => $adminHeaderValue));
        
        $this->assertTrue($checker->checkRequest());
        
    }
    
    public function testGetCheckReports(): void {
        $modelRequest = $this->createBlankRequest();
        $checker = $this->createChecker($modelRequest);
        
        $modelRequest->url = '/sec31chars012345678901234567890/sec2/sec3/sec4/sec5/sec6/sec7/';
        $checker->checkRequest();
        
        $this->assertTrue(is_array($checker->getCheckReports()));
        
        $this->assertTrue(count($checker->getCheckReports()) === 2);
        
        $checker = $this->createChecker($modelRequest);
        
        $modelRequest->url = '/sec30chars012345678901234567890/sec2/sec3/sec4/sec5/sec6/';
        
        $this->assertTrue(is_array($checker->getCheckReports()));
        
        $this->assertTrue(count($checker->getCheckReports()) === 0);
        
    }
    
    public function testIsSuspiciousRequest() : void {
        $modelRequest = $this->createBlankRequest();
        $checker = $this->createChecker($modelRequest);
        
        $modelRequest->url = '/';
        $this->assertFalse($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/core/admin.php';
        $this->assertFalse($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/core/file-not-exists.php';
        $this->assertFalse($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/core/classes.php'; // File exists.
        $this->assertTrue($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/core/classes/backward/TPlugin.php'; // File exists.
        $this->assertTrue($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/ext/html.php'; // File exists.
        $this->assertTrue($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/directory-not-exists/';
        $this->assertFalse($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/directory-not-exists';
        $this->assertFalse($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/core'; // Directory exists.
        $this->assertTrue($checker->isSuspiciousRequest());
        
        $modelRequest->url = '/core/'; // Directory exists.
        $this->assertTrue($checker->isSuspiciousRequest());
        
    }
    
    public function testRouter(): void {
        $checker = Factory::instance()->createTypedModule('Checker');
        
        $this->assertTrue($checker->setRouter($this->_router));
        
    }
    
    public function testModelRequest(): void {
        $checker = Factory::instance()->createTypedModule('Checker');
        
        $this->assertTrue($checker->setModelRequest($this->_modelRequest));
    }
    
}
