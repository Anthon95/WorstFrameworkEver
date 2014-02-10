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
use core\WFEResponse;

class WFERouter {
    
    /**
     * Stack of controllers called inside the running application
     * @var Array
     */
    private static $controllers = array();
    /**
     * Stack of actions called inside the running application
     * @var Array
     */
    private static $actions = array();
    /**
     * Current WFERoute currently routed by the WFERouter
     * @var WFERoute
     */
    private static $currentRoute = null;
    /**
     * Current WFERequest currently routed by the WFERouter
     * @var WFERequest
     */
    private static $currentRequest = null;
    
    /**
     * Routes a WFERequest
     * @param \core\WFERequest $request
     * @return \core\WFEResponse
     * @throws WFEDefinitionException If a controller or an action is not defined
     */
    public static function run(WFERequest $request) {
        
        $route = $request->getRoute();
        
        if($route == null) {
            $route = WFERoute::get('WFE404');
            $request = new WFERequest('GET', 'WFE404');
        }
        else if( self::isInitialRequest() && ! $request->isAjax() && $route->getController() != WFEConfig::get('publicController')  ) {
            $oldRequest = $request;
            $request = new WFERequest('GET', 'WFEMain', array(
                array(
                    'routeToLoad' => $route->injectParams($oldRequest->getArguments()),
                    'WFERoutes' => WFERoute::getInstances(),
                    'appRoute' => APP_PATH,
                )
            ));    
        }
        
        $route = $request->getRoute();
        
        self::$currentRequest = $request;
        
        self::$controllers[] = $route->getController();
        self::$actions[] = $route->getAction();

        if (!self::controllerExists(self::getCurrentController())) {
            throw new WFEDefinitionException('The controller : ' . self::getCurrentController() . ' does not exist');
        }
        
        $mycontroller = self::getControllerClass(self::getCurrentController());
        
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
        
        if(sizeof(self::$controllers) == 1) {
            $response->send();
        }
        else {
            array_pop(self::$controllers);
            array_pop(self::$actions);
            
            return $response;
        }
    }
    
    /**
     * Alias to re-routes to a 404 error page
     */
    public static function run404() {
        $response = WFERouter::run( new WFERequest('GET', 'WFE404') );
        $response->send();
    }
    
    /**
     * Alias to re-routes to a 500 error page
     */
    public static function run500() {
        $response = WFERouter::run( new WFERequest('GET', 'WFE500') );
        $response->send();
    }
    
    /**
     * Redirect to a different route
     * @param String $routeName Name of the route
     * @param Array $params $params for the route path
     * @return \core\WFEResponse
     */
    public static function redirect($routeName, $params = array()) {
        
        $route = WFERoute::get($routeName);
    
        header('Location: ' . $route->injectParams($params));
        return new WFEResponse();
    }
    
    /**
     * Return the current controller class name
     * @return String
     */
    public static function getCurrentController() {
        if(!empty(self::$controllers)) {
            return end(self::$controllers); 
        }
        else {
            return null;
        }
    }
    
    /**
     * Return the current action name
     * @return String
     */
    public static function getCurrentAction() {
        if(!empty(self::$actions)) {
            return end(self::$actions); 
        }
        else {
            return null;
        }
    }
    
    /**
     * Return the current WFERoute
     * @return WFERoute
     */
    public static function getCurrentRoute() {
        return self::$currentRoute;
    }
    
    /**
     * Return the curent WFERequest
     * @return WFERequest
     */
    public static function getCurrentRequest() {
        return self::$currentRequest;
    }
    
    
    /**
     * Return true if controller exists in app/controllers 
     * @param String $controller
     * @return Boolean
     */
    private static function controllerExists($controller) {

        return WFELoader::fileExists('app/controllers/' . $controller . '.php') && class_exists(self::getControllerClass($controller));
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
    
    /**
     * Return the full classname of a controller
     * @param String $controller
     * @return String
     */
    private static function getControllerClass($controller) {
        return 'app\\controllers\\' . str_replace('/', '\\', $controller);
    }
    
    /**
     * Return true if this is the initial request and not a sub request
     * @return boolean
     */
    private static function isInitialRequest() {
        return empty(self::$controllers);
    }

}
