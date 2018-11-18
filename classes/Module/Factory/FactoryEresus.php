<?php

declare(strict_types=1);

namespace preloader;

include_once('FactoryBase.php');

/**
 * Creates modules and models.
 */
class FactoryEresus extends FactoryBase {
    
    /**
     * @var string $_moduleNamePostfix Postfix for some modules' names.
     */
    protected $_moduleNamePostfix = 'Eresus';
    
    /**
     * Class constructor.
     */
    public function __construct() {
    }
    
}
