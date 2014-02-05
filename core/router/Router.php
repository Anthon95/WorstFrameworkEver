<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace core\router;

class Router {

    private $controller;
    private $action;

    
   static function run() {

        $routeName = $request::getRouteName();
      
        $route = Config::get('routes::' . $routeName);
        
        $this->controller = $route->getController();
        $this->action = $route->getAction();
        
    }

    private static function controllerExists() {
        $path = APP_PATH . 'controllers' . DS . $this->controller . '.php';
        if (file_exists($path)) {

            return true;
        }

        return false;
    }

    private static function actionExists() {
        if (is_callable(array('application\\controllers\\' . $this->controller, $this->action))) {

            return true;
        }

       return false;
    }

}
