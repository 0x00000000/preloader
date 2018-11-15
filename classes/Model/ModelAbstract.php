<?php

declare(strict_types=1);

namespace preloader;

abstract class ModelAbstract {
    
    abstract public function loadById(string $id): bool;
    
    abstract public function loadByKey(string $key, string $value): bool;
    
    abstract public function save(): ?string;
    
    abstract public function getDataAssoc(): array;
    
    abstract public function setDatabase(Database $database): bool;
    
}