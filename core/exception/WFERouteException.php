<?php

/*
 * Class RouteException
 */

namespace core\exception;

class WFERouteException extends WFEException {
    
    
    public function __construct($message) {
        $trace = $this->getTrace();
        $this->setFile( $trace[0]['file'] );
        $this->setLine( $trace[0]['line'] );
        $this->setMessage('<h1>RouteException</h1>' . $message);
    }
    
}

