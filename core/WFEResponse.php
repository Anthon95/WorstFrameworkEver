<?php

namespace core;

/*
 * Class WFEResponse
 */

use core\ORM\WFEDb;

class WFEResponse {

    protected $content = '';
    protected $format = 'text/html';

    public function __construct() {

        
    }

    public function send() {

        WFEDb::disconnect();
        header('Content-type: ' . $this->getFormat());
        exit($this->getContent());
    }

    public function getContent() {

        return $this->content;
    }

    public function setContent($content) {

        $this->content = $content;
    }

    public function getFormat() {

        return $this->format;
    }

    public function setFormat($format) {

        $this->format = $format;
    }

}
