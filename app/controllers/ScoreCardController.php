<?php
class ScoreCardController extends Controller {
    private $scoreCardModel;
    
    public function __construct() {
        parent::__construct();
        $this->scoreCardModel = $this->model('ScoreCardModel');
    }
    
    // Main scorecard form view
    public function index() {
        $this->isAuthenticated();
        
        // Get all employees and KRAs for dropdowns
        $employees = $this->scoreCardModel->getAllEmployees();
        $kras = $this->scoreCardModel->getAllKras();
        
        $this->view->render('scorecard/view', [
            'employees' => $employees,
            'kras' => $kras
        ]);
    }
    
    // Get employee data via AJAX
    public function getEmployeeData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employeeId = $_POST['employee_id'] ?? null;
            
            if ($employeeId) {
                $employee = $this->scoreCardModel->getEmployeeById($employeeId);
                
                if ($employee) {
                    // Debug: log what columns are actually returned
                    error_log("Employee data: " . print_r($employee, true));
                    
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
    
    // Get KRAs for dropdown
    public function getKras() {
        $kras = $this->scoreCardModel->getAllKras();
        echo json_encode([
            'status' => 'success',
            'data' => $kras
        ]);
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
                
                $goals = $this->scoreCardModel->getGoalsForDisplay($employeeId, $evaluationPeriod);
                
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
                $perspective = $_POST['perspective'] ?? 'financial';
                
                if (!$employeeId) {
                    throw new Exception('Employee ID is required');
                }
                
                // Ensure we have a valid KRA ID
                $kraId = $_POST['kra_id'] ?? null;
                
                if (!$kraId) {
                    throw new Exception('KRA is required');
                }
                
                // Validate that KRA ID exists
                $validKra = $this->scoreCardModel->validateKraId($kraId);
                if (!$validKra) {
                    throw new Exception('Invalid KRA selected');
                }
                
                // Get or create scorecard
                $scorecard = $this->scoreCardModel->getOrCreateScorecard(
                    $employeeId, $evaluationPeriod, $jobTitle, 
                    $department, $reviewer, $reviewerDesignation
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
                $goalId = $this->scoreCardModel->saveGoal($scorecard['id'], $kraId, $perspective, $goalData);
                
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
                
                // Update goal using the dedicated update function
                $result = $this->scoreCardModel->updateGoal($goalId, $goalData);
                
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
            $goal = $this->scoreCardModel->getGoalById($goalId);
            if (!$goal) {
                throw new Exception('Goal not found');
            }
            
            // Delete the goal
            $result = $this->scoreCardModel->deleteGoal($goalId);
            
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

    public function checkKraExists() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $employeeId = $_POST['employee_id'] ?? null;
                $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
                $kraId = $_POST['kra_id'] ?? null;
                $currentGoalId = $_POST['current_goal_id'] ?? null;
                
                if (!$employeeId || !$kraId) {
                    throw new Exception('Employee ID and KRA ID are required');
                }
                
                $exists = $this->scoreCardModel->checkKraExists($employeeId, $evaluationPeriod, $kraId, $currentGoalId);
                
                echo json_encode([
                    'status' => 'success',
                    'exists' => $exists
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


    public function getCalculations() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $employeeId = $_POST['employee_id'] ?? null;
            $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
            $perspective = $_POST['perspective'] ?? 'financial';
            
            if (!$employeeId) {
                throw new Exception('Employee ID is required');
            }
            
            $calculations = $this->scoreCardModel->getCalculationsForEmployee($employeeId, $evaluationPeriod, $perspective);
            
            echo json_encode([
                'status' => 'success',
                'data' => $calculations
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

    public function getTotalCalculations() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $employeeId = $_POST['employee_id'] ?? null;
            $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
            
            if (!$employeeId) {
                throw new Exception('Employee ID is required');
            }
            
            $calculations = $this->scoreCardModel->getTotalCalculations($employeeId, $evaluationPeriod);
            
            echo json_encode([
                'status' => 'success',
                'data' => $calculations
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