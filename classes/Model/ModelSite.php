<?php

declare(strict_types=1);

namespace preloader;

include_once('ModelDatabase.php');

/**
 * Model request.
 * Gets and saves site`s data.
 * 
 * @property string|null $id Log`s id.
 * @property string|null $url Url to site`s root.
 * @property string|null $name Site`s name.
 */
class ModelSite extends ModelDatabase {
    /**
     * Default url if $_SERVER['SERVER_NAME'] is not set.
     */
    public const UNKNOWN_SERVER_NAME = 'UNKNOWN_SERVER_NAME';
    
    protected $_id = null;
    protected $_url = null;
    protected $_name = null;
    
    /**
     * @var string $_table Name of database table.
     */
    protected $_table = 'site';
    
    /**
     * Class constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Sets current site information to this object.
     */
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
    
    /**
     * Load site from database by site url if it is possible.
     */
    public function loadByUrl(string $name): bool {
        return $this->loadByKey('url', $name);
    }
    
}