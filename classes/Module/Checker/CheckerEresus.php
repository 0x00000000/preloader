<?php

declare(strict_types=1);

namespace preloader;

include_once('CheckerBase.php');

/**
 * Checks request.
 */
class CheckerEresus extends CheckerBase {
    
    /**
     * Checks if request to admin area is allowed.
     */
    protected function checkAdminRequest(): bool {
        
        $result = false;
        
        if ($this->getModelRequest()) {
            $result = parent::checkAdminRequest();
            
            if ($result) {
                $url = $this->getModelRequest()->url;
                if ($url && strpos($url, 'admin.php?mod=files') !== false) {
                    $result = false;
                    $this->addCheckReport('Request to mod=files.');
                }
            }
        }
        
        return $result;
    }
    
}
