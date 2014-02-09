<?php

namespace app\controllers\WFE;

use core\exception\WFEFileNotFoundException;
use core\router\WFERouter;
use core\WFEController;
use core\WFELoader;
use core\WFEResponse;

/*
 * Controller WFEPublic : allows to load public content (assets)
 */

class WFEPublic extends WFEController {
    
    public function css($css) {
        
        $response = new WFEResponse();
        
        try {
            $response->setContent(WFELoader::content('public/css/' . $css));
        } catch (WFEFileNotFoundException $e) {
            WFERouter::run404();
        }
                
        $response->setFormat('text/css');
        
        return $response;
    }
    
    public function js($js) {
        $response = new WFEResponse();
        try {
            $response->setContent(WFELoader::content('public/js/' . $js));
        } catch (WFEFileNotFoundException $e) {
            WFERouter::run404();
        }
        
        $response->setFormat('text/javascript');
        
        return $response;
    }
    
    public function img($img) {
        
        
        $response = new WFEResponse();
        
        try {
            $response->setContent(WFELoader::content('public/img/' . $img));
        } catch (WFEFileNotFoundException $e) {
            WFERouter::run404();
        }
        
        $response->setFormat('image/' . pathinfo($img, PATHINFO_EXTENSION));
        
        return $response;
    }
    
}
