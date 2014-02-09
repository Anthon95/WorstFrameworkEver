<?php

namespace app\controllers\WFE;

use core\WFEController;
use core\WFEResponse;

/*
 * WFE controller for errors
 */
 

class WFEError extends WFEController {
    
    /**
     * Action for error code 404 page not found
     */
    public function WFE404() {

        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        return new WFEResponse();
    }
    
    public function WFEErrorServer() {
        
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);

        return new WFEResponse();
    }
    
}
