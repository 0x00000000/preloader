<?php

declare(strict_types=1);

namespace Preloader\Module\Database;

/**
 * Allow get and save data from/in database.
 */
abstract class DatabaseAbstract {
    
    /**
     * Gets data by id.
     */
    abstract public function getById(string $table, string $id): ?array;
    
    /**
     * Gets data by key.
     */
    abstract public function getByKey(string $table, string $key, string $value): ?array;
    
    /**
     * Saves the record in the database.
     */
    abstract public function addRecord(string $table, array $data): ?string;
    
    /**
     * Updates the record in the database.
     */
    abstract public function updateRecord(string $table, array $data, string $primaryKey = 'id'): ?string;
    
}