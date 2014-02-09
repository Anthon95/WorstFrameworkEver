<?php

namespace core;

use core\exception\WFEFileNotFoundException;

/*
 * Loader Class
 */

class WFELoader {
    
    /**
     * Load a PHP script
     * @param String $filename File to load
     * @throws WFEFileNotFoundException
     */
    public static function load($filename) {
        
        $filepath = ROOT . '/' . $filename;
        
        if(WFELoader::fileExists($filename)) {
            require_once($filepath);
        }
        else {
            throw new WFEFileNotFoundException($filepath);
        }
        
    }
    
    /**
     * Load and return a file's content
     * @param String $filename The file
     * @return String
     * @throws WFEFileNotFoundException
     */
    public static function content($filename) {
        
        $filepath = ROOT . '/' . $filename;
        
        if(WFELoader::fileExists($filename)) {
            return file_get_contents($filepath);
        }
        else {
            throw new WFEFileNotFoundException($filepath);
        }
        
    }
    
    /**
     * Checks if a path exists and is a file
     * @param String $filename The path
     * @return Boolean
     */
    public static function fileExists($filename) {
        
        return is_file(ROOT . '/' . $filename);
    }

}
