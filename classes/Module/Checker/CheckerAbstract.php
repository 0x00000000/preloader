<?php

declare(strict_types=1);

namespace preloader;

Core::loadModule('Router');

abstract class CheckerAbstract {
    
    abstract public function checkRequest(): bool;
    
    abstract public function getCheckReports(): array;
    
    abstract public function isSuspiciousRequest(): bool;
    
    abstract public function setRouter(Router $router): bool;
    
    abstract public function setModelRequest(ModelRequest $modelRequest): bool;
    
}
