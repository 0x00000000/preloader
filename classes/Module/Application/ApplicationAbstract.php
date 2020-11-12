<?php

declare(strict_types=1);

namespace Preloader\Module\Application;

/**
 * Facade for other modules.
 * Checks request and routes UA.
 */
abstract class ApplicationAbstract {
    
    /**
     * Checks request and routes UA.
     */
    abstract public function run(): void;
    
}