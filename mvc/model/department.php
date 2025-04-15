<?php
class department {
    private $db;

    public function __construct(){
        $this->db = \lib\Database::getInstance()->getConnection();
    }

    // Method para mag-insert ng department record
    public function insertDepartment($data){
        $stmt = $this->db->prepare("INSERT INTO departments (department) VALUES (?)");
        $stmt->execute([
            $data['department']
        ]);
        return $this->db->lastInsertId();
    }
}
?>
