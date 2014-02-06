<?php

use core\WFEConfig;
use core\router\WFERoute;

/**
 * ROUTES
 */
WFEConfig::add(array(
    
    'defaultRoutetName' => 'home',
    'routes' => array(
    
        'home' => new WFERoute('/', 'Main', 'home'),
        'blog' => new WFERoute('/blog', 'Main', 'blogHome'),
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