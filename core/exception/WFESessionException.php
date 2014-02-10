<?php

/*
 * Class SessionException
 */

namespace core\exception;

class WFESessionException extends WFEException {
    
    
    public function __construct($message) {
        $trace = $this->getTrace();
        $this->setFile( $trace[0]['file'] );
        $this->setLine( $trace[0]['line'] );
        $this->setMessage('<h1>SessionException</h1>' . $message);
    }
    
}

