<?php

namespace app\controllers;

use core\router\WFERoute;
use core\router\WFERouter;
use core\WFEController;
use core\WFERequest;
use core\WFEResponse;
use core\WFETemplate;

class Main extends WFEController {
    
    public function home() {
        
        $response = WFERouter::run(new WFERequest('GET', 'do', array('do' => 'BLA')));
        
        return new WFEResponse();
    }
    
    public function doSomething($arg) {
        
        $response = new WFEResponse();
        
        $response->setContent( WFETemplate::render(array('message' => $arg)) );
        
        return $response;
    }
    
    public function getMenu() {
        
    }
}
