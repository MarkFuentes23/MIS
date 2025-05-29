<?php
class ScoreCard3Controller extends Controller {
    private $scoreCard3Model;
    
    public function __construct() {
        parent::__construct();
        $this->scoreCard3Model = $this->model('ScoreCard3Model');
    }
    
    // Get employee data via AJAX
    public function getEmployeeData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employeeId = $_POST['employee_id'] ?? null;
            $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
            
            if ($employeeId) {
                $employee = $this->scoreCard3Model->getEmployeeById($employeeId);
                
                if ($employee) {
                    // Get scorecard data
                    $scorecard = $this->scoreCard3Model->getScorecardByEmployee($employeeId, $evaluationPeriod);
                    
                    // Add scorecard to employee data
                    $employee['scorecard'] = $scorecard;
                    
                    echo json_encode([
                        'status' => 'success',
                        'data' => $employee
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Employee not found'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Employee ID is required'
                ]);
            }
        }
        exit;
    }

    // Load existing goals
    public function loadGoals() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $employeeId = $_POST['employee_id'] ?? null;
                $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
                
                if (!$employeeId) {
                    throw new Exception('Employee ID is required');
                }
                
                $goals = $this->scoreCard3Model->getGoalsForDisplay($employeeId, $evaluationPeriod);
                
                echo json_encode([
                    'status' => 'success',
                    'data' => $goals,
                    'count' => count($goals),
                    'employee_id' => $employeeId,
                    'evaluation_period' => $evaluationPeriod
                ]);
                
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }
        exit;
    }
    
    // Save goal via AJAX
    public function saveGoal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Get employee scorecard data
                $employeeId = $_POST['employee_id'] ?? null;
                $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
                $jobTitle = $_POST['job_title'] ?? '';
                $department = $_POST['department'] ?? '';
                $reviewer = $_POST['reviewer'] ?? '';
                $reviewerDesignation = $_POST['reviewer_designation'] ?? '';
                
                if (!$employeeId) {
                    throw new Exception('Employee ID is required');
                }
                
                // Ensure we have a valid KRA ID
                $kraId = $_POST['kra_id'] ?? null;
                
                if (!$kraId) {
                    throw new Exception('KRA is required');
                }
                
                // Validate that KRA ID exists
                $validKra = $this->scoreCard3Model->validateKraId($kraId);
                if (!$validKra) {
                    throw new Exception('Invalid KRA selected');
                }
                
                // Get or create scorecard
                $jobClassification = $_POST['job_classification'] ?? '';
                $scorecard = $this->scoreCard3Model->getOrCreateScorecard(
                    $employeeId, $evaluationPeriod, $jobTitle, 
                    $department, $reviewer, $reviewerDesignation,
                    $jobClassification
                );
                
                // Prepare goal data
                $goalData = [
                    'goal' => $_POST['goal'] ?? '',
                    'measurement' => $_POST['measurement'] ?? 'Savings',
                    'weight' => $_POST['weight'] ?? 0,
                    'target' => $_POST['target'] ?? '',
                    'period' => $_POST['period'] ?? 'Annual',
                    'jan' => $_POST['jan'] ?? null,
                    'feb' => $_POST['feb'] ?? null,
                    'mar' => $_POST['mar'] ?? null,
                    'apr' => $_POST['apr'] ?? null,
                    'may' => $_POST['may'] ?? null,
                    'jun' => $_POST['jun'] ?? null,
                    'jul' => $_POST['jul'] ?? null,
                    'aug' => $_POST['aug'] ?? null,
                    'sep' => $_POST['sep'] ?? null,
                    'oct' => $_POST['oct'] ?? null,
                    'nov' => $_POST['nov'] ?? null,
                    'dec' => $_POST['dec'] ?? null,
                    'rating' => $_POST['rating'] ?? null,
                    'evidence' => $_POST['evidence'] ?? null
                ];
                
                // Save goal
                $goalId = $this->scoreCard3Model->saveGoal($scorecard['id'], $kraId, 'strategic', $goalData);
                
                if ($goalId) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Goal saved successfully',
                        'goal_id' => $goalId,
                        'kra_id' => $kraId,
                        'employee_id' => $employeeId
                    ]);
                } else {
                    throw new Exception('Failed to save goal');
                }
                
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }
        exit;
    }

    // Update goal via AJAX
    public function updateGoal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $goalId = $_POST['goal_id'] ?? null;
                
                if (!$goalId) {
                    throw new Exception('Goal ID is required');
                }
                
                // Prepare goal data
                $goalData = [
                    'goal' => $_POST['goal'] ?? '',
                    'measurement' => $_POST['measurement'] ?? 'Savings',
                    'weight' => $_POST['weight'] ?? 0,
                    'target' => $_POST['target'] ?? '',
                    'period' => $_POST['period'] ?? 'Annual',
                    'jan' => $_POST['jan'] ?? null,
                    'feb' => $_POST['feb'] ?? null,
                    'mar' => $_POST['mar'] ?? null,
                    'apr' => $_POST['apr'] ?? null,
                    'may' => $_POST['may'] ?? null,
                    'jun' => $_POST['jun'] ?? null,
                    'jul' => $_POST['jul'] ?? null,
                    'aug' => $_POST['aug'] ?? null,
                    'sep' => $_POST['sep'] ?? null,
                    'oct' => $_POST['oct'] ?? null,
                    'nov' => $_POST['nov'] ?? null,
                    'dec' => $_POST['dec'] ?? null,
                    'rating' => $_POST['rating'] ?? null,
                    'evidence' => $_POST['evidence'] ?? null
                ];
                
                // Update goal
                $result = $this->scoreCard3Model->updateGoal($goalId, $goalData);
                
                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Goal updated successfully'
                    ]);
                } else {
                    throw new Exception('Failed to update goal');
                }
                
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }
        exit;
    }

    public function deleteGoal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $goalId = $_POST['goal_id'] ?? null;
                
                if (!$goalId) {
                    throw new Exception('Goal ID is required');
                }
                
                // Verify goal exists before deleting
                $goal = $this->scoreCard3Model->getGoalById($goalId);
                if (!$goal) {
                    throw new Exception('Goal not found');
                }
                
                // Delete the goal
                $result = $this->scoreCard3Model->deleteGoal($goalId);
                
                if ($result) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Goal deleted successfully'
                    ]);
                } else {
                    throw new Exception('Failed to delete goal');
                }
                
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }
        exit;
    }

    // Check if a category has reached its weight limit
    public function checkCategoryLimit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $employeeId = $_POST['employee_id'] ?? null;
                $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
                
                if (!$employeeId) {
                    throw new Exception('Employee ID is required');
                }
                
                $limitInfo = $this->scoreCard3Model->checkWeightLimit($employeeId, $evaluationPeriod);
                
                echo json_encode([
                    'status' => 'success',
                    'data' => $limitInfo
                ]);
                
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }
        exit;
    }
}
