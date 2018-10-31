<?php

namespace preloader;

class Logger {
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
    
    public function logExtended(
        $methodName, $message, $description = null, $data = null, $code = null,
        $file = null, $line = null
    ) {
        $log = Factory::instance()->createModel('ModelLog');
        $request = Registry::get('request');
        $result = $log->$methodName($message, $description, $data, $code, $file, $line, $request->url);
        if ($result) {
            $log->save();
            $log->save();
        }
        
        return $result;
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
}
