<?php

/*
 * Class Router
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
        
        $response = $controller->$myaction();
        
        if( get_class($response) != 'core\WFEResponse' && ! is_subclass_of($response, 'core\WFEResponse')) {
            throw new WFEDefinitionException('Action : ' . self::$action . ' in controller : ' . self::$controller . ' must return a WFEResponse object');
        }
        
        $response->send();
    }

    /**
     * Return true if controller exists in app/controllers 
     * @param String $controller
     * @return Boolean
     */
    private static function controllerExists($controller) {

        return ! WFELoader::fileExists('app/controllers/' . $controller . '.php' || ! class_exists('app\\controllers\\' . self::$controller));
    }
    
    /**
     * Return true if action $action exists in Controller $controller
     * @param WFEController $controller
     * @param String $action
     * @return Boolean
     */
    private static function actionExists($controller, $action) {

        return method_exists($controller, $action); 
    }

}
