<?php

namespace preloader;

include_once('FactoryBase.php');

class FactoryJoomla extends FactoryBase {
    
    protected $_moduleNamePostfix = 'Joomla';
    
    public function __construct() {
    }
    
}
