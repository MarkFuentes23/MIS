<?php
class ScoreCardModel extends Model {
    
    // Get or create scorecard for an employee
    public function getOrCreateScorecard($employeeId, $evaluationPeriod, $positionTitle, $department, $reviewer, $reviewerDesignation) {
        // Check if scorecard exists
        $stmt = $this->db->prepare("SELECT * FROM scorecards WHERE employee_id = ? AND evaluation_period = ?");
        $stmt->execute([$employeeId, $evaluationPeriod]);
        $scorecard = $stmt->fetch();
        
        if (!$scorecard) {
            // Create new scorecard
            $stmt = $this->db->prepare("INSERT INTO scorecards 
                (employee_id, evaluation_period, position_title, department, reviewer, reviewer_designation) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$employeeId, $evaluationPeriod, $positionTitle, $department, $reviewer, $reviewerDesignation]);
            
            // Get the newly created scorecard
            $scorecardId = $this->db->lastInsertId();
            $stmt = $this->db->prepare("SELECT * FROM scorecards WHERE id = ?");
            $stmt->execute([$scorecardId]);
            $scorecard = $stmt->fetch();
        }
        
        return $scorecard;
    }
    
    // Validate that a KRA ID exists in the database
    public function validateKraId($kraId) {
        if (!$kraId) return false;
        
        $stmt = $this->db->prepare("SELECT id FROM kras WHERE id = ?");
        $stmt->execute([$kraId]);
        return $stmt->fetch() ? true : false;
    }
    
    // Save a new goal
    public function saveGoal($scorecardId, $kraId, $perspective, $goalData) {
        // Validate KRA ID exists
        if (!$this->validateKraId($kraId)) {
            return false;
        }
        
        $sql = "INSERT INTO scorecard_goals 
                (scorecard_id, kra_id, perspective, goal, measurement, weight, target, rating_period,
                 jan_value, feb_value, mar_value, apr_value, may_value, jun_value,
                 jul_value, aug_value, sep_value, oct_value, nov_value, dec_value,
                 rating, evidence) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $scorecardId,
            $kraId,
            $perspective,
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
    
    // Update goal score based on formula
    private function updateGoalScore($goalId) {
        $stmt = $this->db->prepare("SELECT weight, rating, rating_period FROM scorecard_goals WHERE id = ?");
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
            
            $stmt = $this->db->prepare("UPDATE scorecard_goals SET score = ? WHERE id = ?");
            $stmt->execute([$score, $goalId]);
        }
    }
    
    // Get goal ID for existing goal
    private function getGoalId($scorecardId, $kraId, $goalText) {
        $stmt = $this->db->prepare("SELECT id FROM scorecard_goals WHERE scorecard_id = ? AND kra_id = ? AND goal = ?");
        $stmt->execute([$scorecardId, $kraId, $goalText]);
        $result = $stmt->fetch();
        return $result ? $result['id'] : null;
    }
    
    // Get goals for a scorecard
    public function getGoalsByScorecard($scorecardId, $perspective = null) {
        $sql = "SELECT sg.*, k.kra as kra_name 
                FROM scorecard_goals sg 
                JOIN kras k ON sg.kra_id = k.id 
                WHERE sg.scorecard_id = ?";
        
        $params = [$scorecardId];
        
        if ($perspective) {
            $sql .= " AND sg.perspective = ?";
            $params[] = $perspective;
        }
        
        $sql .= " ORDER BY sg.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Get all KRAs
    public function getAllKras() {
        $stmt = $this->db->query("SELECT id, kra FROM kras ORDER BY kra ASC");
        return $stmt->fetchAll();
    }
    
    // Get employee info
    public function getEmployeeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM employees_info WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Get all employees
    public function getAllEmployees() {
        $stmt = $this->db->query("SELECT id, first_name, last_name FROM employees_info ORDER BY last_name ASC");
        return $stmt->fetchAll();
    }
    
    // Get scorecard by employee
    public function getScorecardByEmployee($employeeId, $evaluationPeriod) {
        $stmt = $this->db->prepare("SELECT * FROM scorecards WHERE employee_id = ? AND evaluation_period = ?");
        $stmt->execute([$employeeId, $evaluationPeriod]);
        return $stmt->fetch();
    }
    
    // Update existing goal
   public function updateGoal($goalId, $goalData) {
        if (!$goalId) return false;
        
        $sql = "UPDATE scorecard_goals SET 
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
            // Update the goal score after modifying it
            $this->updateGoalScore($goalId);
            return true;
        }
        
        return false;
    }
    
    // Delete a goal
    public function deleteGoal($goalId) {
        if (!$goalId) return false;
        
        $stmt = $this->db->prepare("DELETE FROM scorecard_goals WHERE id = ?");
        return $stmt->execute([$goalId]);
    }
    
    // Get goal details by ID
    public function getGoalById($goalId) {
        $stmt = $this->db->prepare("SELECT * FROM scorecard_goals WHERE id = ?");
        $stmt->execute([$goalId]);
        return $stmt->fetch();
    }
    
    // Get total score for a scorecard
    public function getTotalScore($scorecardId, $perspective = null) {
        $sql = "SELECT SUM(score) as total_score FROM scorecard_goals WHERE scorecard_id = ?";
        $params = [$scorecardId];
        
        if ($perspective) {
            $sql .= " AND perspective = ?";
            $params[] = $perspective;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result ? $result['total_score'] : 0;
    }
    
    // Get total weight for a scorecard
    public function getTotalWeight($scorecardId, $perspective = null) {
        $sql = "SELECT SUM(weight) as total_weight FROM scorecard_goals WHERE scorecard_id = ?";
        $params = [$scorecardId];
        
        if ($perspective) {
            $sql .= " AND perspective = ?";
            $params[] = $perspective;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result ? $result['total_weight'] : 0;
    }
    
    // Update scorecard details
    public function updateScorecard($scorecardId, $data) {
        $sql = "UPDATE scorecards SET 
                position_title = ?, 
                department = ?, 
                reviewer = ?, 
                reviewer_designation = ?,
                updated_at = NOW()
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['position_title'] ?? null,
            $data['department'] ?? null,
            $data['reviewer'] ?? null,
            $data['reviewer_designation'] ?? null,
            $scorecardId
        ]);
    }
    
    // Get goals by KRA
    public function getGoalsByKra($scorecardId, $kraId) {
        $stmt = $this->db->prepare("SELECT * FROM scorecard_goals WHERE scorecard_id = ? AND kra_id = ?");
        $stmt->execute([$scorecardId, $kraId]);
        return $stmt->fetchAll();
    }
    
    // Get all perspectives
    public function getAllPerspectives() {
        return ['financial', 'customer', 'internal', 'learning'];
    }
    
    // Get scorecard overall performance
    public function getScorecardPerformance($scorecardId) {
        $sql = "SELECT 
                SUM(score) as total_score, 
                SUM(weight) as total_weight,
                CASE 
                    WHEN SUM(weight) > 0 THEN (SUM(score) / SUM(weight)) * 100
                    ELSE 0
                END as performance_percentage
                FROM scorecard_goals 
                WHERE scorecard_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$scorecardId]);
        return $stmt->fetch();
    }
    
    // Get perspective performance
    public function getPerspectivePerformance($scorecardId, $perspective) {
        $sql = "SELECT 
                SUM(score) as total_score, 
                SUM(weight) as total_weight,
                CASE 
                    WHEN SUM(weight) > 0 THEN (SUM(score) / SUM(weight)) * 100
                    ELSE 0
                END as performance_percentage
                FROM scorecard_goals 
                WHERE scorecard_id = ? AND perspective = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$scorecardId, $perspective]);
        return $stmt->fetch();
    }
}