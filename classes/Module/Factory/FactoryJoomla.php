<?php

declare(strict_types=1);

namespace Preloader\Module\Factory;

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
