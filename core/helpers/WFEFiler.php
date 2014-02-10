<?php

namespace app\core\helpers;

class WFEFiler {

    protected static $root = APP;
    public static $current = APP;

    public static function test() {
        if (!file_exists(self::$current)) {
            $test = false;
        } else {
            $test = true;
        }

        return $test;
    }

    /*   Generic Methods  */

    public static function delete($obj = "") {

        if (!$test = self::test()) {
            return false;
        }

        if (self::$current == self::$root) {
            return false;
        }

        if ($obj == "") {
            $obj = self::$current;
        }

        if (is_dir($obj)) {

            $source = opendir($obj);
            while ($res = readdir($source)) {
                if ($res == "." || $res == "..") {
                    continue;
                }

                if (is_dir($obj . "/" . $res)) {
                    self::delete($obj . "/" . $res);

                    if (file_exists($obj . "/" . $res)) {
                        rmdir($obj . "/" . $res);
                    }
                }
                elseif (is_file($obj . '/' . $res)) {
                    unlink($obj . "/" . $res);
                }
            }
            closedir($source);
        } else {
            unlink($obj);
        }

        if (file_exists($obj)) {
            rmdir($obj);
        }

        return true;
    }
    

    public static function move($new = "") {

        if (!$test = self::test()) {
            return false;
        }

        if (self::$current == self::$root) {
            return false;
        }

        $nom = basename(self::$current);
        if ($new == "") {
            if (!file_exists(self::$root . "/" . $nom)) {
                rename(self::$current, self::$root . "/" . $nom);
            } else {
                return false;
            }
        }
        else {
            if (!file_exists(self::$root . "/" . $new . "/" . $nom)) {
                rename(self::$current, self::$root . "/" . $new . "/" . $nom);
            } else {
                return false;
            }
        }


        return true;
    }

    public static function last_err() {
        return error_get_last();
    }

    public static function cleanOut($obj = "") {

        if (!$test = self::test()) {
            return false;
        }

        if (!is_dir(self::$current)) {
            return false;
        }

        if (self::$current == self::$root) {
            return false;
        }

        if ($obj == "") {
            $obj = self::$current;
        }

        if (is_dir($obj)) {

            $source = opendir($obj);
            while ($res = readdir($source)) {
                if ($res == "." || $res == "..") {
                    continue;
                }

                if (is_dir($obj . "/" . $res)) {
                    self::cleanOut($obj . "/" . $res);

                    if (file_exists($obj . "/" . $res)) {
                        rmdir($obj . "/" . $res);
                    }
                }
                elseif (is_file($obj . '/' . $res)) {
                    unlink($obj . "/" . $res);
                }
            }
            closedir($source);
        } else {
            return false;
        }

        return true;
    }

    public static function createDir($nom) {

        if (!$test = self::test()) {
            return false;
        }

        if (is_dir(self::$current)) {
            if (!file_exists(self::$current . "/" . $nom)) {
                mkdir(self::$current . "/" . $nom);
            } else {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    public static function upload() {

        if (!$test = $this->test()) {
            return false;
        }

        if (isset($_FILES) == true) {
            if (is_dir($this->current)) {
                if (!file_exists($this->current . "/" . $_FILES['upload']['name'])) {
                    if (!move_uploaded_file($_FILES['upload']['tmp_name'], $this->current . "/" . $_FILES['upload']['name'])) {
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public static function content() {

        if (!$test = self::test()) {
            return false;
        }

        if (is_file(self::$current)) {
            $res = file(self::$current);
        } else {
            return false;
        }

        return $res;
    }

    public static function changeContent($contenu) {

        if (!$test = self::test()) {
            return false;
        }

        if (is_file(self::$current)) {
            if (is_writable(self::$current)) {
                if (!$handle = fopen(self::$current, 'w')) {
                    return false;
                } else {
                    if (fwrite($handle, $contenu) === false) {
                        return false;
                    } else {
                        return true;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    public static function addContent($contenu) {

        if (!$test = self::test()) {
            return false;
        }

        if (is_file(self::$current)) {
            if (is_writable(self::$current)) {
                if (!$handle = fopen(self::$current, 'a')) {
                    return false;
                } else {
                    if (fwrite($handle, $contenu) === false) {
                        return false;
                    } else {
                        return true;
                    }
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    public static function download() {

        if (!$test = self::test()) {
            return false;
        }

        function content_type($filename) {

            $mime_types = array(
                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',
                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',
                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',
                // audio/video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',
                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',
                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',
                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet');
            $ext = pathinfo($filename);
            if (array_key_exists($ext['extension'], $mime_types)) {
                return $mime_types[$ext['extension']];
            } else {
                return 'application/octet-stream';
            }
        }

        if (is_file(self::$current)) {
            $name = basename(self::$current);
            $poids = filesize(self::$current);
            header('Content-Type:' . content_type($name));
            header('Content-Length:' . $poids);
            header('Content-disposition: attachment; filename=' . $name);
            $handle = fopen(self::$current, 'r');
            fpassthru($handle);
            exit();
        } else {
            return false;
        }
    }

    public static function setCurrent($current = "") {
        if ($current == "") {
            self::$current = self::$root;
        } else {
            self::$current = self::$root . "/" . $current;
        }
    }

    public static function getCurrent() {
        return self::$current;
    }

    public static function getRoot() {
        return self::$root;
    }

}
