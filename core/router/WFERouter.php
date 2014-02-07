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

    private static $controllers = array();
    private static $actions = array();
    private static $currentRoute = null;

    public static function run(WFERequest $request) {

        $routeName = $request->getRouteName();
        self::$currentRoute = $routeName;
        
        try {
            $route = WFEConfig::get('routes::' . $routeName);
        } catch (WFEConfigErrorException $e) {
            $route = WFEConfig::get('routes::WFE404');
        }

        self::$controllers[] = $route->getController();
        self::$actions[] = $route->getAction();

        if (!self::controllerExists(self::$controllers)) {
            throw new WFEDefinitionException('The controller : ' . self::$controllers . ' does not exist');
        }
        
        $mycontroller = str_replace('/', '\\', 'app\\controllers\\' . self::getCurrentController());
        
        $controller = new $mycontroller();
        
        if( get_class($controller) != 'core\WFEController' && ! is_subclass_of($controller, 'core\WFEController') ) {
            throw new WFEDefinitionException('Controller : ' . $mycontroller . ' must extends core\WFEController');
        }

        $myaction = self::getCurrentAction();
        
        if (!self::actionExists($controller, $myaction)) {
            throw new WFEDefinitionException('The action : ' . $myaction . ' does not exist');
        }
        
        $response = $controller->$myaction();
        
        if( get_class($response) != 'core\WFEResponse' && ! is_subclass_of($response, 'core\WFEResponse') ) {
            throw new WFEDefinitionException('Action : ' . $myaction . ' in controller : ' . $mycontroller . ' must return a core\WFEResponse object');
        }
        
        array_pop(self::$controllers);
        array_pop(self::$actions);
        
        $response->send();
    }
    
    public static function getCurrentController() {
        if(!empty(self::$controllers)) {
            return end(self::$controllers); 
        }
        else {
            return null;
        }
    }
    
    public static function getCurrentAction() {
        if(!empty(self::$actions)) {
            return end(self::$actions); 
        }
        else {
            return null;
        }
    }
    
    public static function getCurrentRoute() {
        return self::$currentRoute;
    }
    
    public static function getRouteName($path) {
        
    }
    
    public static function getURI(WFERequest $request = null) {
        if($request == null) {
            return str_replace('/'.RELATIVE_ROOT, '', $_SERVER['REQUEST_URI']);
        }
        else {
            $route = WFEConfig::get('routes::' . $request->getRouteName());
            return $route->getPath();
        }
    }
    
    /**
     * Return true if controller exists in app/controllers 
     * @param String $controller
     * @return Boolean
     */
    private static function controllerExists($controller) {

        return ! WFELoader::fileExists('app/controllers/' . $controller . '.php' || ! class_exists('app\\controllers\\' . self::$controllers));
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
