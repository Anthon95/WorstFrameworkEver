<?php

namespace app\controllers;

use core\router\WFERouter;
use core\WFEController;
use core\WFERequest;
use core\WFEResponse;
use core\WFETemplate;

class Main extends WFEController {
    
    public function home() {
        
        $response = WFERouter::run(new WFERequest('GET', 'do', array('parameter' => 'BLA')));
        $response = new WFEResponse();
        $response->setContent(WFETemplate::render());
        
        return $response;
    }
    
    public function doSomething($arg) {
        
        $response = new WFEResponse();
        
        $response->setContent( WFETemplate::render(array('parameter' => $arg)) );
        
        return $response;
    }
    
    public function getMenu() {
        
    }
}
