<?php

declare(strict_types=1);

namespace Preloader\Module\Checker;

use Preloader\Module\Config\Config;
use Preloader\Module\Router\Router;
use Preloader\Model\ModelRequest;

/**
 * Checks request.
 */
class CheckerBase extends Checker {
    
    /**
     * @var array $_checkReports Messages with information about check of request. May be an empty array.
     */
    protected $_checkReports = array();
    
    /**
     * @var Router $_router Router object.
     */
    protected $_router = null;
    
    /**
     * @var ModelReqeust $_modelRequest Request model object.
     */
    protected $_modelRequest = null;
    
    /**
     * Checks if request is allowed.
     */
    public function checkRequest(): bool {
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
    
    /**
     * Gets information after checking request.
     */
    public function getCheckReports(): array {
        $repors = $this->_checkReports;
        if (! is_array($repors)) {
            $repors = array();
        }
        
        return $repors;
    }
    
    /**
     * Checks if request is suspiccious.
     */
    public function isSuspiciousRequest(): bool {
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
    
    /**
     * Sets router.
     */
    public function setRouter(Router $router): bool {
        $result = false;
        
        if (is_object($router) && $router instanceof Router) {
            $this->_router = $router;
            $result = true;
        }
        
        return $result;
    }
    
    /**
     * Gets router.
     */
    protected function getRouter(): Router {
        return $this->_router;
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
    
    /**
     * Gets request model.
     */
    protected function getModelRequest(): ModelRequest {
        return $this->_modelRequest;
    }
    
    /**
     * Gets list of allowed site scripts for direct loading.
     */
    protected function getAllowedScripts(): array {
        $scriptsList = Config::instance()->get('checker', 'allowedScripts');
        $rootInList = array('/');
        
        if ($scriptsList && is_array($scriptsList)) {
            $allowedScripts = array_merge($rootInList, $scriptsList);
        } else {
            $allowedScripts = $rootInList;
        }
        
        return $allowedScripts;
    }
    
    /**
     * Checks if request to admin area is allowed.
     */
    protected function checkAdminRequest(): bool {
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
                $this->addCheckReport('Admin request doesn\'t have correct headers.');
            }
        }
        
        return $result;
    }

    /**
     * Checks if ajax request is allowed.
     */
    protected function checkAjaxRequest(): bool {
        $rules = Config::instance()->get('checker', 'rules');
        
        $result = $this->checkRequestParams($rules);
        
        return $result;
    }

    /**
     * Checks if request to client area is allowed.
     */
    protected function checkClientRequest(): bool {
        $rules = Config::instance()->get('checker', 'rules');
        
        $result = $this->checkRequestParams($rules);
        
        return $result;
    }
    
    /**
     * Checks if request is allowed.
     */
    protected function checkRequestParams(array $params): bool {
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
    
    /**
     * Checks if url is allowed.
     */
    protected function checkUrl(array $params): bool {
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
                        $this->addCheckReport('Url\'s part "' . $part . '" is longer then ' . $params['url']['maxPartLength'] . ' symbols.');
                    }
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Checks if get data is allowed.
     */
    protected function checkGet(array $params): bool {
        $result = true;
        
        if ($this->getModelRequest()) {
            $get = $this->getModelRequest()->get;
            $result = $this->checkParams($get, $params['get']);
        }
        
        return $result;
    }
    
    /**
     * Checks if post data is allowed.
     */
    protected function checkPost(array $params): bool {
        $result = true;
        
        if ($this->getModelRequest()) {
            $post = $this->getModelRequest()->post;
            $result = $this->checkParams($post, $params['post']);
        }
        
        return $result;
    }
    
    /**
     * Checks if get or post data is allowed.
     */
    protected function checkParams(array $data, array $params): bool {
        $result = true;
        
        if (! is_array($data)) {
            $result = false;
            $this->addCheckReport('checkParams method: data is empty.');
        } else {
            if (array_key_exists('maxParamsCount', $params)) {
                if (count($data) > $params['maxParamsCount']) {
                        $result = false;
                        $this->addCheckReport('Request\'s data has more then ' . $params['maxParamsCount'] . ' items.');
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
    
    /**
     * Add message about check of request.
     */
    protected function addCheckReport(string $message): void {
        if ($message) {
            $this->_checkReports[] = $message;
        }
    }
    
}
