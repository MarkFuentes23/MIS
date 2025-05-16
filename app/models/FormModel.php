<?php
class FormModel extends Model {
    
    public function getAllForms() {
        $stmt = $this->db->query("SELECT * FROM evaluation_forms ORDER BY id");
        return $stmt->fetchAll();
    }
    
    // Add this missing method
    public function getAllEmployees() {
        $stmt = $this->db->query("SELECT * FROM employees_info ORDER BY lastname, firstname");
        return $stmt->fetchAll();
    }
    
    public function getEmployeeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM employees_info WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getFormById($id) {
        $stmt = $this->db->prepare("SELECT * FROM evaluation_forms WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function saveForm($employeeId, $evaluation, $reviewerId = null, $reviewerDesignation = null) {
        // Check if evaluation record exists
        $stmt = $this->db->prepare("SELECT id FROM evaluation_forms WHERE employee_id = ?");
        $stmt->execute([$employeeId]);
        $existingForm = $stmt->fetch();
        
        if ($existingForm) {
            // Update existing form
            $stmt = $this->db->prepare("UPDATE evaluation_forms SET 
                                      evaluation_score = ?, 
                                      reviewer_id = ?, 
                                      reviewer_designation = ?, 
                                      updated_at = NOW() 
                                      WHERE employee_id = ?");
            return $stmt->execute([$evaluation, $reviewerId, $reviewerDesignation, $employeeId]);
        } else {
            // Insert new form
            $stmt = $this->db->prepare("INSERT INTO evaluation_forms 
                                      (employee_id, evaluation_score, reviewer_id, reviewer_designation, created_at, updated_at) 
                                      VALUES (?, ?, ?, ?, NOW(), NOW())");
            return $stmt->execute([$employeeId, $evaluation, $reviewerId, $reviewerDesignation]);
        }
    }
    
    public function getFormByEmployeeId($employeeId) {
        $stmt = $this->db->prepare("SELECT * FROM evaluation_forms WHERE employee_id = ?");
        $stmt->execute([$employeeId]);
        return $stmt->fetch();
    }
    
     public function getAllKras() {
    $stmt = $this->db->query("SELECT id, kra FROM kras ORDER BY kra ASC");
    return $stmt->fetchAll();
}

    public function updateEvaluationScore($formId, $score) {
        $stmt = $this->db->prepare("UPDATE evaluation_forms SET evaluation_score = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$score, $formId]);
    }
    
    // Add this missing method (referenced in saveEvaluation)
    public function updateEvaluation($employeeId, $evaluation) {
        $stmt = $this->db->prepare("UPDATE employees_info SET evaluation = ? WHERE id = ?");
        return $stmt->execute([$evaluation, $employeeId]);
    }
}