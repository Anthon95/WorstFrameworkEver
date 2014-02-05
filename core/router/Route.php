<?php

/*
 * This class defines a route
 */

namespace core\router;

class Route {
    
    private $path;
    private $controller;
    private $action;
    
    public function __construct($path, $controller, $action) {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
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
}
