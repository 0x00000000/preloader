<?php

namespace preloader;

include_once('Router.php');

abstract class RouterBase extends Router {
    
    public function getSiteRoot() {
        $dir = FileSystem::getRoot();
        $levels = Registry::get('config')->get('router', 'levelsToSiteRoot');
        for ($i = 0; $i < $levels; $i++) {
            $dir = dirname($dir);
        }
        $siteRoot = realpath($dir);
        return $siteRoot;
    }
    
}
