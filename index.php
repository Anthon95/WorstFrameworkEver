<?php

use core\WFEAutoload;
use core\exception\WFEException;
use core\WFELoader;
use core\WFERequest;
use core\ORM\WFEDb;

// Constante system
define('ROOT', str_replace('\\', '/', __DIR__));

// include core files
require_once('core/WFEAutoload.php');

// set exception handler
set_exception_handler(function(WFEException $e) {
    echo $e->getMessage();
});

// Register autoload
WFEAutoload::register(__NAMESPACE__);

// Load main config
WFELoader::load('app/config.php');

exit(WFERequest::getRouteName());