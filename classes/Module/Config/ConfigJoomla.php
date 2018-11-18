<?php

declare(strict_types=1);

namespace preloader;

include_once('ConfigBase.php');

/**
 * Stores configuration data for other modules.
 */
class ConfigJoomla extends ConfigBase {
    
    /**
     * @var array $_data Stores configuration data.
     */
    protected $_data = array(
        'application' => array(
            'log_all_requests' => false,
            'session_name' => false,
            'session_start' => false,
        ),
        'database' => array(
            'server' => 'localhost',
            'login' => 'root',
            'password' => '',
            'name' => 'saitik_saitik',
            'prefix' => 'saitik_preloader_'
        ),
        'checker' => array(
            'admin_header_key' => 'Preloader_local',
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
                '/administrator/index.php',
                '/index.php',
            ),
        ),
        'router' => array(
            'levelsToSiteRoot' => 1,
        ),
    );
    
}
