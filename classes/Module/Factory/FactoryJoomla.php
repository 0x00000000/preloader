<?php

declare(strict_types=1);

namespace preloader;

include_once('FactoryBase.php');

/**
 * Creates modules and models.
 */
class FactoryJoomla extends FactoryBase {
    
    /**
     * @var string $_moduleNamePostfix Postfix for some modules' names.
     */
    protected $_moduleNamePostfix = 'Joomla';
    
    /**
     * Class constructor.
     */
    public function __construct() {
    }
    
}
