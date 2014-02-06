<?php

namespace core\exception;

use core\exception\WFEException;

/*
 * Class ClassNotFoundException
 */

class WFEClassNotFoundException extends WFEException {
    
    function __construct($in, $classname) {
        parent::__construct($in);
        $classname_seg = explode('/', $classname);
        $class = $classname_seg[count($classname_seg)-1];
        
        $trace = $this->getTrace();
        $this->setFile( $trace[1]['file'] );
        $this->setLine( $trace[1]['line'] );
        
        $this->setMessage('<h1>ClassNotFoundedException</h1>Class not founded : ' . $class);
    }
    
}
