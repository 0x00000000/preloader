<?php

declare(strict_types=1);

namespace preloader;

include_once('System/Core.php');

include_once('MainAbstract.php');

class MainEresus extends MainAbstract {
    
    private $_application = null;
    
    public function run(): void {
        
        Core::setApplicationType('Eresus');
        
        $this->_application = Factory::instance()->createApplication();
        
        $this->_application->run();
        
    }
    
}
