<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct(){
        $host = 'localhost'; 
        $db = 'mis';
        $user = 'root';      
        $pass = '';
        $dsn  = "mysql:host=$host;dbname=$db;charset=utf8";
        
        try {
            $this->conn = new PDO($dsn, $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->conn;
    }
}