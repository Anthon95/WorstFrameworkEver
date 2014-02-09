<?php

/*
 * This class defines a route
 */

namespace core\router;

use core\exception\WFERouteException;

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
            if ($route->getName() == $name) {
                return $route;
            }
        }
        return null;
    }

    public static function getByPath($path) {
        foreach (self::$instances as $route) {
            if ($route->getPath() != null && self::pathMatch($path, $route->getPath())) {
                return $route;
            }
        }
        return null;
    }
    
    public function injectParams($params) {
       
        
        $url = $this->getPath();
        foreach ($params as $paramName => $param) {
            if (!$this->hasParam($paramName)) {
                throw new WFERouteException('Parameter : ' . $paramName . ' does not exists in route : ' . $this->name);
            }   
            else {
                $url = str_replace('{' . $paramName . '}', $param, $url);
                $url = str_replace('{' . $paramName . ':path}', $param, $url);
            }
        }
        
        return APP_PATH . $url;
    }
    
    private function hasParam($paramName) {
        $url = $this->getPath();
        return strpos($url, '{' . $paramName . '}') !== false || strpos($url, '{' . $paramName . ':path}') !== false;
    } 

    private static function pathMatch($path, $pattern) {
        
        $pattern_segs = explode('/', $pattern);
        $path_segs = explode('/', $path);
        
        $sizePattern = sizeof($pattern_segs);
        $sizePath = sizeof($path_segs);
        
        if ($sizePath > 2 && $path_segs[$sizePath - 1] == '') {
            array_pop($path_segs);
        }
        
        $formated = false;
        
        for ($i = 0; $i < $sizePattern; $i++) {
            
            if ((substr($pattern_segs[$i], 0, 1) != '{' && $pattern_segs[$i] != $path_segs[$i])) {
                
               return false;
            }
            
            if(!$formated && strpos($pattern_segs[$i], ':path') !== false) {
                
                $formated = true;
                
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
