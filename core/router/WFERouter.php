<?php

/*
 * Class Router
 */

namespace core\router;

use core\exception\WFEDefinitionException;
use core\WFEConfig;
use core\WFELoader;
use core\WFERequest;

class WFERouter {

    private static $controller;
    private static $action;

    static function run(WFERequest $request) {

        $routeName = $request->getRouteName();

        $route = WFEConfig::get('routes::' . $routeName);

        self::$controller = $route->getController();
        self::$action = $route->getAction();

        if (!self::controllerExists(self::$controller)) {
            throw new WFEDefinitionException('The controller : ' . self::$controller . ' does not exist');
        }
        
        $mycontroller = 'app\\controllers\\' . self::$controller;
        
        $controller = new $mycontroller();

        if (!self::actionExists($controller, self::$action)) {
            throw new WFEDefinitionException('The action : ' . self::$action . ' does not exist');
        }
        
        $myaction = self::$action;
        
        $controller->$myaction();
    }

    private static function controllerExists($controller) {

        return ! WFELoader::fileExists('app/controllers/' . $controller . '.php' || ! class_exists('app\\controllers\\' . self::$controller));
    }

    private static function actionExists($controller, $action) {

        return method_exists($controller, $action); 
        
        
    }
    
   

}
