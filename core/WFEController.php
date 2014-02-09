<?php

namespace core;

/*
 * Base controller
 */

abstract class WFEController {
    
    /**
     *
     * @var Gump Gump instance of this controller
     */
    private $gump;
    
    /**
     * 
     * @return Gump Return Gump instance
     */
    public function getGUMP (){

        if($this->gump == null){
            WFELoader::load("core/libs/GUMP/gump.class.php");
            $this->gump = new \GUMP();
        }
        
        return $this->gump;

    }
    
}
