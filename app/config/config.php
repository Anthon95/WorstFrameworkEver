<?php

use core\WFEConfig;
use core\router\WFERoute;

WFEConfig::add(array(
    
    /**
    * ROUTES
    */
    
    'defaultRoutetName' => 'home',
    '404ErrorRouteName' => 'WFE404',
    'ServerErrorRouteName' => 'WFEErrorServer',
    
    'routes' => array(
        
        // system routes
        new WFERoute('WFE404', null, 'WFE/WFEError', 'WFE404'),
        new WFERoute('WFEErrorServer', null, 'WFE/WFEError', 'WFEErrorServer'),
        
        new WFERoute('WFEPublicCss', '/public/css/:css', 'WFE/WFEPublic', 'css'),
        new WFERoute('WFEPublicJs', '/public/js/:js', 'WFE/WFEPublic', 'js'),
        new WFERoute('WFEPublicImg', '/public/img/:img', 'WFE/WFEPublic', 'img'),
        
        // application routes
        new WFERoute('home', '/', 'Main', 'home'),
        new WFERoute('user_get', '/user/:id', 'User', 'get'),
        
        new WFERoute('do', '/something', 'Main', 'doSomething'),
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