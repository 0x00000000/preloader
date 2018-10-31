<?php

namespace preloader;

include_once('ConfigBase.php');

class ConfigEresus extends ConfigBase {
    
    protected $_data = array(
        'application' => array(
            'log_all_requests' => false,
            'session_name' => 'sid',
            'session_start' => true,
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
