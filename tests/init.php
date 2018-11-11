<?php

namespace preloader;

include_once(dirname(__FILE__) . '/../classes/System/Core.php');

class init {
    
    public static function init() {
        Core::setApplicationType('Eresus');

        $application = Factory::instance()->createTypedModule('Application');

        $application->runForTest();
    }
    
}

init::init();
