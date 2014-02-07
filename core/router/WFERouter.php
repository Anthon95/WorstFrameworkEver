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
        
        $route = $request->getRoute();
        self::$currentRoute = $route;
        
        if($route == null) {
            $route = WFERoute::get('WFE404');
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
        
        $response = \call_user_func_array(array($controller, $myaction), $request->getArguments());
        
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
