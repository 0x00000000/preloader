<?php

declare(strict_types=1);

namespace preloader;

include_once('System/Core.php');

include_once('MainAbstract.php');

/**
 * Runs preloader script.
 */
class MainJoomla extends MainAbstract {
    
    private $_application = null;
    
    /**
     * Runs preloader script.
     */
    public function run(): void {
        
        Core::setApplicationType('Joomla');
        
        $this->_application = Factory::instance()->createApplication();
        
        $this->_application->run();
        
    }
    
}
