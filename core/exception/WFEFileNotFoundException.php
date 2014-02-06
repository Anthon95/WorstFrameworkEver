<?php

namespace core\exception;

use core\exception\WFEException;

/*
 * Class ClassNotFoundException
 */

class WFEFileNotFoundException extends WFEException {
    
    function __construct($filename) {
        parent::__construct();
        
        $trace = $this->getTrace();  
        $this->setFile( $trace[0]['file'] );
        $this->setLine( $trace[0]['line'] );
        
        $this->setMessage('<h1>FileNotFoundException</h1>File not found : ' . $filename);
    }
    
}
