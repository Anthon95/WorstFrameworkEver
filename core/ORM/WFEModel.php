<?php

    namespace core\Model;

    class WFEModel {

        /**
         *
         */
        private $table;

        public function insert($params = array()){

            $item = R::dispense($this->table);

            foreach($params as $key => $value){

                $item->$key = $value;

            }

            $id = R::store($item);

            return $id;

        }

        public function delete($id){

            $item = R::load($this->table, $id);
            R::trash($item);

        }

    }