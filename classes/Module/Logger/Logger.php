<?php

declare(strict_types=1);

namespace preloader;

class Logger {
    protected $_modelSite = null;
    
    protected $_modelRequest = null;
    
    public function __construct() {
        
    }
    
    public function logCritical(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
        return self::logExtended('createCritical', $message, $description, $data, $code, $file, $line);
    }
    
    public function logError(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
        return self::logExtended('createError', $message, $description, $data, $code, $file, $line);
    }
    
    public function logWarning(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
        return self::logExtended('createWarning', $message, $description, $data, $code, $file, $line);
    }
    
    public function logNotice(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
        return self::logExtended('createNotice', $message, $description, $data, $code, $file, $line);
    }
    
    public function startErrorsLogging(): void {
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
        string $methodName, string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
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
    
    protected function getModelSite(): ModelSite {
        return $this->_modelSite;
    }
    
    public function setModelSite(ModelSite $modelSite): bool {
        $result = false;
        
        if (is_object($modelSite) && $modelSite instanceof ModelSite) {
            $this->_modelSite = $modelSite;
            $result = true;
        }
        
        return $result;
    }
    
    protected function getModelRequest(): ModelRequest {
        return $this->_modelRequest;
    }
    
    public function setModelRequest(ModelRequest $modelRequest): bool {
        $result = false;
        
        if (is_object($modelRequest) && $modelRequest instanceof ModelRequest) {
            $this->_modelRequest = $modelRequest;
            $result = true;
        }
        
        return $result;
    }
    
}
