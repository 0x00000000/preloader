<?php

declare(strict_types=1);

namespace preloader;

include_once('FactoryBase.php');

class FactoryEresus extends FactoryBase {
    
    protected $_moduleNamePostfix = 'Eresus';
    
    public function __construct() {
    }
    
}
