<?php

    namespace core\ORM;

    use core\WFEConfig;
    use core\WFELoader;
    use \R;

    class WFEDb {

        /**
         *
         */


        public static function connect(){

            WFELoader::load("core/libs/redbean/rb.php");
            WFELoader::load("core/libs/QueryBuilder/QueryBuilder.php");
            $host = WFEConfig::get('db::host');
            $name = WFEConfig::get('db::name');
            $user = WFEConfig::get('db::user');
            $password = WFEConfig::get('db::password');
            $dbEnabled = WFEConfig::get('db::enabled');
            if($dbEnabled == true){
                R::setup('mysql:host='.$host.'; dbname='.$name,$user,$password);
            }

        }

        public static function disconnect(){

            R::close();

        }

    }