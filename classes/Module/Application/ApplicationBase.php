<?php

namespace preloader;

include_once('Application.php');

abstract class ApplicationBase extends Application {
    
    protected $_router = null;
    
    protected $_checker = null;
    
    public function __construct() {
        
        error_reporting(E_ALL);
        
        $this->initSession();
        
        $site = Factory::instance()->createModel('ModelSite');
        Registry::set('site', $site);
        
        $request = Factory::instance()->createModel('ModelRequest');
        $request->create();
        Registry::set('request', $request);
        
        $logger = Factory::instance()->createModule('Logger');
        $logger->startErrorsLogging();
        Registry::set('logger', $logger);
        
        $this->_router = Factory::instance()->createTypedModule('Router');
        $this->_checker = Factory::instance()->createTypedModule('Checker');
        $this->_checker->setRouter($this->_router);
        
        error_reporting(E_ALL);
    }
    
    public function run() {
        
        $this->logRequest();
        
        if (! $this->_checker->checkRequest()) {
            $this->logUnacceptedRequest();
            Core::FatalError();
        }
        
        $this->_router->route();
        
    }
    
    protected function logRequest() {
        $logRequest = false;
        $request = Registry::get('request');
        
        if (Registry::get('config')->get('application', 'log_all_requests')) {
            $logRequest = true;
        } else {
            if ($this->_checker->isSuspiciousRequest()) {
                $logRequest = true;
            }
        }
        
        if ($logRequest) {
            $request->save();
        }
    }
    
    protected function logUnacceptedRequest() {
        $description = '';
        $reports = $this->_checker->getCheckReports();
        if (is_array($reports) and count($reports)) {
            $description = implode("\n", $reports);
        }
        Registry::get('logger')->logNotice(
            'Request was blocked', $description,
            '', null, __FILE__, __LINE__
        );
    }
    
    protected function initSession() {
        
        $session_name = Registry::get('config')->get('application', 'session_name');
        if ($session_name) {
            session_name($session_name);
        }
        
        if (Registry::get('config')->get('application', 'session_start')) {
            session_start();
        }
        
    }
    
}
