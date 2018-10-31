<?php

namespace preloader;

include_once('ModelDatabase.php');

class ModelRequest extends ModelDatabase {
    protected $_id = null;
    protected $_siteId = null;
    protected $_url = null;
    protected $_get = null;
    protected $_post = null;
    protected $_session = null;
    protected $_headers = null;
    protected $_ip = null;
    protected $_userAgent = null;
    protected $_info = null;
    
    protected $_table = 'request';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create() {
        $this->_url = $_SERVER['REQUEST_URI'];
        $this->_siteId = Registry::get('site')->id;
        $this->_get = json_encode($_GET);
        $this->_post = json_encode($_POST);
        if (! empty($_SESSION)) {
            $this->_session = json_encode($_SESSION);
        }
        $this->_headers = json_encode(getallheaders());
        $this->_ip = $_SERVER['REMOTE_ADDR'];
        $this->_userAgent = $_SERVER['HTTP_USER_AGENT'];
    }
    
    public function getGet() {
        return $this->_get ? json_decode($this->_get, true) : null;
    }
    
    public function setGet($value) {
        $this->_get = json_encode($value);
    }
    
    public function getPost() {
        return $this->_post ? json_decode($this->_post, true) : null;
    }
    
    public function setPost($value) {
        $this->_post = json_encode($value);
    }
    
    public function getSession() {
        return $this->_session ? json_decode($this->_session, true) : null;
    }
    
    public function setSession($value) {
        $this->_session = json_encode($value);
    }
    
    public function getHeaders() {
        return $this->_headers ? json_decode($this->_headers, true) : null;
    }
    
    public function setHeaders($value) {
        $this->_headers = json_encode($value);
    }
    
}