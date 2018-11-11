<?php

namespace preloader;

include_once('ModelDatabase.php');

class ModelSite extends ModelDatabase {
    protected $_id = null;
    protected $_url = null;
    protected $_name = null;
    
    protected $_table = 'site';
    
    const UNKNOWN_SERVER_NAME = 'UNKNOWN_SERVER_NAME';
    
    public function __construct() {
        parent::__construct();
        
        if (array_key_exists('SERVER_NAME', $_SERVER)) {
            $url = preg_replace('/^www\./i', '', $_SERVER['SERVER_NAME']);
        } else {
            $url = self::UNKNOWN_SERVER_NAME;
        }
        $found = $this->getByUrl($url);
        if (! $found) {
            $this->url = $url;
            $this->name = $url;
            $this->save();
        }
    }
    
    public function getByUrl($name) {
        return $this->getByKey('url', $name);
    }
    
}