<?php

declare(strict_types=1);

namespace PreloaderTest;

use Preloader\Module\Factory\Factory;

include_once(dirname(__FILE__) . '/../classes/System/Core.php');

class init {
    
    public static function init(): void {
        $testMode = true;
        \Preloader\System\Core::setApplicationType('Eresus', $testMode);
        
        $application = Factory::instance()->createApplication();
        
        $application->run();
    }
    
}

init::init();
