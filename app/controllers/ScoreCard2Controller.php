<?php
class ScoreCard2Controller extends Controller {
    private $scoreCard2Model;
    
    public function __construct() {
        parent::__construct();
        $this->scoreCard2Model = $this->model('ScoreCard2Model');
    }
    
    // Main scorecard form view
    public function index() {
        $this->isAuthenticated();
        
        // Get all employees and KRAs for dropdowns
        $employees = $this->scoreCard2Model->getAllEmployees();
        $kras = $this->scoreCard2Model->getAllKras();
        
        $this->view->render('scorecard/view', [
            'employees' => $employees,
            'kras' => $kras
        ]);
    }
    
    // Get employee data via AJAX
    public function getEmployeeData() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employeeId = $_POST['employee_id'] ?? null;
            $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
            
            if ($employeeId) {
                $employee = $this->scoreCard2Model->getEmployeeById($employeeId);
                
                if ($employee) {
                    // Get scorecard data
                    $scorecard = $this->scoreCard2Model->getScorecardByEmployee($employeeId, $evaluationPeriod);
                    
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
    
    // Get all employees for dropdown
    public function getEmployees() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $searchTerm = $_POST['search'] ?? '';
            $employees = $this->scoreCard2Model->getAllEmployees($searchTerm);
            echo json_encode([
                'status' => 'success',
                'data' => $employees
            ]);
        }
        exit;
    }

    public function getKras() {
        $kras = $this->scoreCard2Model->getAllKras();
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
                
                $goals = $this->scoreCard2Model->getGoalsForDisplay($employeeId, $evaluationPeriod);
                
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
                $category = $_POST['category'] ?? 'financial';
                
                if (!$employeeId) {
                    throw new Exception('Employee ID is required');
                }
                
                // Ensure we have a valid KRA ID
                $kraId = $_POST['kra_id'] ?? null;
                
                if (!$kraId) {
                    throw new Exception('KRA is required');
                }
                
                // Validate that KRA ID exists
                $validKra = $this->scoreCard2Model->validateKraId($kraId);
                if (!$validKra) {
                    throw new Exception('Invalid KRA selected');
                }
                
                // Get or create scorecard
                $jobClassification = $_POST['job_classification'] ?? '';
                $scorecard = $this->scoreCard2Model->getOrCreateScorecard(
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
                $goalId = $this->scoreCard2Model->saveGoal($scorecard['id'], $kraId, $category, $goalData);
                
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
                $result = $this->scoreCard2Model->updateGoal($goalId, $goalData);
                
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
                $goal = $this->scoreCard2Model->getGoalById($goalId);
                if (!$goal) {
                    throw new Exception('Goal not found');
                }
                
                // Delete the goal
                $result = $this->scoreCard2Model->deleteGoal($goalId);
                
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
                
                $exists = $this->scoreCard2Model->checkKraExists($employeeId, $evaluationPeriod, $kraId, $currentGoalId);
                
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
                $category = $_POST['category'] ?? 'financial';
                
                if (!$employeeId) {
                    throw new Exception('Employee ID is required');
                }
                
                $calculations = $this->scoreCard2Model->getCalculationsForEmployee($employeeId, $evaluationPeriod, $category);
                
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
                
                $calculations = $this->scoreCard2Model->getTotalCalculations($employeeId, $evaluationPeriod);
                
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

    public function getWeightsByPerspective() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $employeeId = $_POST['employee_id'] ?? null;
                $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
                
                if (!$employeeId) {
                    throw new Exception('Employee ID is required');
                }
                
                $weights = $this->scoreCard2Model->getWeightsByPerspective($employeeId, $evaluationPeriod);
                
                echo json_encode([
                    'status' => 'success',
                    'data' => $weights
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

    // Check if a category has reached its weight limit
    public function checkCategoryLimit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $employeeId = $_POST['employee_id'] ?? null;
                $evaluationPeriod = $_POST['evaluation_period'] ?? date('Y');
                $category = $_POST['category'] ?? null;
                
                if (!$employeeId) {
                    throw new Exception('Employee ID is required');
                }
                
                if (!$category) {
                    throw new Exception('Category is required');
                }
                
                $limitInfo = $this->scoreCard2Model->checkCategoryWeightLimit($employeeId, $evaluationPeriod, $category);
                
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
