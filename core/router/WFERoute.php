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
            if(self::pathMatch($path, $route->getPath())) {
                return $route;
            }
        }
        return null;
    }
    
    private static function pathMatch($path, $pattern) {
        $pattern_segs = explode('/', $pattern);
        $path_segs = explode('/', $path);
        
        for($i = 0 ; $i < sizeof($path_segs) ; $i++) {
            
            if(substr($pattern_segs[$i], 0, 1) != '{' && $pattern_segs[$i] != $path_segs[$i]) {
                
                return false;
            }
        }
        
        return true;
    }
}
