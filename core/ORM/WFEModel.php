<?php

    namespace core\Model;

    use \R;
    use \QueryBuilder;

    abstract class WFEModel {

        /**
         *
         */
        private $table;
        private  $qb;

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

            // no id and no other params
            if($id == null){

                $items = R::loadAll($this->table,array());
                return $items;

            }
            // id and no params
            else {

                $item = R::load($this->table,$id);
                return $item;

            }

        }

        public function convertToBeans($sql){

            $rows = R::getAll($sql);
            $items = R::convertToBeans($this->table,$rows);
            return $items;

        }

        final protected function getQueryBuilder(){

            if($this->qb == null){
                $this->qb = new QueryBuilder();
            }

            return $this->qb;

        }

    }