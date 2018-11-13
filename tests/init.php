<?php

namespace preloader;

include_once(dirname(__FILE__) . '/../classes/System/Core.php');

class init {
    
    public static function init() {
        $testMode = true;
        Core::setApplicationType('Eresus', $testMode);
        
        $application = Factory::instance()->createApplication();
        
        $application->run();
    }
    
}

init::init();
