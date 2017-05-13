<?php
    class sql{
        # variables
        private $pdo = null, $host, $db, $user, $pass, $query, $data, $connect_file;

        # construct
        public function __construct($file_path = ''){
            # file path
            if($file_path){
                $this->connect_file = getenv("DOCUMENT_ROOT")."/{$file_path}";
            }else{
                $this->connect_file = getenv("DOCUMENT_ROOT")."/service/connect.php";
            }

            # include
            include $this->connect_file;

            # connect
            try{
                $this->pdo = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
            }catch(PDOException $e){
                exit("Couldn't connect to db");
            }

            # set charset
            self::set_names();
            self::set_charset();
        }

        # set character set
        public function set_charset($charset = "utf8_unicode_ci")
        {
            $this->F("SET CHARACTER SET {$charset}", false)->R();
        }

        # set names
        public function set_names($names = "utf8")
        {
            $this->F("SET NAMES {$names}", false)->R();
        }

        # select
        public function s($columns, $table){
            $this->query = "SELECT {$columns} FROM {$table}";
            $this->data  = true;

            return $this;
        }

        # update
        public function u($table, $set){
            $this->query = "UPDATE {$table} SET {$set}";
            $this->data  = false;

            return $this;
        }

        # delete
        public function d($table){
            $this->query = "DELETE FROM {$table}";
            $this->data  = false;

            return $this;
        }

        # insert
        public function i($table, $columns, $values){
            $this->query = "INSERT INTO {$table}({$columns}) values({$values})";
        }

        # where
        public function w($where){
            $this->query = $this->query. " WHERE {$where}";

            return $this;
        }

        # order by
        public function o($order, $type = 'ASC'){
            $this->query = $this->query. " ORDER BY {$order} {$type}";

            return $this;
        }

        # limit
        public function l($limit){
            $this->query = $this->query. " LIMIT {$limit}";

            return $limit;
        }

        # like
        public function lk($like){
            $this->query = $this->query. " LIKE {$like}";

            return $this;
        }

        # between
        public function b($b1, $b2){
            $this->query = $this->query. " BETWEEN {$b1} AND {$b2}";

            return $this;
        }

        # group by
        public function g($group){
            $this->query = $this->query. " GROUP BY {$group}";

            return $this;
        }

        # free query
        public function f($q, $d = false){
            $this->query = $q;
            $this->data  = $d;

            return $this;
        }

        # run
        public function r($num_rows = false){
            if($num_rows){
                # execute query
                $Query = $this->pdo->prepare($this->query);
                $Query->execute();

                # return number of rows
                return $Query->rowCount();
            }else{
                if($this->data){
                    # execute
                    $Query = $this->pdo->prepare($this->query);
                    $Query->execute();
                    $array = array();

                    # fetch rows
                    while($row = $Query->fetch(PDO::FETCH_ASSOC))
                        $array[] = $row;

                    # return
                    return $array;
                }else{
                    $Query  = $this->pdo->prepare($this->query);
                    $result = $Query->execute();

                    if($result){
                        return array("status"=> "OK", "insert_id"=> $this->pdo->lastInsertId(), "affected_rows"=> $Query->rowCount());
                    }else{
                        return array("status"=> "ERROR", "insert_id"=> "undefined", "affected_rows"=> "undefined");
                    }
                }
            }
        }

    }
?>
