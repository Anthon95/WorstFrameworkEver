<?php

    namespace core\ORM;

    use core\WFELoader;
    use \R;

    class WFEDb {

        /**
         *
         */


        function __construct($host,$name,$user,$password){

            WFELoader::load("core/libs/redbean/rb.php");
            R::setup('mysql:host='.$host.'; dbname='.$name,$user,$password);

        }

        public function disconnect(){

            R::close();

        }

    }