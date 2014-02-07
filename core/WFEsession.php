<?php

namespace core\router;

class WFEsession {

    public static function init() {

        session_start();

        if (!isset($_SESSION['userdata']) || !is_array($_SESSION['userdata'])) {
            $_SESSION['userdata'] = array();
        }
        if (!isset($_SESSION['flashdata']) || !is_array($_SESSION['flashdata'])) {
            $_SESSION['flashdata'] = array();
        }
    }

    public static function end_session() {

        foreach ($_SESSION['flashdata'] as $name => $data) {
            unset($_SESSION['flashdata'][$name]);
        }


        set_flashdata(array('last_page_uri' => get_current_uri()));
    }

    public static function get_flashdata($data) {

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

   
    

    public static function get_userdata($data) {

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

    public static function isset_flashdata($data_name) {

        return isset($_SESSION['flashdata'][$data_name]);
    }


    public static function isset_session_data($data_name, $auto_destroy = false) {

        $isset = isset($_SESSION[$data_name]);
        if ($isset && $auto_destroy) {
            unset($_SESSION[$data_name]);
        }
        return $isset;
    }


    public static function isset_userdata($data_name) {

        return isset($_SESSION['userdata'][$data_name]);
    }



    public static function kill_userdata($data_names = null) {

        if ($data_names == null) {
            foreach ($_SESSION['userdata'] as $key => $value) {
                
            }
            unset($_SESSION['userdata'][$key]);
        }

        else {
            foreach ($data_names as $name) {
                unset($_SESSION['userdata'][$name]);
            }
        }
    }


    public static function set_flashdata($data) {

        foreach ($data as $name => $value) {
            $_SESSION['flashdata'][$name] = $value;
        }
    }



    public static function set_session_data($data) {

        foreach ($data as $name => $value) {
            $_SESSION[$name] = $value;
        }
    }



    function set_userdata($data) {

        foreach ($data as $name => $value) {
            $_SESSION['userdata'][$name] = $value;
        }
    }

}

