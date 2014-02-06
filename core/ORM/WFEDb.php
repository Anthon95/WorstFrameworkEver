<?php

    namespace core\ORM;

    class WFEDb {

        /**
         *
         */


        function __construct($host,$name,$user,$password){

            R::setup('mysql:host='.$host.'; dbname='.$name,$user,$password);

        }

        public function disconnect(){

            R::close();

        }

    }