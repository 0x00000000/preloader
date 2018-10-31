<?php

namespace preloader;

include_once('Checker.php');

class CheckerBase extends Checker {
    
    protected $_router = null;
    
    protected $_checkReports = array();
    
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
        
        $request = Registry::get('request');
        
        if ($request) {
            $isSuspicious = false;
            
            $urlToScript = preg_replace('/\?.*/', '', $request->url);
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
        $scriptsList = Registry::get('config')->get('checker', 'allowedScripts');
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
        
        $request = Registry::get('request');
        
        $headerKey = Registry::get('config')->get('checker', 'admin_header_key');
        $headerValue = Registry::get('config')->get('checker', 'admin_header_value');
        $headers = $request->headers;
        if (
            $headers
            && array_key_exists($headerKey, $headers)
            && $headers[$headerKey] === $headerValue
        ) {
            $result = true;
        } else {
            $this->addCheckReport('Admin request doesn`t have correct headers.');
        }
        
        return $result;
    }

    protected function checkAjaxRequest() {
        $rules = Registry::get('config')->get('checker', 'rules');
        
        $result = $this->checkRequestParams($rules);
        
        return $result;
    }

    protected function checkClientRequest() {
        $rules = Registry::get('config')->get('checker', 'rules');
        
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
            $request = Registry::get('request');
            
            if ($this->checkUrl($request, $params)) {
                if ($this->checkGet($request, $params)) {
                    if ($this->checkPost($request, $params)) {
                        $result = true;
                    }
                }
            }
        } else {
            $this->addCheckReport('Checker params are incorrect.');
        }
        
        return $result;
    }
    
    protected function checkUrl($request, $params) {
        $result = true;
        
        $url = preg_replace('/\?.*/', '', $request->url);
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
        
        return $result;
    }
    
    protected function checkGet($request, $params) {
        $result = true;
        
        if ($request->get) {
            $get = $request->get;
            $result = $this->checkParams($get, $params['get']);
        }
        
        return $result;
    }
    
    protected function checkPost($request, $params) {
        $result = true;
        
        if ($request->post) {
            $post = $request->post;
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
    
}
