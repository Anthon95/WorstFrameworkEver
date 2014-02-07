<?php

namespace core;

use core\exception\WFERequestException;
use core\exception\WFESystemErrorException;
use core\router\WFERouter;


/*
 * Request class
 */

class WFERequest {
    
    /**
     * Current route of the request
     * @var String
     */
    private $routeName = null;
    
    /**
     * Method of the request
     * @var String
     */
    private $method = null;
    
    function __construct($method = null, $routeName = null, $forceNesting = false) {WFERouter::getURI();
        
        if(is_string($method)) {
            $this->method = $method;
        }
        else {
            $this->method = $_SERVER['REQUEST_METHOD'];   
        }
        
        if(is_string($routeName)) {
            $this->routeName = $routeName;
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
        
        if( ! $forceNesting && ($this->routeName == WFERouter::getCurrentRoute() && $this->routeName != null)) {
            throw new WFERequestException('You cannot request a route inside the controller\'s action linked to this route (avoid infinit loop)');
        }
    }
    
    /**
     * Return route of the request
     * @return String
     */
    public function getRouteName() {
        return $this->routeName;
    }
    
    public function getMethod() {
        return $this->method;
    }
    
    
    private function initPOST() {
        if( ! isset($_POST['routeName'])) {
            throw new WFESystemErrorException('POST value "routeName" does not exists');
        }
        $this->routeName = $_POST['routeName'];
    }
    
    private function initGET() {
        if( ! isset($_GET['routeName']) ) {
            
            $routeName = null;
        }
        else {
            $routeName = $_GET['routeName'];
        }
        $this->routeName = $routeName;
    }
    
    public static function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
