<?php

namespace preloader;

include_once('Checker.php');

class CheckerBase extends Checker {
    
    protected $_router = null;
    
    protected $_checkReports = array();
    
    protected $_modelRequest = null;
    
    public function checkRequest() {
        $result = false;
        
        $requestType = $this->_router->getRequestType();
        if ($requestType === Router::REQUEST_TYPE_ADMIN) {
            $result = $this->checkAdminRequest();
        } else if ($requestType === Router::REQUEST_TYPE_AJAX) {
            $result = $this->checkAjaxRequest();
        } else if ($requestType === Router::REQUEST_TYPE_CLIENT) {
            $result = $this->checkClientRequest();
        } else {
            $result = false;
        }
        
        return $result;
    }
    
    public function getCheckReports() {
        $repors = $this->_checkReports;
        if (! is_array($repors)) {
            $repors = array();
        }
        
        return $repors;
    }
    
    public function setRouter(Router $router) {
        $this->_router = $router;
    }
    
    public function isSuspiciousRequest() {
        $isSuspicious = true;
        
        if ($this->getModelRequest()) {
            $isSuspicious = false;
            
            $url = $this->getModelRequest()->url;
            
            $urlToScript = preg_replace('/\?.*/', '', $url);
            $siteRoot = $this->_router->getSiteRoot();
            if (is_file($siteRoot . $urlToScript) || is_dir($siteRoot . $urlToScript)) {
                if (! in_array($urlToScript, $this->getAllowedScripts())) {
                    $isSuspicious = true;
                }
            }
        }
        
        return $isSuspicious;
    }
    
    protected function getAllowedScripts() {
        $scriptsList = Config::instance()->get('checker', 'allowedScripts');
        $rootInList = array('/');
        
        if ($scriptsList) {
            $allowedScripts = array_merge($rootInList, $scriptsList);
        } else {
            $allowedScripts = $rootInList;
        }
        
        return $allowedScripts;
    }
    
    protected function checkAdminRequest() {
        $result = false;
        
        if ($this->getModelRequest()) {
            $headerKey = Config::instance()->get('checker', 'admin_header_key');
            $headerValue = Config::instance()->get('checker', 'admin_header_value');
            $headers = $this->getModelRequest()->headers;
            if (
                $headers
                && array_key_exists($headerKey, $headers)
                && $headers[$headerKey] === $headerValue
            ) {
                $result = true;
            } else {
                $this->addCheckReport('Admin request doesn`t have correct headers.');
            }
        }
        
        return $result;
    }

    protected function checkAjaxRequest() {
        $rules = Config::instance()->get('checker', 'rules');
        
        $result = $this->checkRequestParams($rules);
        
        return $result;
    }

    protected function checkClientRequest() {
        $rules = Config::instance()->get('checker', 'rules');
        
        $result = $this->checkRequestParams($rules);
        
        return $result;
    }
    
    protected function checkRequestParams($params) {
        $result = false;
        
        if (
            is_array($params) && count($params)
            && array_key_exists('url', $params) && is_array($params['url']) && count($params['url'])
            && array_key_exists('get', $params) && is_array($params['get']) && count($params['url'])
            && array_key_exists('post', $params) && is_array($params['post']) && count($params['url'])
        ) {
            if ($this->checkUrl($params)) {
                if ($this->checkGet($params)) {
                    if ($this->checkPost($params)) {
                        $result = true;
                    }
                }
            }
        } else {
            $this->addCheckReport('Checker params are incorrect.');
        }
        
        return $result;
    }
    
    protected function checkUrl($params) {
        $result = false;
        
        if ($this->getModelRequest()) {
            $result = true;
            
            $url = preg_replace('/\?.*/', '', $this->getModelRequest()->url);
            $parts = explode('/', $url);
            
            if (array_key_exists('maxPartsCount', $params['url'])) {
                if (count($parts) > $params['url']['maxPartsCount']) {
                        $result = false;
                        $this->addCheckReport('Url has more then ' . $params['url']['maxPartsCount'] . ' parts.');
                }
            }
            
            if (array_key_exists('maxPartLength', $params['url'])) {
                foreach ($parts as $part) {
                    if (strlen($part) > $params['url']['maxPartLength']) {
                        $result = false;
                        $this->addCheckReport('Url`s part "' . $part . '" is longer then ' . $params['url']['maxPartLength'] . ' symbols.');
                    }
                }
            }
        }
        
        return $result;
    }
    
    protected function checkGet($params) {
        $result = true;
        
        if ($this->getModelRequest()) {
            $get = $this->getModelRequest()->get;
            $result = $this->checkParams($get, $params['get']);
        }
        
        return $result;
    }
    
    protected function checkPost($params) {
        $result = true;
        
        if ($this->getModelRequest()) {
            $post = $this->getModelRequest()->post;
            $result = $this->checkParams($post, $params['post']);
        }
        
        return $result;
    }
    
    protected function checkParams($data, $params) {
        $result = true;
        
        if (! is_array($data)) {
            $result = false;
            $this->addCheckReport('checkParams method: data is empty.');
        } else {
            if (array_key_exists('maxParamsCount', $params)) {
                if (count($data) > $params['maxParamsCount']) {
                        $result = false;
                        $this->addCheckReport('Request`s data has more then ' . $params['maxParamsCount'] . ' items.');
                }
            }
            
            if (array_key_exists('maxKeyLength', $params)) {
                foreach ($data as $key => $value) {
                    if (strlen($key) > $params['maxKeyLength']) {
                        $result = false;
                        $this->addCheckReport('Key "' . $key . '" is longer then ' . $params['maxKeyLength'] . ' symbols.');
                        break;
                    }
                }
            }
            
            if (array_key_exists('maxValueLength', $params)) {
                foreach ($data as $key => $value) {
                    if (strlen($value) > $params['maxValueLength']) {
                        $this->addCheckReport('Value "' . $value . '" is longer then ' . $params['maxValueLength'] . ' symbols.');
                        $result = false;
                        break;
                    }
                }
            }
        }
        
        return $result;
    }
    
    protected function addCheckReport($message) {
        if ($message) {
            $this->_checkReports[] = $message;
        }
    }
    
    public function setModelRequest($modelRequest) {
        $result = false;
        if (is_object($modelRequest) && $modelRequest instanceof ModelRequest) {
            $this->_modelRequest = $modelRequest;
            $result = true;
        }
        
        return $result;
    }
    
    protected function getModelRequest() {
        return $this->_modelRequest;
    }
    
}
