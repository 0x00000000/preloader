<?php

declare(strict_types=1);

namespace preloader;

include_once('Application.php');

/**
 * Facade for other modules.
 * Checks request and routes UA.
 */
abstract class ApplicationBase extends Application {
    
    protected $_router = null;
    
    protected $_checker = null;
    
    protected $_logger = null;
    
    protected $_modelRequest = null;
    
    /**
     * Class's constructor.
     */
    public function __construct() {
        
        error_reporting(E_ALL);
        
        $this->initSession();
        
        $modelSite = Factory::instance()->createModelSite();
        $modelSite->create();
        
        $this->_modelRequest = Factory::instance()->createModelRequest($modelSite);
        $this->_modelRequest->create();
        
        $this->_logger = Factory::instance()->createLogger($modelSite, $this->_modelRequest);
        $this->_logger->startErrorsLogging();
        
        $this->_router = Factory::instance()->createRouter($this->_modelRequest);
        
        $this->_checker = Factory::instance()->createChecker($this->_modelRequest, $this->_router);
        
        error_reporting(E_ALL);
    }
    
    /**
     * Checks request and routes UA.
     */
    public function run(): void {
        
        $this->saveRequestIfNeeded();
        
        if (! $this->_checker->checkRequest()) {
            $this->saveLog();
            Core::FatalError();
        }
        
        $this->_router->route();
        
    }
    
    /**
     * Save request if it is needed.
     */
    protected function saveRequestIfNeeded(): void {
        $needToSave = false;
        
        if (Config::instance()->get('application', 'log_all_requests')) {
            $needToSave = true;
        } else {
            if ($this->_checker->isSuspiciousRequest()) {
                $needToSave = true;
            }
        }
        
        if ($needToSave) {
            $this->_modelRequest->save();
        }
    }
    
    /**
     * Save log.
     */
    protected function saveLog(): void {
        $description = '';
        $reports = $this->_checker->getCheckReports();
        if (is_array($reports) and count($reports)) {
            $description = implode("\n", $reports);
        }
        $this->_logger->logNotice(
            'Request was blocked', $description,
            null, null, __FILE__, __LINE__
        );
    }
    
    /**
     * Initialise sassion.
     */
    protected function initSession(): void {
        
        if (session_status() === PHP_SESSION_NONE) {
            $session_name = Config::instance()->get('application', 'session_name');
            if ($session_name) {
                session_name($session_name);
            }
            
            if (Config::instance()->get('application', 'session_start')) {
                session_start();
            }
        }
        
    }
    
}
