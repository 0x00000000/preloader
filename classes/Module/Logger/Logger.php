<?php

namespace preloader;

class Logger {
    protected $_modelSite = null;
    
    protected $_modelRequest = null;
    
    public function __construct() {
        
    }
    
    public function logCritical(
        $message, $description = null, $data = null, $code = null,
        $file = null, $line = null
    ) {
        return self::logExtended('createCritical', $message, $description, $data, $code, $file, $line);
    }
    
    public function logError(
        $message, $description = null, $data = null, $code = null,
        $file = null, $line = null
    ) {
        return self::logExtended('createError', $message, $description, $data, $code, $file, $line);
    }
    
    public function logWarning(
        $message, $description = null, $data = null, $code = null,
        $file = null, $line = null
    ) {
        return self::logExtended('createWarning', $message, $description, $data, $code, $file, $line);
    }
    
    public function logNotice(
        $message, $description = null, $data = null, $code = null,
        $file = null, $line = null
    ) {
        return self::logExtended('createNotice', $message, $description, $data, $code, $file, $line);
    }
    
    public function startErrorsLogging() {
        set_error_handler(function($code, $message, $file, $line, $context) {
            var_export(array($code, $message, $file, $line));
            $this->logError('Error catched', $message, json_encode($context), $code, $file, $line);
        });
        
        set_exception_handler(function($exception) {
            var_export($exception);
            $this->logError(
                'Exception catched', $exception->getMessage(),
                json_encode($exception->getTraceAsString()), $exception->getCode(),
                $exception->getFile(), $exception->getLine()
            );
        });
    }
    
    protected function logExtended(
        $methodName, $message, $description = null, $data = null, $code = null,
        $file = null, $line = null
    ) {
        $result = false;
        $modelLog = Factory::instance()->createModelLog($this->getModelSite(), $this->getModelRequest());
        
        if (method_exists($modelLog, $methodName)) {
            if ($this->getModelRequest()) {
                $url = $this->getModelRequest()->url;
            } else {
                $url = null;
            }
            
            $result = $modelLog->$methodName($message, $description, $data, $code, $file, $line, $url);
            if ($result) {
                $modelLog->save();
            }
        }
        
        return $result;
    }
    
    public function getModelSite() {
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
