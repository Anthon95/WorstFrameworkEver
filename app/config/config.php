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
        
        // system routes /////////////////////////////////////////////////////////////////////////////////
        
        new WFERoute('WFE404', null, 'WFE/WFEError', 'WFE404'),
        new WFERoute('WFE500', null, 'WFE/WFEError', 'WFEErrorServer'),
        
        new WFERoute('WFEMain', null, 'WFE/WFE', 'main'),
        
        new WFERoute('WFEPublicCss', '/public/css/{css:path}', 'WFE/WFEPublic', 'css'),
        new WFERoute('WFEPublicJs', '/public/js/{js:path}', 'WFE/WFEPublic', 'js'),
        new WFERoute('WFEPublicImg', '/public/img/{img:path}', 'WFE/WFEPublic', 'img'),
        
        // application routes ///////////////////////////////////////////////////////////////////////////
        
        new WFERoute('home', '/', 'Main', 'home'),
        new WFERoute('home_2', '/{id}/home/{id2}', 'Main', 'home'),
        new WFERoute('user_get', '/user/{id}', 'User', 'get'),
        
        new WFERoute('do', '/something/{do}', 'Main', 'doSomething'),
    ),
    
    /**
    * DATABASE
    */
    
    'db' => array(

        'host' => "localhost",
        'name' => "wfe",
        'user' => "root",
        'password' => "",
        'enabled' => true
    ),
    
    /**
     * ENVIRONMENT
     */
    
    'env' => 'dev', // whether dev or prod
    
    /**
     * CONTROLLERS
     */
    
    'publicController' => 'WFE/WFEPublic',
    'errorController' => 'WFE/WFEError',
    
));