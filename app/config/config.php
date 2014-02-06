<?php

use core\WFEConfig;
use core\router\WFERoute;

/**
 * ROUTES
 */
Config::add(array(
    'routes' => array(
    
        'home' => new Route('/', 'Main', 'home'),
        'blog' => new Route('/blog', 'Main', 'home'),
    ),
));

/**
 * DATABASE
 */
Config::add(array(
    'db' => array(
    
        
    ),
));