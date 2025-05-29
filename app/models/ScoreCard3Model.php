<?php
class ScoreCard3Model extends Model {
    private $table = 'strategic_goals';
    private $weightLimit = 10; // Strategic goals have 10% weight limit
    
    public function getEmployeeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM employees_info WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getScorecardByEmployee($employeeId, $evaluationPeriod) {
        $stmt = $this->db->prepare("SELECT * FROM scorecards WHERE employee_id = ? AND evaluation_period = ?");
        $stmt->execute([$employeeId, $evaluationPeriod]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getGoalsForDisplay($employeeId, $evaluationPeriod) {
        $sql = "SELECT sg.*, k.kra as kra_name, s.id as scorecard_id
                FROM {$this->table} sg 
                JOIN kras k ON sg.kra_id = k.id 
                JOIN scorecards s ON sg.scorecard_id = s.id
                WHERE s.employee_id = ? AND s.evaluation_period = ?
                ORDER BY k.kra, sg.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$employeeId, $evaluationPeriod]);
        return $stmt->fetchAll();
    }
    
    public function validateKraId($kraId) {
        if (!$kraId) return false;
        
        $stmt = $this->db->prepare("SELECT id FROM kras WHERE id = ?");
        $stmt->execute([$kraId]);
        return $stmt->fetch() ? true : false;
    }
    
    public function getOrCreateScorecard($employeeId, $evaluationPeriod, $positionTitle, $department, $reviewer, $reviewerDesignation, $jobClassification) {
        // Check if scorecard exists
        $stmt = $this->db->prepare("SELECT * FROM scorecards WHERE employee_id = ? AND evaluation_period = ?");
        $stmt->execute([$employeeId, $evaluationPeriod]);
        $scorecard = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$scorecard) {
            // Create new scorecard
            $stmt = $this->db->prepare("INSERT INTO scorecards 
                (employee_id, evaluation_period, position_title, department, reviewer, reviewer_designation, job_classification) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$employeeId, $evaluationPeriod, $positionTitle, $department, $reviewer, $reviewerDesignation, $jobClassification]);
            
