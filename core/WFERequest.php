<?php

namespace core;

/*
 * Request class
 */

class WFERequest {
    
    /**
     * Current route of the request
     * @var String
     */
    private static $routeName;
    
    /**
     * Return route of the request
     * @return String
     */
    public static function getRouteName() {
        return self::$routeName;
    }
    
    
    
}
