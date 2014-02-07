<?php

namespace core\exception;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class WFERequestException extends WFEException {
    
    public function __construct($message) {
        $trace = $this->getTrace();
        $this->setFile( $trace[0]['file'] );
        $this->setLine( $trace[0]['line'] );
        $this->setMessage('<h1>RequestException</h1>' . $message);
    }
}
