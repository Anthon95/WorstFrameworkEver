<?php

namespace core;

use core\exception\WFEClassNotFoundException;
use core\exception\WFEFileNotFoundException;

/*
 * Loader Class
 */

class WFELoader {

    public static function load($filename) {
        
        $filepath = ROOT . '/' . $filename;
        
        if(WFELoader::fileExists($filename)) {
            require_once($filepath);
        }
        else {
            throw new WFEClassNotFoundException($filepath);
        }
        
    }
    
    public static function fileExists($filename) {
        
        return ROOT . '/' . $filename;
    }

}
