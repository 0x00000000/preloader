<?php

namespace Preloader\Module\Router;

/**
 * Calculates request type and if needed routes UA.
 */
class RouterJoomla extends RouterBase {
    
    /**
     * Gets request type.
     */
    public function getRequestType(): string {
        $type = false;
        
        if ($this->getModelRequest()) {
            $url = $this->getModelRequest()->url;
            if (strpos($url, '/administrator') === 0) {
                $type = Router::REQUEST_TYPE_ADMIN;
            } else {
                $type = Router::REQUEST_TYPE_CLIENT;
            }
        }
        
        return $type;
    }
    
    /**
     * Routes UA.
     */
    public function route(): void {
        // Routing is not used.
    }
    
}
