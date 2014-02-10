<?php

/*
 * This class defines a route
 */

namespace core\router;

use core\exception\WFERouteException;

class WFERoute {
    
    /**
     * Store all instances of WFERoute
     * @var Array
     */
    private static $instances = array();
    /**
     * Path of the route
     * @var String
     */
    private $path;
    /**
     * Controller class of the route
     * @var String
     */
    private $controller;
    /**
     * Action of the route's controller
     * @var String
     */
    private $action;
    /**
     * Name of the route
     * @var String
     */
    private $name;
    
    /**
     * Instanciate a new route
     * @param String $name Name of the route
     * @param String $path Path of the route
     * @param String $controller Controller of the route
     * @param String $action Action of the route's controller
     */
    public function __construct($name, $path, $controller, $action) {
        $this->name = $name;
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;

        self::$instances[] = $this;
    }
    
    /**
     * Returns the name of the route
     * @return String
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Returns the path of the route
     * @return String
     */
    public function getPath() {
        return $this->path;
    }
    
    /**
     * Return the controller of the route
     * @return String
     */
    public function getController() {
        return $this->controller;
    }
    
    /**
     * Return the action of the route's controller
     * @return String
     */
    public function getAction() {
        return $this->action;
    }
    
    /**
     * Get a WFERoute object from its names
     * @param String $name Name of the route
     * @return WFERoute Returns null if the route does not exist
     */
    public static function get($name) {
        foreach (self::$instances as $route) {
            if ($route->getName() == $name) {
                return $route;
            }
        }
        return null;
    }
    
    /**
     * Get a WFERoute object from its path
     * @param String $path Path of the route
     * @return WFERoute Returns null if the route does not exist
     */
    public static function getByPath($path) {
        foreach (self::$instances as $route) {
            if ($route->getPath() != null && self::pathMatch($path, $route->getPath())) {
                return $route;
            }
        }
        return null;
    }
    
    /**
     * Return all instances of WFERoute
     * @return Array
     */
    public static function getInstances() {
        return self::$instances;
    }
    
    /**
     * Returns the path of the route with an array of parameters
     * @param Array $params Parameters to inject
     * @return String
     * @throws WFERouteException If route has not a parameter
     */
    public function injectParams($params) {
               
        $url = $this->getPath();
        foreach ($params as $paramName => $param) {
            if (!$this->hasParam($paramName)) {
                throw new WFERouteException('Parameter : ' . $paramName . ' does not exists in route : ' . $this->name);
            }   
            else {
                $url = str_replace(array(':path', '{' . $paramName . '}'), array('', $param), $url);
            }
        }
        
        return APP_PATH . $url;
    }
    
    /**
     * Check if the route has a certain parameter
     * @param String $paramName Name of the parameter
     * @return Boolean
     */
    private function hasParam($paramName) {
        $url = $this->getPath();
        return strpos($url, '{' . $paramName . '}') !== false || strpos($url, '{' . $paramName . ':path}') !== false;
    } 
    
    /**
     * Check if an path matches a route's pattern path
     * @param String $path
     * @param String $pattern
     * @return boolean
     */
    private static function pathMatch($path, $pattern) {
        
        $pattern_segs = explode('/', $pattern);
        $path_segs = explode('/', $path);
        
        $sizePattern = sizeof($pattern_segs);
        $sizePath = sizeof($path_segs);
        
        if ($sizePath > 2 && $path_segs[$sizePath - 1] == '') {
            array_pop($path_segs);
        }
        
        $sizePath = sizeof($path_segs);
        
        for ($i = 0; $i < $sizePattern; $i++) {
            
            if ((substr($pattern_segs[$i], 0, 1) != '{' && $pattern_segs[$i] != $path_segs[$i])) {
                
               return false;
            }
            
            if(strpos($pattern_segs[$i], ':path') !== false) {
                
                $param = '';
                
                for($i2 = $i ; $i2 < $sizePath ; $i2++) {
                    $param .= '/' . $path_segs[$i2];
                    unset($path_segs[$i2]);
                }
                $path_segs[] = $param;
                $sizePath = sizeof($path_segs);
                
                return true;
            }
            
            
            
            if ($sizePath != $sizePattern) {
                continue;
            }
        }

        return true;
    }

}
