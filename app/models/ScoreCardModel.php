<?php
class ScoreCardModel extends Model {
    
    public function getAllEmployees() {
        $stmt = $this->db->query("SELECT * FROM employees_info ORDER BY lastname, firstname");
        return $stmt->fetchAll();
    }
    
    public function getEmployeeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM employees_info WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getEvaluationByEmployeeId($employeeId) {
        $stmt = $this->db->prepare("SELECT * FROM evaluation_forms WHERE employee_id = ?");
        $stmt->execute([$employeeId]);
        return $stmt->fetch();
    }
    
    public function saveEvaluationForm($employeeId, $reviewerId = null, $reviewerDesignation = null) {
        // Check if evaluation record exists
        $stmt = $this->db->prepare("SELECT id FROM evaluation_forms WHERE employee_id = ?");
        $stmt->execute([$employeeId]);
        $existingForm = $stmt->fetch();
        
        if ($existingForm) {
            // Update existing form
            $stmt = $this->db->prepare("UPDATE evaluation_forms SET 
                                      reviewer_id = ?, 
                                      reviewer_designation = ?, 
                                      updated_at = NOW() 
                                      WHERE employee_id = ?");
            $stmt->execute([$reviewerId, $reviewerDesignation, $employeeId]);
            return $existingForm['id'];
        } else {
            // Insert new form
            $stmt = $this->db->prepare("INSERT INTO evaluation_forms 
                                      (employee_id, reviewer_id, reviewer_designation, created_at, updated_at) 
                                      VALUES (?, ?, ?, NOW(), NOW())");
            $stmt->execute([$employeeId, $reviewerId, $reviewerDesignation]);
            return $this->db->lastInsertId();
        }
    }
    
    public function getKRAData($evaluationId, $categoryId) {
        $stmt = $this->db->prepare("SELECT * FROM kra_data WHERE evaluation_id = ? AND category_id = ?");
        $stmt->execute([$evaluationId, $categoryId]);
        return $stmt->fetchAll();
    }
    
    public function deleteKRAData($evaluationId, $categoryId) {
        $stmt = $this->db->prepare("DELETE FROM kra_data WHERE evaluation_id = ? AND category_id = ?");
        return $stmt->execute([$evaluationId, $categoryId]);
    }
    
    public function saveKRAData(
        $evaluationId, 
        $categoryId,
        $kraType,
        $goal,
        $measurement,
        $weight,
        $target,
        $ratingPeriod,
        $jan = null,
        $feb = null,
        $mar = null,
        $apr = null,
        $may = null,
        $jun = null,
        $jul = null,
        $aug = null,
        $sep = null,
        $oct = null,
        $nov = null,
        $dec = null,
        $rating = null,
        $score = null,
        $evidence = null,
        $isLocked = false
    ) {
        $stmt = $this->db->prepare("INSERT INTO kra_data (
            evaluation_id, category_id, kra_type, goal, measurement, weight, target,
            rating_period, jan_value, feb_value, mar_value, apr_value, may_value,
            jun_value, jul_value, aug_value, sep_value, oct_value, nov_value, dec_value,
            rating, score, evidence, is_locked
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )");
        
        return $stmt->execute([
            $evaluationId, $categoryId, $kraType, $goal, $measurement, $weight, $target,
            $ratingPeriod, $jan, $feb, $mar, $apr, $may, $jun, $jul, $aug, $sep, $oct,
            $nov, $dec, $rating, $score, $evidence, $isLocked
        ]);
    }
    
    public function updateEvaluationScore($evaluationId, $score) {
        $stmt = $this->db->prepare("UPDATE evaluation_forms SET evaluation_score = ?, updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$score, $evaluationId]);
    }
    
    public function checkAllSectionsLocked($evaluationId) {
        // Get all category IDs
        $stmt = $this->db->query("SELECT id FROM kra_categories");
        $categories = $stmt->fetchAll();
        
        foreach ($categories as $category) {
            // Check if there are any unlocked rows for this category
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM kra_data 
                                        WHERE evaluation_id = ? AND category_id = ? AND is_locked = 0");
            $stmt->execute([$evaluationId, $category['id']]);
            $result = $stmt->fetch();
            
            if ($result['count'] > 0) {
                return false; // Found unlocked rows
            }
            
            // Check if there are any rows at all for this category
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM kra_data 
                                        WHERE evaluation_id = ? AND category_id = ?");
            $stmt->execute([$evaluationId, $category['id']]);
            $result = $stmt->fetch();
            
            if ($result['count'] == 0) {
                return false; // No data for this category
            }
        }
        
        return true; // All categories have locked data
    }
    
    public function calculateTotalScore($evaluationId) {
        // Get category weights
        $stmt = $this->db->query("SELECT id, weight FROM kra_categories");
        $categories = $stmt->fetchAll();
        
        $totalScore = 0;
        
        foreach ($categories as $category) {
            // Calculate weighted average score for this category
            $stmt = $this->db->prepare("SELECT SUM(score * weight) / SUM(weight) as category_score 
                                        FROM kra_data 
                                        WHERE evaluation_id = ? AND category_id = ?");
            $stmt->execute([$evaluationId, $category['id']]);
            $result = $stmt->fetch();
            
            if ($result && $result['category_score'] !== null) {
                // Apply category weight to category score
                $totalScore += ($result['category_score'] * $category['weight'] / 100);
            }
        }
        
        return round($totalScore, 2);
    }
    
    public function finalizeEvaluation($evaluationId, $totalScore) {
        $stmt = $this->db->prepare("UPDATE evaluation_forms SET 
                                  evaluation_score = ?, 
                                  updated_at = NOW() 
                                  WHERE id = ?");
        return $stmt->execute([$totalScore, $evaluationId]);
    }
}