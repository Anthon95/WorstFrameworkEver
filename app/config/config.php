<?php

use core\WFEConfig;
use core\router\WFERoute;

/**
 * ROUTES
 */
WFEConfig::add(array(
    
    'defaultRoutetName' => 'home',
    'routes' => array(
        
        // system routes
        'WFE404' => new WFERoute('', 'WFE/WFEError', 'WFE404'),
        
        // application routes
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

/**
 * GLOBALE
 */
WFEConfig::add(array(
    'env' => 'dev', // whether dev or prod
));