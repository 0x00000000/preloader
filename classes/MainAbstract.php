<?php

declare(strict_types=1);

namespace Preloader;

/**
 * Runs preloader script.
 */
abstract class MainAbstract {
    
    abstract public function run(): void;
    
}