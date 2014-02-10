<?php

namespace core;

use core\exception\WFESessionException;
use core\router\WFERouter;

/**
 * Session class
 */
class WFEsession {

    /**
     * Initialize Session
     */
    public static function init() {

        session_start();

        if (!isset($_SESSION['session']) || !is_array($_SESSION['session'])) {
            $_SESSION['session'] = array();
        }

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

        WFEsession::setFlashdata(array('last_page_uri' => WFERouter::getCurrentRequest()->getURI()));
    }

    /**
     * Return a user session data
     * @param mixed $data Array or string of $data name
     * @return Array Associative array of names and values
     * @throws WFESessionException If $data does not exists in session
     */
    public static function getUserdata($data) {

        return self::getData('userdata', $data);
    }

    /**
     * Return true if a user session data exists
     * @param String $data Data name
     * @return boolean
     */
    public static function issetUserdata($data) {

        return self::issetData('userdata', $data);
    }

    /**
     * Add data to the user session data
     * @param Array $data Associative array of names, values to add to the user session data
     */
    public static function setUserdata($data) {

        self::setData('userdata', $data);
    }

    /**
     * Removes data from the user session data
     * @param Array $data Associative array of user session data to remove, if null remove all data !
     */
    public static function killUserdata($data = null) {

        self::killData('userdata', $data);
    }

    /**
     * Return a flash session data
     * @param mixed $data Array or string of $data name
     * @return Array Associative array of names and values
     * @throws WFESessionException If $data does not exists in session
     */
    public static function getFlashdata($data) {

        return self::getData('flashdata', $data);
    }

    /**
     * Return true if a flash session data exists
     * @param String $data Data name
     * @return boolean
     */
    public static function issetFlashdata($data) {

        return self::issetData('flashdata', $data);
    }

    /**
     * Add data to the flash session data
     * @param Array $data Associative array of names, values to add to the user session data
     */
    public static function setFlashdata($data) {

        self::setData('flashdata', $data);
    }

    /**
     * Removes data from the flash session data
     * @param Array $data Associative array of user session data to remove, if null remove all data !
     */
    public static function killFlashdata($data = null) {

        self::killData('flashdata', $data);
    }

    /**
     * Return a session data
     * @param mixed $data Array or string of $data name
     * @return Array Associative array of names and values
     * @throws WFESessionException If $data does not exists in session
     */
    public static function getSessiondata($data) {

        return self::getData('session', $data);
    }

    /**
     * Return true if a session data exists
     * @param String $data Data name
     * @return boolean
     */
    public static function issetSessionData($data) {

        return self::issetData('session', $data);
    }

    /**
     * Add data to the session data
     * @param Array $data Associative array of names, values to add to the user session data
     */
    public static function setSessionData($data) {

        self::setData('session', $data);
    }

    /**
     * Removes data from the session data
     * @param Array $data Associative array of user session data to remove, if null remove all data !
     */
    public static function killSessiondata($data = null) {

        self::killData('session', $data);
    }

    private static function getData($in, $data) {
        $return = array();

        if (is_string($data)) {
            if (self::issetData($in, $data)) {
                $return = $_SESSION[$in][$data];
            } else {
                throw new WFESessionException('Session data : ' . $data . ' cannot be found in ' . $in);
            }
        } else {
            foreach ($data as $name) {
                if (self::issetData($in, $data)) {
                    $return[$name] = $_SESSION[$in][$name];
                } else {
                    throw new WFESessionException('Session data : ' . $name . ' cannot be found in ' . $in);
                }
            }
        }

        return $return;
    }

    private static function setData($in, $data) {
        foreach ($data as $name => $value) {
            $_SESSION[$in][$name] = $value;
        }
    }

    private static function issetData($in, $data) {
        return isset($_SESSION[$in][$data]);
    }

    private static function killData($in, $data = null) {
        if ($data == null) {
            foreach ($_SESSION[$in] as $key => $value) {
                if (self::issetData($in, $key)) {
                    unset($_SESSION[$in][$key]);
                }
            }
        } else {
            foreach ($data as $name) {
                unset($_SESSION[$in][$name]);
            }
        }
    }

}
