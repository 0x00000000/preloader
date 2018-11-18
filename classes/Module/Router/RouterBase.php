<?php

declare(strict_types=1);

namespace preloader;

include_once('Router.php');

/**
 * Calculates request type and if needed routes UA.
 */
abstract class RouterBase extends Router {
    
    /**
     * @var ModelRequest|null $_modelRequest Model request.
     */
    protected $_modelRequest = null;
    
    /**
     * Gets site root path.
     */
    public function getSiteRoot(): string {
        $dir = FileSystem::getRoot();
        $levels = Config::instance()->get('router', 'levelsToSiteRoot');
        for ($i = 0; $i < $levels; $i++) {
            $dir = dirname($dir);
        }
        
        $siteRoot = realpath($dir);
        return $siteRoot;
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
    
}
