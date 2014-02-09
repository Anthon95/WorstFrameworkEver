<?php

namespace core;

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
            throw new WFEFileNotFoundException($filepath);
        }
        
    }
    
    public static function content($filename) {
        
        $filepath = ROOT . '/' . $filename;
        
        if(WFELoader::fileExists($filename)) {
            return file_get_contents($filepath);
        }
        else {
            throw new WFEFileNotFoundException($filepath);
        }
        
    }
    
    public static function fileExists($filename) {
        
        return is_file(ROOT . '/' . $filename);
    }

}
