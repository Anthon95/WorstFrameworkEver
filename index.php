<?php

use core\Autoload;
use core\exception\WFEException;
//use core\Request;


// include core files
require_once('core/Autoload.php');


// set exception handler
set_exception_handler(function(WFEException $e) {
    echo $e->getMessage();
});

// Register autoload
Autoload::register(__NAMESPACE__);

exit(Request::getRouteName());