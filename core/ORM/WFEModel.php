<?php

    namespace core\Model;

    class WFEModel {

        /**
         *
         */
        private $table;

        public function insertItem($params = array()){

            $table = R::dispense($this->table);

            foreach($params as $key => $value){

                $table->$key = $value;

            }

            $id = R::store($table);

            return $id;

        }

        public function deleteItem($params = array()){

            if(isset($params['id'])){

                $item = R::load($this->table, $params['id']);
                R::trash($item);

            }

        }

    }