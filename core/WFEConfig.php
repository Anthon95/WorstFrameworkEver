<?php

namespace core;

use core\exception\WFEConfigErrorException;


/**
 * Config class
 */
class WFEConfig {

    /**
     * @var Array Array containing settings
     */
    private static $config = array();
    
    /**
     * Allows to get a config settings
     * @param String $setting Setting to get
     * @return mixed Setting
     * @throws WFEConfigErrorException
     */
    static public function get($setting) {

        $settingSegs = explode("::", $setting);
        $config = self::$config;

        foreach ($settingSegs as $seg) {

            if (array_key_exists($seg, $config)) {

                $config = $config[$seg];
            } else {
                throw new WFEConfigErrorException('Setting : ' . $setting . ' does not exists');
            }
        }

        return $config;
    }
    
    /**
     * Allows to add settings to the configuration
     * @param Array $param Associative array of settings
     */
    static function add($param = array()) {

        self::$config = array_merge(self::$config, $param);
    }

}
