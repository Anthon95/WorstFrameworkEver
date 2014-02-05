<?php

use core\Autoload;
use core\exception\WFEException;
use core\Request;


// include core files
require_once('core/Autoload.php');
require_once('core/exception/WFEException.php');
require_once('core/exception/ClassNotFoundException.php');

// set exception handler
set_exception_handler(function(WFEException $e) {
    echo $e->getMessage();
});

// Register autoload
Autoload::register(__NAMESPACE__);

exit(Request::getRouteName());