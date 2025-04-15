<?php
class evaluationModel {
    private $db;
    
    public function __construct(){
        $this->db = \lib\Database::getInstance()->getConnection();
    }
    
    public function saveRecord($employee_id, $position_title_id, $reviewer_id, $evaluation_period, $department_id, $reviewer_designation_id){
        $stmt = $this->db->prepare("INSERT INTO evaluations (employee_id, position_title_id, reviewer_id, evaluation_period, department_id, reviewer_designation_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$employee_id, $position_title_id, $reviewer_id, $evaluation_period, $department_id, $reviewer_designation_id]);
    }
    
    public function getAllRecords(){
        $stmt = $this->db->prepare("SELECT * FROM evaluations");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getRecordById($id){
        $stmt = $this->db->prepare("SELECT * FROM evaluations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
?>
