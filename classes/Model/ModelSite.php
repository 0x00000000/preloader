<?php

namespace preloader;

include_once('ModelDatabase.php');

class ModelSite extends ModelDatabase {
    protected $_id = null;
    protected $_url = null;
    protected $_name = null;
    
    protected $_table = 'site';
    
    public function __construct() {
        parent::__construct();
        
        $url = preg_replace('/^www\./i', '', $_SERVER['SERVER_NAME']);
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