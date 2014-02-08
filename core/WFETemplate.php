<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WFETemplate
 *
 * @author SUPINTERNET
 */

namespace core;

use core\router\WFERouter;
use \Smarty;

class WFETemplate {

    private static $smarty;
    CONST TPL_PATH = '/app/templates/';

    public static function render($arg1 = null, $arg2 = array()) {

        if(self::$smarty == null){
            self::$smarty = new Smarty();
        }

        if(is_array($arg1)){
            $tpl = self::defaultTemplate();
            self::setParams($arg1);
        }
        elseif(is_string($arg1)){
            $tpl = $arg1;
            self::setParams($arg2);
        }
        else{
            $tpl = self::defaultTemplate();
            self::setParams($arg2);
        }

        $output = self::$smarty->fetch(ROOT . self::TPL_PATH . $tpl);

        return $output;
    }

    private static function setParams($params = array()){

        foreach($params as $key=>$value){

            self::$smarty->assign($key,$value);

        }

    }

    private static function defaultTemplate(){

        return WFERouter::getCurrentController().'/'.WFERouter::getCurrentAction().'.tpl';

    }

}