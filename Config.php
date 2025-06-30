<?php

    class DB{

        public $conn;
        public $server_name = "localhost";
        public $user_name = "root";
        public $password = "";
        public $database = "rhino";

        public function __construct(){
            $this->conn = mysqli_connect(
                $this->server_name,
                $this->user_name,
                $this->password,
                $this->database
            );
            if (!$this->conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
        }

    }

?>
