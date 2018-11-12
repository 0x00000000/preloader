<?php

namespace preloader;

include_once(dirname(__FILE__) . '/../classes/System/Core.php');

class init {
    
    public static function init() {
        Core::setApplicationType('Eresus');

        $application = Factory::instance()->createApplication();

        $application->runForTest();
    }
    
}

init::init();
