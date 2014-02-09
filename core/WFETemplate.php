<?php

namespace core;

use core\exception\WFETemplateException;
use core\router\WFERouter;
use Smarty;

/**
 * Templating class
 * Allows to use smarty template inside WFE
 */
class WFETemplate {

    CONST TPL_PATH = '/app/templates/';
    
    /**
     * Smarty instance
     * @var Smarty
     */
    private static $smarty;
    /**
     * Is initialized
     * @var Boolean
     */
    private static $init = false;
    
    /**
     * Initialiaze Templating
     */
    public static function init() {
        if (!self::$init) {
            self::$init = true;

            self::$smarty = new Smarty();
            if (WFEConfig::get("env") == "prod") {
                self::$smarty->debugging = false;
            } else {
                self::$smarty->debugging = true;
            }

            self::$smarty->setTemplateDir(array(
                'templates' => ROOT . self::TPL_PATH,
            ));

            self::$smarty->setCompileDir(ROOT . '/app/cache/smarty/template_c/');
        }

        self::registerPluginsSmarty();
    }
    
    /**
     * Render a smarty template
     * if $arg1 is a string it will render the template $arg1 with parameters $arg2
     * if $arg1 is an array it will load the default template with parameters $arg1
     * 
     * @param mixed $arg1
     * @param Array $arg2
     * @return String Template rendered
     * @throws WFETemplateException If template does not exists
     */
    public static function render($arg1 = null, $arg2 = array()) {

        self::init();

        if (is_array($arg1)) {
            $tpl = self::defaultTemplate();
            self::setParams($arg1);
        } elseif (is_string($arg1)) {
            $tpl = $arg1;
            self::setParams($arg2);
        } else {
            $tpl = self::defaultTemplate();
            self::setParams($arg2);
        }

        if (!WFELoader::fileExists('app/templates/' . $tpl)) {
            throw new WFETemplateException($tpl);
        }

        $output = self::$smarty->fetch($tpl);

        return $output;
    }
    
    /**
     * Assign parameters to smarty
     * @param Array $params
     */
    private static function setParams($params = array()) {

        foreach ($params as $key => $value) {

            self::$smarty->assign($key, $value);
        }
    }
    
    /**
     * Get default template
     * @return String
     */
    private static function defaultTemplate() {
        
        $controller_segs = explode('/', WFERouter::getCurrentController());
        $dir = $controller_segs[sizeof($controller_segs)-1];
       
        return $dir . '/' . WFERouter::getCurrentAction() . '.tpl';
    }
    
    /**
     * Register all smarty plugins for WFE
     */
    private static function registerPluginsSmarty() {

        self::$smarty->registerPlugin("function", "link", array("core\libs\smartyPlugins\WFESmartyPlugins", "link"));
    }

}
