<?php

namespace app\core\helpers;

class WFESecurity {

    public static function htmlSpecialChars($value) {

        return htmlspecialchars($value);
    }

    public static function htmlEntities($value) {

        return htmlentities($value);
    }

    public static function stripTags($value) {

        return strip_tags($value);
    }

    public static function xssClean($value) {
        if (!is_array($value)) {
            return htmLawed($value, array('safe' => 1, 'balanced' => 0));
        }

        foreach ($value as $k => $v) {
            $value[$k] = $this->xss_clean($v);
        }

        return $value;
    }

}
