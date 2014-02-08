<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WFEtempl
 *
 * @author SUPINTERNET
 */

namespace core;

class WFETemplate {

    public static function render($tpl = null) {

        $smarty->display('/app/templates/Main/doSomething.tpl');

        if(!file_exists($tpl)) {
        }
        else {
            require_once $tpl;
        }

        if($tpl == $myaction){
            $smarty->display('/app/templates/'.$controller.'/'.$tpl.'.tpl');
        }
        else {
        }

    }
}
