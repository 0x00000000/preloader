<?php

namespace preloader;

include_once('ModelDatabase.php');

class ModelLog extends ModelDatabase {
    const LEVEL_CRITICAL = 1;
    const LEVEL_ERROR = 2;
    const LEVEL_WARNING = 4;
    const LEVEL_NOTICE = 8;
    
    protected $_id = null;
    protected $_siteId = null;
    protected $_requestId = null;
    protected $_level = null;
    protected $_message = null;
    protected $_description = null;
    protected $_data = null;
    protected $_code = null;
    protected $_file = null;
    protected $_line = null;
    protected $_url = null;
    
    protected $_table = 'log';
    
    protected $_modelSite = null;
    
    protected $_modelRequest = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->addHiddenProperty('_modelSite');
        $this->addHiddenProperty('_modelRequest');
    }
    
    public function create(
        $level, $message, $description = null, $data = null, $code = null,
        $file = null, $line = null, $url = null
    ) {
        $result = false;
        
        if ($this->getModelRequest()) {
            if (! $this->getModelRequest()->id) {
                $this->getModelRequest()->save();
            }
        }
        
        if ($this->checkLevel($level) && strlen($message)) {
            if ($this->getModelSite()) {
                $this->siteId = $this->getModelSite()->id;
            } else {
                $this->siteId = null;
            }
            
            if ($this->getModelRequest()) {
                $this->requestId = $this->getModelRequest()->id;
            } else {
                $this->requestId = null;
            }
            
            $this->level = $level;
            $this->message = $message;
            $this->description = $description;
            $this->data = $data;
            $this->code = $code;
            $this->file = $file;
            $this->line = $line;
            $this->url = $url;
            
            $result = true;
        }
        
        return $result;
    }
    
    public function createCritical(
        $message, $description = null, $data = null, $code = null,
        $file = null, $line = null, $url = null
    ) {
        if ($code === null) {
            $code = E_USER_ERROR;
        }
        return $this->create(self::LEVEL_CRITICAL, $message, $description, $data, $code, $file, $line, $url);
    }
    
    public function createError(
        $message, $description = null, $data = null, $code = null,
        $file = null, $line = null, $url = null
    ) {
        if ($code === null) {
            $code = E_USER_ERROR;
        }
        return $this->create(self::LEVEL_ERROR, $message, $description, $data, $code, $file, $line, $url);
    }
    
    public function createWarning(
        $message, $description = null, $data = null, $code = null,
        $file = null, $line = null, $url = null
    ) {
        if ($code === null) {
            $code = E_USER_WARNING;
        }
        return $this->create(self::LEVEL_WARNING, $message, $description, $data, $code, $file, $line, $url);
    }
    
    public function createNotice(
        $message, $description = null, $data = null, $code = null,
        $file = null, $line = null, $url = null
    ) {
       if ($code === null) {
            $code = E_USER_NOTICE ;
        }
        return $this->create(self::LEVEL_NOTICE, $message, $description, $data, $code, $file, $line, $url);
    }
    
    protected function checkLevel($level) {
        $result = false;
        
        if ($level) {
            if (
                $level === self::LEVEL_CRITICAL
                || $level === self::LEVEL_ERROR
                || $level === self::LEVEL_WARNING
                || $level === self::LEVEL_NOTICE
            ) {
                $result = true;
            }
        }
        
        return $result;
    }
    
    protected function setMessage($value) {
        $maxMessageLength = 255;
        
        if ($value) {
            if (strlen($value) > $maxMessageLength) {
                $value = substr($value, 0, $maxMessageLength);
            }
        }
        
        $this->_message = $value;
    }
    
    protected function setUrl($value) {
        $maxUrlLength = 255;
        
        if ($value) {
            if (strlen($value) > $maxUrlLength) {
                $value = substr($value, 0, $maxUrlLength);
            }
        }
        
        $this->_url = $value;
    }
    
    public function getData() {
        return $this->_data ? json_decode($this->_data, true) : null;
    }
    
    public function setData($value) {
        $this->_data = json_encode($value);
    }
    
    protected function getModelSite() {
        return $this->_modelSite;
    }
    
    public function setModelSite($modelSite) {
        $result = false;
        if (is_object($modelSite) && $modelSite instanceof ModelSite) {
            $this->_modelSite = $modelSite;
            $result = true;
        }
        
        return $result;
    }
    
    protected function getModelRequest() {
        return $this->_modelRequest;
    }
    
    public function setModelRequest($modelRequest) {
        $result = false;
        if (is_object($modelRequest) && $modelRequest instanceof ModelRequest) {
            $this->_modelRequest = $modelRequest;
            $result = true;
        }
        
        return $result;
    }
    
}