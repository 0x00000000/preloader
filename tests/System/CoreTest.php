<?php

declare(strict_types=1);

namespace PreloaderTest\System;

use PHPUnit\Framework\TestCase;

use Preloader\System\Core;

include_once(dirname(__FILE__) . '/../init.php');

final class CoreTest extends TestCase {
    
    public function testGetApplicationType(): void {
        $this->assertEquals(Core::getApplicationType(), 'Eresus');
    }
    
    public function testGetNamespacePrefix(): void {
        $this->assertEquals(Core::getNamespacePrefix(), 'Preloader\\');
    }
    
}
