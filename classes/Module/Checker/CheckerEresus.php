<?php

namespace preloader;

include_once('CheckerBase.php');

class CheckerEresus extends CheckerBase {
    
    protected function checkAdminRequest() {
        
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
