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
        
        $response = new WFEResponse();
        
//        $menu = WFETemplate->render('menu');
//        
//        $response->setContent('<p>Hello !</p>');
//        $response->setFormat('text/html');
        
        return $response;
    }
    
    public function getMenu() {
        
    }
}
