<?php

namespace preloader;

abstract class ConfigAbstract {
    
    abstract public function get($section, $name = null);
    
    abstract public function add($section, $name, $value);
    
}