<?php

use core\exception\WFEException;
use core\router\WFERouter;
use core\WFEAutoload;
use core\WFELoader;
use core\WFERequest;

// Constante system
define('ROOT', str_replace('\\', '/', __DIR__));

// include core files
require_once('core/WFEAutoload.php');

// set exception handler
set_exception_handler(function(WFEException $e) {
    exit($e->getMessage());
});

// Register autoload
WFEAutoload::register(__NAMESPACE__);

// Load main config
WFELoader::load('app/config/config.php');

// init request data
$request = new WFERequest();

// Routes request
WFERouter::run($request);