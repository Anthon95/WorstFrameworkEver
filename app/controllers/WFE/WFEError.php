<?php

namespace app\controllers\WFE;

use core\WFEController;
use core\WFEResponse;
use core\WFETemplate;

/*
 * WFE controller for errors
 */
 

class WFEError extends WFEController {
    
    /**
     * Action for error code 404 page not found
     */
    public function WFE404() {

        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        $response = new WFEResponse();
        
        $response->setContent( WFETemplate::render() );
        
        return $response;
    }
    
    public function WFEErrorServer() {
        
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

        $response = new WFEResponse();
        
        $response->setContent( WFETemplate::render() );
        
        return $response;
    }
    
}
