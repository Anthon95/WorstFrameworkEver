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
    
}
