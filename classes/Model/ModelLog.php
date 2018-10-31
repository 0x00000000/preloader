<?php

namespace preloader;

include_once('ModelDatabase.php');

class ModelLog extends ModelDatabase {
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
    
    const LEVEL_CRITICAL = 1;
    const LEVEL_ERROR = 2;
    const LEVEL_WARNING = 4;
    const LEVEL_NOTICE = 8;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function create(
        $level, $message, $description = null, $data = null, $code = null,
        $file = null, $line = null, $url = null
    ) {
        $result = false;
        
        $request = Registry::get('request');
        if (! $request->id) {
            $request->save();
        }
        
        if ($this->checkLevel($level) && strlen($message)) {
            $this->siteId = Registry::get('site')->id;
            $this->requestId = $request->id;
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
    
    public function createCritical(
        $message, $description = null, $dat = null, $code = null,
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
    
}