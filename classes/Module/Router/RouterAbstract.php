<?php

namespace preloader;

abstract class RouterAbstract {
    
    const REQUEST_TYPE_ADMIN = 'admin';
    const REQUEST_TYPE_AJAX = 'ajax';
    const REQUEST_TYPE_CLIENT = 'client';
    
    abstract public function getRequestType();
    
    abstract public function route();
    
    abstract public function getSiteRoot();
    
    abstract public function setModelRequest($modelRequest);
    
}