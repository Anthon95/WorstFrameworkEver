<?php

namespace core;

use core\exception\WFEFileNotFoundException;

/*
 * Loader Class
 */

class WFELoader {

    public static function load($filename) {
        
        $filepath = ROOT . '/' . $filename;
        
        if(Loader::fileExists($filename)) {
            require_once($filepath);
        }
        else {
            throw new FileNotFoundException($filepath);
        }
        
    }
    
    public static function fileExists($filename) {
        
        return ROOT . '/' . $filename;
    }

}
