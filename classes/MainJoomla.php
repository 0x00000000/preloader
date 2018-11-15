<?php

declare(strict_types=1);

namespace preloader;

include_once('System/Core.php');

include_once('MainAbstract.php');

class MainJoomla extends MainAbstract {
    
    private $_application = null;
    
    public function run(): void {
        
        Core::setApplicationType('Joomla');
        
        $this->_application = Factory::instance()->createApplication();
        
        $this->_application->run();
        
    }
    
}
