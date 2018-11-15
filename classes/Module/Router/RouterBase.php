<?php

declare(strict_types=1);

namespace preloader;

include_once('Router.php');

abstract class RouterBase extends Router {
    
    protected $_modelRequest = null;
    
    public function getSiteRoot(): string {
        $dir = FileSystem::getRoot();
        $levels = Config::instance()->get('router', 'levelsToSiteRoot');
        for ($i = 0; $i < $levels; $i++) {
            $dir = dirname($dir);
        }
        
        $siteRoot = realpath($dir);
        return $siteRoot;
    }
    
    public function setModelRequest(ModelRequest $modelRequest): bool {
        $result = false;
        
        if (is_object($modelRequest) && $modelRequest instanceof ModelRequest) {
            $this->_modelRequest = $modelRequest;
            $result = true;
        }
        
        return $result;
    }
    
    protected function getModelRequest(): ModelRequest {
        return $this->_modelRequest;
    }
    
}
