<?php
require_once 'lib/database.php';
use lib\Database;

class departmentModel {
    private $db;
    
    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>
