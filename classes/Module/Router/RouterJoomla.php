<?php

namespace preloader;

include_once('RouterBase.php');

class RouterJoomla extends RouterBase {
    
    public function getRequestType() {
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
    
    public function route() {
        // Routing is not used.
    }
    
}
