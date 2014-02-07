<?php

namespace core;

/*
 * Class WFEResponse
 */

class WFEResponse {

    
    protected $content;
    protected $format;
    
    public function __construct() {
        
        if ($format == '.json' || '.html')
        {
            
        }
    }
    
    public function send() {

      
    }

    public function getContent() {

      return $this->content;
    }

    public function setContent($content) {
        
        $this->setContent = $content;
    }

    public function getFormat() {

         return $this->format;
    }

    public function setFormat() {
        
         $this->setFormat = $format;
    }

}
