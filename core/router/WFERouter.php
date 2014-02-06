<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace core\router;

use core\exception\WFEDefinitionException;
use core\WFEConfig;
use core\WFEController;
use core\WFELoader;
use core\WFERequest;

class WFERouter {

    private static $controller;
    private static $action;

    static function run(WFERequest $request) {

        $routeName = $request::getRouteName();

        $route = WFEConfig::get('routes::' . $routeName);

        self::$controller = $route->getController();
        self::$action = $route->getAction();

        if (!self::controllerExists(self::$controller)) {
            throw new WFEDefinitionException('The controller :' . self::$controller . ' does not exist');
        }
        
        $controller = new WFEController();

        if (!self::actionExists(self::$action)) {
            throw new WFEDefinitionException('The action :' . self::$action . ' does not exist');
        }
    }

    private static function controllerExists($controller) {

        return WFELoader::fileExists('app/controllers/' . $controller . '.php');
    }

    private static function actionExists($controller, $action) {

        return method_exists($controller, $action); 
        
    }
    
    

}
