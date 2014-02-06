<?php

/*
 * Class DefinitionException
 */

namespace core\exception;

class WFEDefinitionException extends WFEException {
    
    
    public function __construct($message) {
        $trace = $this->getTrace();
        $this->setFile( $trace[0]['file'] );
        $this->setLine( $trace[0]['line'] );
        $this->setMessage('<h1>DefinitionException</h1>' . $message);
    }
    
}

