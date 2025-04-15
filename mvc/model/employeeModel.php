<?php
class employeeModel {
    private $db;
    public function __construct(){
        $this->db = \lib\Database::getInstance()->getConnection();
    }
    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM employees");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function insertEmployee($data){
        $stmt = $this->db->prepare("INSERT INTO employees (firstname, lastname, middlename, suffix) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['firstname'], $data['lastname'], $data['middlename'], $data['suffix']]);
    }
}
?>
