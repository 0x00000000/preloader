<?php

declare(strict_types=1);

namespace Preloader\Module\Application;

use Preloader\System\Core;

/**
 * Facade for other modules.
 * Checks request and routes UA.
 */
class ApplicationTest extends ApplicationBase {
    
    /**
     * Checks request and doesn't route UA.
     */
    public function run(): void {
        
        $this->saveRequestIfNeeded();
        
        if (! $this->_checker->checkRequest()) {
            $this->saveLog();
            Core::FatalError();
        }
        
    }
    
}
