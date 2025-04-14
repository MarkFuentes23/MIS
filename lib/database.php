<?php
namespace lib;

class Database {
    private static $instance = null;
    private $conn;

    // Bagong database name: bci_ibsc
    private $host = "localhost";
    private $user = "root";    
    private $pass = "";    
    private $dbname = "bci_ibsc";

    private function __construct(){
        try {
            $this->conn = new \PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname, 
                $this->user, 
                $this->pass
            );
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch(\PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    public static function getInstance(){
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->conn;
    }
}
?>
