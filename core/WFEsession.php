<?php

namespace core;

/**
 * Session class
 */
class WFEsession {

    /**
     * Initialize Session
     */
    public static function init() {

        session_start();

        if (!isset($_SESSION['userdata']) || !is_array($_SESSION['userdata'])) {
            $_SESSION['userdata'] = array();
        }
        if (!isset($_SESSION['flashdata']) || !is_array($_SESSION['flashdata'])) {
            $_SESSION['flashdata'] = array();
        }
    }
    
    /**
     * End session
     */
    public static function end() {

        foreach ($_SESSION['flashdata'] as $name => $data) {
            unset($_SESSION['flashdata'][$name]);
        }

        WFEsession::setFlashdata(array('last_page_uri' => router\WFERouter::getCurrentRequest()->getURI()));
    }

    /**
     * Get data from userdata
     * @param mixed $data array or string of data to get
     * @return mixed Returns data
     */
    public static function getUserdata($data) {

        $return = array();

        if (is_string($data)) {
            $return = $_SESSION['userdata'][$data];
        } else {
            foreach ($data as $name) {
                $return[$name] = $_SESSION['userdata'][$name];
            }
        }

        return $return;
    }
    
    /**
     * 
     * @param type $data_name
     * @return type
     */
    public static function issetUserdata($data_name) {

        return isset($_SESSION['userdata'][$data_name]);
    }

    public static function setUserdata($data) {

        foreach ($data as $name => $value) {
            $_SESSION['userdata'][$name] = $value;
        }
    }

    public static function killUserdata($data_names = null) {

        if ($data_names == null) {
            foreach ($_SESSION['userdata'] as $key => $value) {
                
            }
            unset($_SESSION['userdata'][$key]);
        } else {
            foreach ($data_names as $name) {
                unset($_SESSION['userdata'][$name]);
            }
        }
    }

    public static function setFlashdata($data) {

        foreach ($data as $name => $value) {
            $_SESSION['flashdata'][$name] = $value;
        }
    }
    
    public static function getFlashdata($data) {

        $return = array();

        if (is_string($data)) {
            $return = $_SESSION['flashdata'][$data];
        } else {
            foreach ($data as $name) {
                $return[$name] = $_SESSION['flashdata'][$name];
            }
        }

        return $return;
    }

    public static function issetFlashdata($data_name) {

        return isset($_SESSION['flashdata'][$data_name]);
    }

    public static function getSessiondata($data) {

        $return = array();

        if (is_string($data)) {
            $return = $_SESSION['session'][$data];
        } else {
            foreach ($data as $name) {
                $return[$name] = $_SESSION['session'][$name];
            }
        }

        return $return;
    }
    
    public static function setSessionData($data) {

        foreach ($data as $name => $value) {
            $_SESSION['session'][$name] = $value;
        }
    }
    
    public static function issetSessionData($data_name, $auto_destroy = false) {

        $isset = isset($_SESSION['session'][$data_name]);
        if ($isset && $auto_destroy) {
            unset($_SESSION['session'][$data_name]);
        }
        return $isset;
    }

}
