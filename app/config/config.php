<?php

use core\WFEConfig;
use core\router\WFERoute;

/**
 * ROUTES
 */
WFEConfig::add(array(
    'routes' => array(
    
        'home' => new WFERoute('/', 'Main', 'home'),
        'blog' => new WFERoute('/blog', 'Main', 'home'),
    ),
));

/**
 * DATABASE
 */
WFEConfig::add(array(
    'db' => array(

        'dbHost' => "localhost",
        'dbName' => "wfe",
        'dbUser' => "root",
        'dbPassword' => ""
    ),
));