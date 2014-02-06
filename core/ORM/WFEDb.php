<?php

    namespace core\ORM;

    class WFEDb {

        /**
         *
         */


        private function __construct($host,$name,$user,$password){

            R::setup('mysql:host='.$host.'; dbname='.$name,$user,$password);

        }

    }