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
}
