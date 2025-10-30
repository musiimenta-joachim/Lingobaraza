<?php	
    class Database {
        private static $instance = null;
        private $connection;

        private $dbhost = "localhost";
        private $dbname = "natural_language";
        private $dbusername = "root";
        private $dbpassword = "";
    
        private function __construct() {
            $this->connection = new mysqli(
                $this->dbhost, 
                $this->dbusername, 
                $this->dbpassword, 
                $this->dbname
            );

            if ($this->connection->connect_errno) {
                error_log("Connection error: " . $this->connection->connect_error);
                exit("A connection error occurred. Please try again later.");
            }
        }
    
        public static function getInstance() {
            if (!self::$instance) {
                self::$instance = new Database();
            }
            return self::$instance->connection;
        }
    }