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
    
    // Save or update a goal
    public function saveGoal($scorecardId, $kraId, $perspective, $goalData) {
        $sql = "INSERT INTO scorecard_goals 
                (scorecard_id, kra_id, perspective, goal, measurement, weight, target, rating_period,
                 jan_value, feb_value, mar_value, apr_value, may_value, jun_value,
                 jul_value, aug_value, sep_value, oct_value, nov_value, dec_value,
                 rating, evidence) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                goal = VALUES(goal),
                measurement = VALUES(measurement),
                weight = VALUES(weight),
                target = VALUES(target),
                rating_period = VALUES(rating_period),
                jan_value = VALUES(jan_value),
                feb_value = VALUES(feb_value),
                mar_value = VALUES(mar_value),
                apr_value = VALUES(apr_value),
                may_value = VALUES(may_value),
                jun_value = VALUES(jun_value),
                jul_value = VALUES(jul_value),
                aug_value = VALUES(aug_value),
                sep_value = VALUES(sep_value),
                oct_value = VALUES(oct_value),
                nov_value = VALUES(nov_value),
                dec_value = VALUES(dec_value),
                rating = VALUES(rating),
                evidence = VALUES(evidence)";
        
        $stmt = $this->db->prepare($sql);
        
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
        
        $result = $stmt->execute($params);
        
        if ($result) {
            // Calculate and update score
            $goalId = $this->db->lastInsertId() ?: $this->getGoalId($scorecardId, $kraId, $goalData['goal']);
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
        $stmt = $this->db->query("SELECT * FROM employees_info ORDER BY lastname, firstname");
        return $stmt->fetchAll();
    }
    
    // Get scorecard by employee and period
    public function getScorecardByEmployee($employeeId, $evaluationPeriod) {
        $stmt = $this->db->prepare("SELECT * FROM scorecards WHERE employee_id = ? AND evaluation_period = ?");
        $stmt->execute([$employeeId, $evaluationPeriod]);
        return $stmt->fetch();
    }
    
    // Delete a goal
    public function deleteGoal($goalId) {
        $stmt = $this->db->prepare("DELETE FROM scorecard_goals WHERE id = ?");
        return $stmt->execute([$goalId]);
    }


    
    
    // Update goal by ID
    public function updateGoal($goalId, $goalData) {
        $sql = "UPDATE scorecard_goals SET 
                goal = ?, measurement = ?, weight = ?, target = ?, rating_period = ?,
                jan_value = ?, feb_value = ?, mar_value = ?, apr_value = ?, may_value = ?, jun_value = ?,
                jul_value = ?, aug_value = ?, sep_value = ?, oct_value = ?, nov_value = ?, dec_value = ?,
                rating = ?, evidence = ?
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
        
        $result = $this->db->prepare($sql)->execute($params);
        
        if ($result) {
            $this->updateGoalScore($goalId);
        }
        
        return $result;
    }
}