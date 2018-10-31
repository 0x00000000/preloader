<?php

namespace preloader;

Core::loadModule('Router');

abstract class CheckerAbstract {
    
    abstract public function checkRequest();
    
    abstract public function setRouter(Router $router);
    
    abstract public function isSuspiciousRequest();
    
    abstract public function getCheckReports();
    
}
