<?php

declare(strict_types=1);

namespace preloader;

abstract class RouterAbstract {
    
    public const REQUEST_TYPE_ADMIN = 'admin';
    public const REQUEST_TYPE_AJAX = 'ajax';
    public const REQUEST_TYPE_CLIENT = 'client';
    
    abstract public function getRequestType(): string;
    
    abstract public function route(): void;
    
    abstract public function getSiteRoot(): string;
    
    abstract public function setModelRequest(ModelRequest $modelRequest): bool;
    
}