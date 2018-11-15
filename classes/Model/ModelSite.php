<?php

declare(strict_types=1);

namespace preloader;

include_once('ModelDatabase.php');

class ModelSite extends ModelDatabase {
    public const UNKNOWN_SERVER_NAME = 'UNKNOWN_SERVER_NAME';
    
    protected $_id = null;
    protected $_url = null;
    protected $_name = null;
    
    protected $_table = 'site';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create(): void {
        if (array_key_exists('SERVER_NAME', $_SERVER)) {
            $url = preg_replace('/^www\./i', '', $_SERVER['SERVER_NAME']);
        } else {
            $url = self::UNKNOWN_SERVER_NAME;
        }
        $found = $this->loadByUrl($url);
        if (! $found) {
            $this->url = $url;
            $this->name = $url;
            $this->save();
        }
    }
    
    public function loadByUrl(string $name): bool {
        return $this->loadByKey('url', $name);
    }
    
}