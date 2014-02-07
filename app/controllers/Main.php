<?php

namespace app\controllers;

use core\WFEController;
use core\WFEResponse;

class Main extends WFEController {
    
    public function home() {
        
        \core\router\WFERouter::run(new \core\WFERequest('GET', 'do'));
        
        return new WFEResponse();
    }
    
    public function doSomething() {
        
    }
}
