<?php

/*
 * This class defines a route
 */

namespace core\router;

class WFERoute {
    
    private static $instances = array();
    
    private $path;
    private $controller;
    private $action;
    private $name;
    
    public function __construct($name, $path, $controller, $action) {
        $this->name = $name;
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
        
        self::$instances[] = $this;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getPath() {
        return $this->path;
    }
    
    public function getController() {
        return $this->controller;
    }
    
    public function getAction() {
        return $this->action;
    }
    
    public static function get($name) {
        foreach (self::$instances as $route) {
            if($route->getName() == $name) {
                return $route;
            }
        }
        return null;
    }
    
    public static function getByPath($path) {
        foreach (self::$instances as $route) {
            if($route->getPath() != null && self::pathMatch($path, $route->getPath())) {
                return $route;
            }
        }
        return null;
    }
    
    private static function pathMatch($path, $pattern) {
        $pattern_segs = explode('/', $pattern);
        $path_segs = explode('/', $path);
        
        for($i = 0 ; $i < max(sizeof($path_segs), sizeof($pattern_segs)) ; $i++) {
            
            if(isset($pattern_segs[$i]) && substr($pattern_segs[$i], 0, 1) != ':' && isset($path_segs[$i]) && $pattern_segs[$i] != $path_segs[$i]) {
                
                return false;
            }
        }
        
        return true;
    }
}
