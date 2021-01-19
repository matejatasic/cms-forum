<?php
    class CMS {
        public $host;
        public $username;
        public $password;
        public $dbname;
        public $connection;
        public $statement;
        public $query;
        public $data;
        public $filedata;
        public $user_loged;

        public function __construct() {
            $this->host = 'localhost';
            $this->username = 'root';
            $this->password = '';
            $this->dbname = 'cms';
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->dbname",
            "$this->username", "$this->password");
            session_start();
        }

        //Execute statement
        public function execute_query() {
            $this->statement = $this->connection->prepare($this->query);
            $this->statement->execute($this->data);
        }

        //Return total rows of the executed statement
        public function total_rows() {
            $this->execute_query();
            return $this->statement->rowCount();
        }

        //Move file to specific folder
        public function move_user_image() {
            $file_extension = pathinfo($this->filedata, PATHINFO_EXTENSION);
            $new_name = uniqid() . '.' . $file_extension;
            $source_path = $this->filedata['tmp_name'];
            $target_path = './images/user_images' . $new_name;
            move_uploaded_file($source_path, $target_path);

            return $new_name;
        }

        //Redirect to the page that is passed in
        public function redirect($page) {
            header("location: $page");
            exit();
        }

        //Return array of the executed statement
        public function result() {
            $this->execute_query();
            return $this->statement->fetchAll();
        }
    }
?>