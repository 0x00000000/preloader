<?php

declare(strict_types=1);

namespace preloader;

use PHPUnit\Framework\TestCase;

include_once(dirname(__FILE__) . '/../../init.php');

final class ConfigTest extends TestCase {
    
    public function testGet(): void {
        $config = Factory::instance()->createConfig();
        
        $this->assertTrue(is_array($config->get('database')));
        $this->assertTrue(count($config->get('database')) >= 5);
        $this->assertTrue(is_string($config->get('database', 'name')));
    }
    
    public function testSet(): void {
        $config = Factory::instance()->createConfig();
        
        $stringValue = 'Test value';
        $arrayValue = array('testKey' => 'Test value');
        
        $config->add('database', 'testDatabaseParam', $stringValue);
        $this->assertEquals($config->get('database', 'testDatabaseParam'), $stringValue);
        
        $config->add('database', 'testDatabaseParam', $arrayValue);
        $this->assertEquals(json_encode($config->get('database', 'testDatabaseParam')), json_encode($arrayValue));
        
        $config->add('testSection', 'testSectionParam', $stringValue);
        $this->assertEquals($config->get('testSection', 'testSectionParam'), $stringValue);
        
        $config->add('testSection', 'testSectionParam', $arrayValue);
        $this->assertEquals(json_encode($config->get('testSection', 'testSectionParam')), json_encode($arrayValue));
        
    }
    
}
