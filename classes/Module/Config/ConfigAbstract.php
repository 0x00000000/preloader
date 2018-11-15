<?php

declare(strict_types=1);

namespace preloader;

abstract class ConfigAbstract {
    
    abstract public function get(string $section, string $name = null);
    
    abstract public function add(string $section, string $name, $value): bool;
    
}