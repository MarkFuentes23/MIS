<?php
require_once 'lib/database.php';
use lib\Database;

class evaluationModel {
    private $db;
    
    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }
    
    // I-save ang evaluation record sa table na "evaluations"
    public function saveEvaluation($data){
        $stmt = $this->db->prepare("INSERT INTO evaluations (employee_id, job_title_id, reviewer_id, evaluation_period, department_id, reviewer_designation_id) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['employee_id'],
            $data['job_title_id'],
            $data['reviewer_id'],
            $data['evaluation_period'],
            $data['department_id'],
            $data['reviewer_designation_id']
        ]);
    }
    
    // Kunin lahat ng record
    public function getAllRecords(){
        $stmt = $this->db->prepare("SELECT * FROM evaluations");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    // Kunin ang record base sa ID
    public function getRecordById($id){
        $stmt = $this->db->prepare("SELECT * FROM evaluations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
?>
