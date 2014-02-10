<?php

namespace core\libs\smartyPlugins;

use core\router\WFERoute;
use core\router\WFERouter;

class WFESmartyPlugins {

    public static function link($array, $smarty) {

        $route = WFERoute::get($array['route']);
        $url = $route->injectParams($array['params']);
        return $url;
    }
    
    public static function getCurrentRoutePath($array, $smarty) {
        $route = WFERouter::getCurrentRequest()->getRoute();
        return $route->injectParams(WFERouter::getCurrentRequest()->getArguments());
    }

}
