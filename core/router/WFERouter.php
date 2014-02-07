<?php

/*
 * Class Router
 */

namespace core\router;

use core\exception\WFEConfigErrorException;
use core\exception\WFEDefinitionException;
use core\WFEConfig;
use core\WFEController;
use core\WFELoader;
use core\WFERequest;

class WFERouter {

    private static $controller;
    private static $action;
    private static $currentRoute = null;

    static function run(WFERequest $request) {

        $routeName = $request->getRouteName();
        self::$currentRoute = $routeName;
        
        try {
            $route = WFEConfig::get('routes::' . $routeName);
        } catch (WFEConfigErrorException $e) {
            $route = WFEConfig::get('routes::WFE404');
        }

        self::$controller = $route->getController();
        self::$action = $route->getAction();

        if (!self::controllerExists(self::$controller)) {
            throw new WFEDefinitionException('The controller : ' . self::$controller . ' does not exist');
        }
        
        $mycontroller = str_replace('/', '\\', 'app\\controllers\\' . self::$controller);
        
        $controller = new $mycontroller();
        
        if( get_class($controller) != 'core\WFEController' && ! is_subclass_of($controller, 'core\WFEController') ) {
            throw new WFEDefinitionException('Controller : ' . self::$controller . ' must extends core\WFEController');
        }

        if (!self::actionExists($controller, self::$action)) {
            throw new WFEDefinitionException('The action : ' . self::$action . ' does not exist');
        }
        
        $myaction = self::$action;
        
        $response = $controller->$myaction();
        
        if( get_class($response) != 'core\WFEResponse' && ! is_subclass_of($response, 'core\WFEResponse') ) {
            throw new WFEDefinitionException('Action : ' . self::$action . ' in controller : ' . self::$controller . ' must return a core\WFEResponse object');
        }
        
        $response->send();
    }
    
    public static function getCurrentRoute() {
        return self::$currentRoute;
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
