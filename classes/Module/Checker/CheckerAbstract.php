<?php

declare(strict_types=1);

namespace preloader;

Core::loadModule('Router');

/**
 * Checks request.
 */
abstract class CheckerAbstract {
    
    /**
     * Checks if request is allowed.
     */
    abstract public function checkRequest(): bool;
    
    /**
     * Gets information after checking request.
     */
    abstract public function getCheckReports(): array;
    
    /**
     * Checks if request is suspiccious.
     */
    abstract public function isSuspiciousRequest(): bool;
    
    /**
     * Sets router.
     */
    abstract public function setRouter(Router $router): bool;
    
    /**
     * Sets request model.
     */
    abstract public function setModelRequest(ModelRequest $modelRequest): bool;
    
}
