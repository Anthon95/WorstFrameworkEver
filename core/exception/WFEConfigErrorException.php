<?php

/*
 * Class WFEConfigErrorException
 */

namespace core\exception;

class WFEConfigErrorException extends WFEException {
    
    
    public function __construct($message) {
        $trace = $this->getTrace();
        $this->setFile( $trace[0]['file'] );
        $this->setLine( $trace[0]['line'] );
        $this->setMessage('<h1>ConfigErrorException</h1>' . $message);
    }
    
}

