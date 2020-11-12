<?php

declare(strict_types=1);

namespace Preloader;

use Preloader\System\Core;
use Preloader\Module\Factory\Factory;

include_once(dirname(__FILE__) . '/System/Core.php');
include_once(dirname(__FILE__) . '/MainAbstract.php');

/**
 * Runs preloader script.
 */
class MainEresus extends MainAbstract {
    
    /**
     * Runs preloader script.
     */
    public function run(): void {
        
        Core::setApplicationType('Eresus');
        
        $application = Factory::instance()->createApplication();
        
        $application->run();
        
    }
    
}
