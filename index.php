<?php

use core\exception\WFEDefinitionException;
use core\exception\WFEException;
use core\router\WFERouter;
use core\WFEAutoload;
use core\WFEConfig;
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

// set environment spec
if(WFEConfig::get('env') == 'dev') {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
}
elseif(WFEConfig::get('env') == 'prod') {
    error_reporting(0);
}
else {
    throw new WFEDefinitionException('Config settings env is not set properly (must be dev or prod)');
}

// init request data
$request = new WFERequest();

// Routes request and get response
WFERouter::run($request);

