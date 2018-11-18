<?php

declare(strict_types=1);

namespace preloader;

/**
 * Log errors and notices. Set handlers for php errors and uncaught exceptions.
 */
class Logger {
    
    /**
     * @var ModelSite|null $_modelSite Model site.
     */
    protected $_modelSite = null;
    
    /**
     * @var ModelRequest|null $_modelRequest Model request.
     */
    protected $_modelRequest = null;
    
    /**
     * Class constructor.
     */
    public function __construct() {
        
    }
    
    /**
     * Logs critical error.
     */
    public function logCritical(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
        return self::logExtended('createCritical', $message, $description, $data, $code, $file, $line);
    }
    
    /**
     * Logs error.
     */
    public function logError(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
        return self::logExtended('createError', $message, $description, $data, $code, $file, $line);
    }
    
    /**
     * Logs warning.
     */
    public function logWarning(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
        return self::logExtended('createWarning', $message, $description, $data, $code, $file, $line);
    }
    
    /**
     * Logs notice.
     */
    public function logNotice(
        string $message, string $description = null,
        array $data = null, int $code = null,
        string $file = null, int $line = null
    ): bool {
        return self::logExtended('createNotice', $message, $description, $data, $code, $file, $line);
    }
    
    /**
     * Set handlers for php errors and uncaught exceptions.
     */
    public function startErrorsLogging(): void {
        set_error_handler(function($code, $message, $file, $line, $context) {
            var_export(array($code, $message, $file, $line));
            $this->logError('Error catched', $message, $context, $code, $file, $line);
        });
        
        set_exception_handler(function($exception) {
            var_export($exception);
            $this->logError(
                'Exception catched', $exception->getMessage(),
                $exception->getTrace(), $exception->getCode(),
                $exception->getFile(), $exception->getLine()
            );
        });
    }
    
    /**
     * Save log.
     */
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
    
    /**
     * Gets site model.
     */
    protected function getModelSite(): ModelSite {
        return $this->_modelSite;
    }
    
    /**
     * Sets site model.
     */
    public function setModelSite(ModelSite $modelSite): bool {
        $result = false;
        
        if (is_object($modelSite) && $modelSite instanceof ModelSite) {
            $this->_modelSite = $modelSite;
            $result = true;
        }
        
        return $result;
    }
    
    /**
     * Gets request model.
     */
    protected function getModelRequest(): ModelRequest {
        return $this->_modelRequest;
    }
    
    /**
     * Sets request model.
     */
    public function setModelRequest(ModelRequest $modelRequest): bool {
        $result = false;
        
        if (is_object($modelRequest) && $modelRequest instanceof ModelRequest) {
            $this->_modelRequest = $modelRequest;
            $result = true;
        }
        
        return $result;
    }
    
}
