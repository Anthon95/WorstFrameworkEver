<?php

namespace core;

/*
 * Class WFEResponse
 */

class WFEResponse {
    
    /**
     * Content of the response
     * @var String
     */
    protected $content = '';
    /**
     * MIME format of the response
     * @var String
     */
    protected $format = 'text/html';

    /**
     * Sends the response to the browser and ends the script
     */
    public function send() {

        WFEsession::end();
        
        header('Content-type: ' . $this->getFormat());
        exit($this->getContent());
        
    }
    
    /**
     * Get content of the response
     * @return String
     */
    public function getContent() {

        return $this->content;
    }

    /**
     * Change content of the response
     * @param String $content New content
     */
    public function setContent($content) {

        $this->content = $content;
    }
    
    /**
     * Get format of the response
     * @return String
     */
    public function getFormat() {

        return $this->format;
    }
    
    /**
     * Change format of the response
     * @param String $format New format
     */
    public function setFormat($format) {

        $this->format = $format;
    }

}
