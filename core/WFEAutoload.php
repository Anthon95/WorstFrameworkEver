<?php

namespace core;

use core\exception\WFEClassNotFoundException;

/*
 * Autoload class
 */

class WFEAutoload {
    
    protected static $namespaces = array();
    protected static $registered = false;
    protected static $root = null;
    
    public static function register($namespace) {
        
        if(self::$root == null) {
            self::$root = str_replace('\\', '/', str_replace(__NAMESPACE__, '', __DIR__));
        }
        
        self::$namespaces[] = $namespace;
        if( ! self::$registered) {
            spl_autoload_register(array(__CLASS__, 'load'));
        }
    }
        
    protected static function load($class) {
        foreach (self::$namespaces as $namespace) {
            $namespace = str_replace('\\', '/', $namespace);
            $class = str_replace('\\', '/', $class);
            $filename = self::$root . $class . '.php';
            if(file_exists($filename)){
                require_once $filename;
            }
            else {
                
                throw new WFEClassNotFoundException($filename, $class);
            }
        }
        
    }
    
}
