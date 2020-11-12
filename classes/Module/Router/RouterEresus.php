<?php

namespace Preloader\Module\Router;

use Preloader\System\Core;

/**
 * Calculates request type and if needed routes UA.
 */
class RouterEresus extends RouterBase {
    
    /**
     * Gets request type.
     */
    public function getRequestType(): string {
        $type = false;
        
        if ($this->getModelRequest()) {
            $url = $this->getModelRequest()->url;
            if (strpos($url, '/admin') === 0) {
                $type = Router::REQUEST_TYPE_ADMIN;
            } else if (strpos($url, '/ajax') === 0) {
                $type = Router::REQUEST_TYPE_AJAX;
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
        // В Eresus используются глобальные переменные.
        // Они должны быть определены здесь.
        global $Eresus, $page, $db;
        
        $siteRoot = $this->getSiteRoot();
        $requestType = $this->getRequestType();
        if ($siteRoot) {
            if ($requestType === Router::REQUEST_TYPE_ADMIN) {
                include_once($siteRoot . '/core/admin.php');
                exit;
            } else if ($requestType === Router::REQUEST_TYPE_AJAX) {
                include_once($siteRoot . '/core/ajax.php');
                exit;
            } else if ($requestType === Router::REQUEST_TYPE_CLIENT) {
                include_once($siteRoot . '/core/client.php');
                exit;
            } else {
                Core::FatalError();
                exit;
            }
        } else {
            Core::FatalError();
            exit;
        }
    }
    
}
