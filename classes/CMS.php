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
        public function move_user_image($image_folder) {
            $file_extension = pathinfo($this->filedata['name'], PATHINFO_EXTENSION);
            $new_name = uniqid() . '.' . $file_extension;
            $source_path = $this->filedata['tmp_name'];
            $target_path = "./images/$image_folder/$new_name";
            move_uploaded_file($source_path, $target_path);

            return $target_path;
        }

        //Redirect to the page that is passed in
        public function redirect($page) {
            header("location: $page");
            exit();
        }

        //Redirect the user if he is not logged in either as admin or simple user to the index page 
        public function user_session() {
            if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
                $this->redirect('index.php');
            }
        }

        //Redirect user if he is not logged in as admin
        public function admin_session() {
            if(!isset($_SESSION['admin_id'])) {
                $this->redirect('index.php');
            }
        }

        //Return array of the executed statement
        public function result() {
            $this->execute_query();
            return $this->statement->fetchAll();
        }

        //Calculate and show how much time has passed since passing
        //this data
        public function timePassed($date) {
            $date_created = new DateTime($date);
            $date_now = new DateTime;

            $difference = $date_created->diff($date_now);

            if($difference->y > 0) return [$difference->y, $difference->y > 1 ? 'years' : 'year'];
            else if($difference->m > 0) return [$difference->m, $difference->m > 1 ? 'months' : 'month'];
            else if($difference->d > 0) return [$difference->d, $difference->d > 1 ? 'days' : 'day'];
            else if($difference->h > 0) return [$difference->h, $difference->h > 1 ? 'hours' : 'hour'];
            else if($difference->i > 0) return [$difference->i, $difference->i > 1 ? 'minutes' : 'minute'];
            else if($difference->s > 0) return [$difference->s, $difference->s > 1 ? 'seconds' : 'second'];
            else return ['now', 'now'];
        }

        //Shorten the text to a certain amount of characters and add '...' at the end
        public function shortenText($str, $limit) {
            if(strlen($str) > $limit) {
                $str = substr($str, 0, $limit) . '...';
            }

            return $str;
        }
    }
?>
