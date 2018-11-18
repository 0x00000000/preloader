<?php

declare(strict_types=1);

namespace preloader;

/**
 * Abstract model class.
 */
abstract class ModelAbstract {
    
    /**
     * Loads object's data by id.
     */
    abstract public function loadById(string $id): bool;
    
    /**
     * Loads object's data by key.
     */
    abstract public function loadByKey(string $key, string $value): bool;
    
    /**
     * Saves object's data.
     */
    abstract public function save(): ?string;
    
    /**
     * Gets object's data as associative array.
     */
    abstract public function getDataAssoc(): array;
    
    /**
     * Sets database object.
     */
    abstract public function setDatabase(Database $database): bool;
    
}