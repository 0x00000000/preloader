<?php

namespace preloader;

include_once('System/Core.php');

include_once('MainAbstract.php');

class MainJoomla extends MainAbstract {
    
    private $_application = null;
    
    public function __construct() {
    }
    
    public function run() {
        
        Core::setApplicationType('Joomla');
        
        $this->_application = Factory::instance()->createTypedModule('Application');
        
        $this->_application->run();
        
    }
    
}
