<?php

declare(strict_types=1);

namespace Preloader\Module\Config;

/**
 * Stores configuration data for other modules.
 */
class ConfigEresus extends ConfigBase {
    
    /**
     * @var array $_data Stores configuration data.
     */
    protected $_data = array(
        'application' => array(
            'log_all_requests' => false,
            'session_name' => 'sid',
            'session_start' => true,
        ),
        'database' => array(
            'server' => 'localhost',
            'login' => 'mysql',
            'password' => '',
            'name' => 'saitik',
            'prefix' => 'saitik_preloader_'
        ),
        'checker' => array(
            'admin_header_key' => 'Preloader-Local',
            'admin_header_value' => 'local',
            'rules' => array(
                'url' => array(
                    'maxPartsCount' => 6,
                    'maxPartLength' => 30,
                ),
                'get' => array(
                    'maxParamsCount' => 3,
                    'maxKeyLength' => 20,
                    'maxValueLength' => 20,
                ),
                'post' => array(
                    'maxParamsCount' => 3,
                    'maxKeyLength' => 20,
                    'maxValueLength' => 20,
                ),
            ),
            'allowedScripts' => array(
                '/core/admin.php',
                '/core/ajax.php',
                '/core/client.php',
            ),
        ),
        'router' => array(
            'levelsToSiteRoot' => 2,
        ),
    );
    
}
