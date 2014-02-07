<?php

/*
 * 
 */

namespace core\exception;

use Exception;

class WFEException extends Exception {
    public function __construct() {
        
    }
    
    public function setMessage($message) {
        $this->message = $message;
        $this->message .= '<br>in : ' . $this->file . ' at line ' . $this->line;
    }
    
    protected function setFile($file) {
        $this->file = $file;
        return $this;
    }
    protected function setLine($line) {
        $this->line = $line;
        return $this;
    }
}
