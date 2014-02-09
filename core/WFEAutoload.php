<?php

namespace core;

use core\exception\WFEClassNotFoundException;

/*
 * Autoload class
 */

class WFEAutoload {
    
    /**
     * 
     * @var Array Array containing namespaces
     */
    protected static $namespaces = array();
    /**
     *
     * @var Boolean 
     */
    protected static $registered = false;
    /**
     *
     * @var String Root of project
     */
    protected static $root = null;
    
    /**
     * Register a namespace for the autoloader
     * @param String $namespace Namespace to regsiter
     */
    public static function register($namespace) {
        
        if(self::$root == null) {
            self::$root = str_replace('\\', '/', str_replace(__NAMESPACE__, '', __DIR__));
        }
        
        self::$namespaces[] = $namespace;
        if( ! self::$registered) {
            spl_autoload_register(array(__CLASS__, 'load'));
        }
    }
     
    /**
     * Load a namespaced class
     * @param String $class Class to load
     * @throws WFEClassNotFoundException
     */
    protected static function load($class) {
        foreach (self::$namespaces as $namespace) {
            $namespace = str_replace('\\', '/', $namespace);
            $class = str_replace('\\', '/', $class);
            $filename = self::$root . $class . '.php';
            if(file_exists($filename)){
                require_once $filename;
            }
            elseif(true) {

            }
            else {
                throw new WFEClassNotFoundException($filename, $class);
            }
        }
        
    }
    
}
