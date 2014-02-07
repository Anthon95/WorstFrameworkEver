<?php

use core\WFEConfig;
use core\router\WFERoute;

WFEConfig::add(array(
    
    /**
    * ROUTES
    */
    
    'defaultRoutetName' => 'home',
    'routes' => array(
        
        // system routes
        'WFE404' => new WFERoute('', 'WFE/WFEError', 'WFE404'),
        'WFEErrorServer' => new WFERoute('', 'WFE/WFEError', 'WFEErrorServer'),
        'WFEPublicCss' => new WFERoute('/public/css/{css}', 'WFE/WFEPublic', 'css'),
        'WFEPublicJs' => new WFERoute('/public/js/{js}', 'WFE/WFEPublic', 'js'),
        'WFEPublicImg' => new WFERoute('/public/img/{img}', 'WFE/WFEPublic', 'img'),
        
        // application routes
        'home' => new WFERoute('/', 'Main', 'home'),
        'user_get' => new WFERoute('/user/{id}', 'User', 'get'),
        
        'do' => new WFERoute('/something', 'Main', 'doSomething'),
    ),
    
    /**
    * DATABASE
    */
    
    'db' => array(

        'dbHost' => "localhost",
        'dbName' => "wfe",
        'dbUser' => "root",
        'dbPassword' => ""
    ),
    
    /**
     * ENVIRONMENT
     */
    
    'env' => 'dev', // whether dev or prod
    
));