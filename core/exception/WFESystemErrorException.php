<?php

namespace core\exception;

use core\exception\WFEException;

/*
 * Class SystemErrorException
 */

class WFESystemErrorException extends WFEException {
    
    function __construct($message) {
        parent::__construct();
        
        $trace = $this->getTrace();  
        $this->setFile( $trace[0]['file'] );
        $this->setLine( $trace[0]['line'] );
        
        $this->setMessage('<h1>System Error</h1>' . $message);
    }
}
