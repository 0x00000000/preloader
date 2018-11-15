<?php

declare(strict_types=1);

namespace preloader;

abstract class DatabaseAbstract {
    
    abstract public function getById(string $table, string $id): ?array;
    
    abstract public function getByKey(string $table, string $key, string $value): ?array;
    
    abstract public function addRecord(string $table, array $data): ?string;
    
    abstract public function updateRecord(string $table, array $data, string $primaryKey = 'id'): ?string;
    
}