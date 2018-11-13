<?php

namespace preloader;

include_once('ApplicationBase.php');

class ApplicationTest extends ApplicationBase {
    
    public function run() {
        
        $this->logRequest();
        
        if (! $this->_checker->checkRequest()) {
            $this->logUnacceptedRequest();
            Core::FatalError();
        }
        
    }
    
}
