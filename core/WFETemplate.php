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

use core\exception\WFETemplateException;
use core\router\WFERouter;
use Smarty;

class WFETemplate {

    private static $smarty;
    private static $init = false;

    CONST TPL_PATH = '/app/templates/';

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

        $smarty->display('/app/templates/Main/doSomething.tpl');

        return $output;
    }

    private static function setParams($params = array()) {

        foreach ($params as $key => $value) {

            self::$smarty->assign($key, $value);
        }
    }

    private static function defaultTemplate() {

        return WFERouter::getCurrentController() . '/' . WFERouter::getCurrentAction() . '.tpl';
    }

    private static function registerPluginsSmarty() {

        self::$smarty->registerPlugin("function", "link", array("core\libs\smartyPlugins\WFESmartyPlugins", "link"));
    }

}