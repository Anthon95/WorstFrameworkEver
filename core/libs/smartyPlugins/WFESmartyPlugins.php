<?php

namespace core\libs\smartyPlugins;

use core\router\WFERoute;

class WFESmartyPlugins {

    public static function link($array, $smarty){

        $route = WFERoute::get($array['route']);
        $url = $route -> injectParams($array['params']);
        return $url;
    }

}