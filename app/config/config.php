<?php

use core\Config;
use core\router\Route;

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

        'dbHost' => "localhost",
        'dbName' => "wfe",
        'dbUser' => "root",
        'dbPassword' => ""
    ),
));