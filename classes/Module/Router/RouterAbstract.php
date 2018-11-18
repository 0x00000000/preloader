<?php

declare(strict_types=1);

namespace preloader;

/**
 * Calculates request type and if needed routes UA.
 */
abstract class RouterAbstract {
    
    /**
     * Request's types.
     */
    public const REQUEST_TYPE_ADMIN = 'admin';
    public const REQUEST_TYPE_AJAX = 'ajax';
    public const REQUEST_TYPE_CLIENT = 'client';
    
    /**
     * Gets request type.
     */
    abstract public function getRequestType(): string;
    
    /**
     * Routes UA.
     */
    abstract public function route(): void;
    
    /**
     * Gets site root path.
     */
    abstract public function getSiteRoot(): string;
    
    /**
     * Sets request model.
     */
    abstract public function setModelRequest(ModelRequest $modelRequest): bool;
    
}