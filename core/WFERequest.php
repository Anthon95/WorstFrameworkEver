<?php

namespace core;

use core\exception\WFESystemErrorException;


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
    
    function __construct() {
        
        $this->method = $_SERVER['REQUEST_METHOD'];
        
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
            $routeName = WFEConfig::get('defaultRoutetName');
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
