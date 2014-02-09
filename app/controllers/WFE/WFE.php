<?php

namespace app\controllers\WFE;

use core\WFEController;
use core\WFEResponse;
use core\WFETemplate;

/*
 * WFE Main controller
 */

class WFE extends WFEController {
    
    public function main($params) {
        
        $response = new WFEResponse();
        $response->setContent( WFETemplate::render($params) );
        
        return $response;
        
    }
    
}