            // Get the newly created scorecard
            $scorecardId = $this->db->lastInsertId();
            $stmt = $this->db->prepare("SELECT * FROM scorecards WHERE id = ?");
            $stmt->execute([$scorecardId]);
            $scorecard = $stmt->fetch();
        } else {
            // Update existing scorecard with new info if provided
            if ($positionTitle || $department || $reviewer || $reviewerDesignation || $jobClassification) {
                $updateSql = "UPDATE scorecards SET ";
                $updateParams = [];
                $updateFields = [];
                
                if ($positionTitle) {
                    $updateFields[] = "position_title = ?";
                    $updateParams[] = $positionTitle;
                }
                if ($department) {
                    $updateFields[] = "department = ?";
                    $updateParams[] = $department;
                }
                if ($reviewer) {
                    $updateFields[] = "reviewer = ?";
                    $updateParams[] = $reviewer;
                }
                if ($reviewerDesignation) {
                    $updateFields[] = "reviewer_designation = ?";
                    $updateParams[] = $reviewerDesignation;
                }
                if ($jobClassification) {
                    $updateFields[] = "job_classification = ?";
                    $updateParams[] = $jobClassification;
                }
                
                if (!empty($updateFields)) {
                    $updateSql .= implode(", ", $updateFields);
                    $updateSql .= " WHERE id = ?";
                    $updateParams[] = $scorecard['id'];
                    
                    $updateStmt = $this->db->prepare($updateSql);
                    $updateStmt->execute($updateParams);
                }
            }
        }
        
        return $scorecard;
    }
    
    public function saveGoal($scorecardId, $kraId, $category, $goalData) {
        // Validate KRA ID exists
        if (!$this->validateKraId($kraId)) {
            return false;
        }
        
        $sql = "INSERT INTO {$this->table} 
                (scorecard_id, kra_id, category, goal, measurement, weight, target, rating_period,
                 jan_value, feb_value, mar_value, apr_value, may_value, jun_value,
                 jul_value, aug_value, sep_value, oct_value, nov_value, dec_value,
                 rating, evidence) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $scorecardId,
            $kraId,
            $category,
            $goalData['goal'],
            $goalData['measurement'],
            $goalData['weight'],
            $goalData['target'],
            $goalData['period'],
            $goalData['jan'] ?? null,
            $goalData['feb'] ?? null,
            $goalData['mar'] ?? null,
            $goalData['apr'] ?? null,
            $goalData['may'] ?? null,
            $goalData['jun'] ?? null,
            $goalData['jul'] ?? null,
            $goalData['aug'] ?? null,
            $goalData['sep'] ?? null,
            $goalData['oct'] ?? null,
            $goalData['nov'] ?? null,
            $goalData['dec'] ?? null,
            $goalData['rating'] ?? null,
            $goalData['evidence'] ?? null
        ];
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($result) {
            // Get the newly inserted goal ID
            $goalId = $this->db->lastInsertId();
            $this->updateGoalScore($goalId);
            return $goalId;
        }
        
        return false;
    }
    
    private function updateGoalScore($goalId) {
        $stmt = $this->db->prepare("SELECT weight, rating, rating_period FROM {$this->table} WHERE id = ?");
        $stmt->execute([$goalId]);
        $goal = $stmt->fetch();
        
        if ($goal && $goal['rating'] && $goal['weight']) {
            $weight = (float)$goal['weight'];
            $rating = (float)$goal['rating'];
            
            // Calculate divisor based on period
            $divisor = 1; // Default for Annual
            switch ($goal['rating_period']) {
                case 'Semi Annual':
                    $divisor = 2;
                    break;
                case 'Quarterly':
                    $divisor = 4;
                    break;
                case 'Monthly':
                    $divisor = 12;
                    break;
            }
            
            $score = ($rating / $divisor) * $weight;
            
            $stmt = $this->db->prepare("UPDATE {$this->table} SET score = ? WHERE id = ?");
            $stmt->execute([$score, $goalId]);
        }
    }
    
    public function updateGoal($goalId, $goalData) {
        if (!$goalId) return false;
        
        $sql = "UPDATE {$this->table} SET 
                goal = ?, 
                measurement = ?, 
                weight = ?, 
                target = ?, 
                rating_period = ?,
                jan_value = ?, 
                feb_value = ?, 
                mar_value = ?, 
                apr_value = ?, 
                may_value = ?, 
                jun_value = ?,
                jul_value = ?, 
                aug_value = ?, 
                sep_value = ?, 
                oct_value = ?, 
                nov_value = ?, 
                dec_value = ?,
                rating = ?, 
                evidence = ?
                WHERE id = ?";
        
        $params = [
            $goalData['goal'],
            $goalData['measurement'],
            $goalData['weight'],
            $goalData['target'],
            $goalData['period'],
            $goalData['jan'] ?? null,
            $goalData['feb'] ?? null,
            $goalData['mar'] ?? null,
            $goalData['apr'] ?? null,
            $goalData['may'] ?? null,
            $goalData['jun'] ?? null,
            $goalData['jul'] ?? null,
            $goalData['aug'] ?? null,
            $goalData['sep'] ?? null,
            $goalData['oct'] ?? null,
            $goalData['nov'] ?? null,
            $goalData['dec'] ?? null,
            $goalData['rating'] ?? null,
            $goalData['evidence'] ?? null,
            $goalId
        ];
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($params);
        
        if ($result) {
            $this->updateGoalScore($goalId);
            return true;
        }
        
        return false;
    }
    
    public function deleteGoal($goalId) {
        if (!$goalId) return false;
        
        try {
            $this->db->beginTransaction();
            
            // First, get goal details before deletion
            $goalSql = "SELECT sg.*, s.employee_id, s.evaluation_period 
                       FROM {$this->table} sg 
                       JOIN scorecards s ON sg.scorecard_id = s.id 
                       WHERE sg.id = ?";
            $goalStmt = $this->db->prepare($goalSql);
            $goalStmt->execute([$goalId]);
            $goalData = $goalStmt->fetch();
            
            if (!$goalData) {
                throw new Exception('Goal not found');
            }
            
            // Delete the goal
            $deleteSql = "DELETE FROM {$this->table} WHERE id = ?";
            $deleteStmt = $this->db->prepare($deleteSql);
            $deleteResult = $deleteStmt->execute([$goalId]);
            
            if (!$deleteResult) {
                throw new Exception('Failed to delete goal');
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Delete goal error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getGoalById($goalId) {
        $stmt = $this->db->prepare("SELECT sg.*, s.employee_id, s.evaluation_period 
                                   FROM {$this->table} sg 
                                   JOIN scorecards s ON sg.scorecard_id = s.id 
                                   WHERE sg.id = ?");
        $stmt->execute([$goalId]);
        return $stmt->fetch();
    }
    
    public function checkWeightLimit($employeeId, $evaluationPeriod) {
        $sql = "SELECT COALESCE(SUM(weight), 0) as total_weight 
                FROM {$this->table} sg
                JOIN scorecards s ON sg.scorecard_id = s.id
                WHERE s.employee_id = ? 
                AND s.evaluation_period = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$employeeId, $evaluationPeriod]);
        $result = $stmt->fetch();
        
        $currentWeight = floatval($result['total_weight']);
        
        return [
            'current_weight' => $currentWeight,
            'weight_limit' => $this->weightLimit,
            'remaining_weight' => $this->weightLimit - $currentWeight,
            'is_limit_reached' => $currentWeight >= $this->weightLimit
        ];
    }
}
