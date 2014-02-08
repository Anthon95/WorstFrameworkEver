<?php

namespace app\controllers\WFE;

use core\WFEController;
use core\WFELoader;
use core\WFEResponse;

/*
 * Controller WFEPublic : allows to load public content (assets)
 */

class WFEPublic extends WFEController {
    
    public function css($css) {
        $response = new WFEResponse();
        $response->setContent(WFELoader::content('public/css/' . $css));
        $response->setFormat('text/css');
        
        return $response;
    }
    
    public function js($js) {
        $response = new WFEResponse();
        $response->setContent(WFELoader::content('public/js/' . $js));
        $response->setFormat('text/css');
    }
    
    public function img($img) {
        $response = new WFEResponse();
        $response->setContent(WFELoader::content('public/img/' . $img));
        exit(var_dump(pathinfo($img, PATHINFO_EXTENSION)));
        
        $response->setFormat('image/' . pathinfo($img, PATHINFO_EXTENSION));
    }
    
}
