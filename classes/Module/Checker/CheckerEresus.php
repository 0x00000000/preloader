<?php

namespace preloader;

include_once('CheckerBase.php');

class CheckerEresus extends CheckerBase {
    
    protected function checkAdminRequest() {
        
        $result = parent::checkAdminRequest();
        
        if ($result) {
            $request = Registry::get('request');
            
            if (strpos($request->url, 'admin.php?mod=files') !== false) {
                $result = false;
                $this->addCheckReport('Request to mod=files.');
            }
        }
        
        return $result;
    }
    
}
