<?php
class locationModel {
    private $db;
    public function __construct(){
        $this->db = \lib\Database::getInstance()->getConnection();
    }
    public function insertLocation($data){
        $stmt = $this->db->prepare("INSERT INTO locations (location) VALUES (?)");
        return $stmt->execute([$data['location']]);
    }
    
    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM locations");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>
