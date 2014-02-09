<?php

namespace core;

/*
 * Base controller
 */

abstract class WFEController {

    private $gump;
    
    public function getGUMP (){

        if($this->gump == null){
            WFELoader::load("core/libs/GUMP/gump.class.php");
            $this->gump = new \GUMP();
        }

    }
    
}
