<?php

declare(strict_types=1);

namespace preloader;

include_once('ApplicationBase.php');

class ApplicationTest extends ApplicationBase {
    
    public function run(): void {
        
        $this->logRequest();
        
        if (! $this->_checker->checkRequest()) {
            $this->logUnacceptedRequest();
            Core::FatalError();
        }
        
    }
    
}
