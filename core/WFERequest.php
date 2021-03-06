<?php

namespace core;

use core\exception\WFERequestException;
use core\exception\WFESystemErrorException;
use core\router\WFERoute;
use core\router\WFERouter;


/*
 * Request class
 */

class WFERequest {
    
    /**
     * Current route of the request
     * @var String
     */
    private $route = null;
    
    /**
     * Method of the request
     * @var String
     */
    private $method = null;
    
    /**
     * Params of the request
     * @var Array
     */
    private $params = null;
    
    /**
     * Instanciate a new WFERequest
     * @param String $method Whether GET or POST
     * @param String $routeName Route's name corresponding to the request
     * @param Array $params Paramters of the request
     * @param Boolean $forceNesting Allows to force a request to be send inside the corresponding route controller (May produce an infinit loop)
     * @throws WFERequestException
     */
    function __construct($method = null, $routeName = null, $params = null, $forceNesting = false) {
        
        if(is_string($method)) {
            $this->method = $method;
        }
        else {
            $this->method = $_SERVER['REQUEST_METHOD'];   
        }
        
        if(is_string($routeName)) {
            $this->route = WFERoute::get($routeName);
        }
        else {
            switch ($this->method) {
                case 'POST':
                    self::initPOST();
                    break;
                case 'GET':
                    self::initGET();
                    break;
                default:
                    break;
            }
        }
        
        if( ! $forceNesting && $this->route != null && $this->route->getName() == WFERouter::getCurrentRoute()) {
            throw new WFERequestException('You cannot request a route inside the controller\'s action linked to this route (avoid infinit loop)');
        }
        
        if(is_array($params)) {
            $this->params = $params;
        }
    }
    
    /**
     * Return route of the request
     * @return String
     */
    public function getRoute() {
        return $this->route;
    }
    
    /**
     * Return the Method of the request
     * @return String
     */
    public function getMethod() {
        return $this->method;
    }
    
    /**
     * Return true if the request is an ajax request
     * @return Boolean
     */
    public static function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Return URI segments of the request
     * @return String
     */
    public static function getURI() {
        return str_replace('/'.RELATIVE_ROOT, '', $_SERVER['REQUEST_URI']);
    }
    
    /**
     * Return an associative array that contains parameters of the request
     * @return Array
     */
    public function getArguments() {
        
        if(is_array($this->params)) {
            return $this->params;
        }
        
        $uri = $this->getURI();
        $args = array();
        
        $pattern_segs = explode('/', $this->route->getPath());
        $path_segs = explode('/', $uri);
        
        for($i = 0 ; $i < sizeof($pattern_segs) ; $i++) {
            
            if(substr($pattern_segs[$i], 0, 1) == '{') {
                
                if(isset($path_segs[$i])) {
                    
                    if(strpos($pattern_segs[$i], ':path') !== false) {
                
                        $param = '';
                        $size = sizeof($path_segs);
                        for($i2 = $i ; $i2 <  $size; $i2++) {
                            $param .= '/' . $path_segs[$i2];
                            unset($path_segs[$i2]);
                        }
                        $param = substr( $param, 1 );
                    }
                    else {
                        $param = $path_segs[$i];
                    }
                    $args[str_replace(array('{', '}', ':path'), array('', '', ''), $pattern_segs[$i])]  = $param;
                }
            }
        }
        
        return $args;
    }
    
    /**
     * Initialize reqeust for POST method
     * @throws WFESystemErrorException
     */
    private function initPOST() {
        if( ! isset($_POST['routeName'])) {
            throw new WFESystemErrorException('POST value "routeName" does not exists');
        }
        $this->route = WFERoute::get( $_GET['routeName'] );
        
        if(isset($_POST['routeParams'])) {
            if(is_array($_POST['routeParams'])) {
                $this->params = $_POST['routeParams'];
            }
        }
    }
    
    /**
     * Initiliaze request for GET method
     */
    private function initGET() {
        if( ! isset($_GET['routeName']) ) {

            $route = WFERoute::getByPath( $this->getURI() );

        }
        else {
            $route = WFERoute::get( $_GET['routeName'] );
        }
        $this->route = $route;
        
        if(isset($_GET['routeParams'])) {
            if(is_array($_GET['routeParams'])) {
                $this->params = $_GET['routeParams'];
            }
        }
    }
    
    
}
