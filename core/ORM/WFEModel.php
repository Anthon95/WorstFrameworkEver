<?php

    namespace core\Model;

    use \R;

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

        public function select($id = null){

            // no id in param
            if($id == null){

                $items = R::loadAll($this->table,array());
                return $items;

            }
            // id in param
            else {

                $item = R::load($this->table,$id);
                return $id;

            }

        }

    }