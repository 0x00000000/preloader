<?php

namespace preloader;

include_once('System/Core.php');

include_once('MainAbstract.php');

class MainEresus extends MainAbstract {
    
    private $_application = null;
    
    public function __construct() {
    }
    
    public function run() {
        
        Core::setApplicationType('Eresus');
        
        $this->_application = Factory::instance()->createTypedModule('Application');
        
        $this->_application->run();
        
    }
    
}
