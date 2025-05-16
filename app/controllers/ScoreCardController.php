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
        
        // Get all employees for dropdown
        $employees = $this->scoreCardModel->getAllEmployees();
        
        $this->view->render('scorecard/index', [
            'employees' => $employees
        ]);
    }
    
    // Get employee data for form
    public function getEmployeeData() {
        $this->isAuthenticated();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
            $employeeId = $_POST['employee_id'];
            $employeeData = $this->scoreCardModel->getEmployeeById($employeeId);
            
            if ($employeeData) {
                // Check if the employee already has an evaluation form
                $existingForm = $this->scoreCardModel->getEvaluationByEmployeeId($employeeId);
                
                if ($existingForm) {
                    // Get KRA data for each category
                    $financialData = $this->scoreCardModel->getKRAData($existingForm['id'], 1); // 1 = Financial
                    $strategicData = $this->scoreCardModel->getKRAData($existingForm['id'], 2); // 2 = Strategic
                    $operationalData = $this->scoreCardModel->getKRAData($existingForm['id'], 3); // 3 = Operational
                    $learningData = $this->scoreCardModel->getKRAData($existingForm['id'], 4); // 4 = Learning
                    
                    echo json_encode([
                        'status' => 'success',
                        'employee' => $employeeData,
                        'evaluation' => $existingForm,
                        'kra_data' => [
                            'financial' => $financialData,
                            'strategic' => $strategicData,
                            'operational' => $operationalData,
                            'learning' => $learningData
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'success',
                        'employee' => $employeeData,
                        'evaluation' => null,
                        'kra_data' => null
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Employee not found'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }
        exit;
    }
    
    // Save or create evaluation form
    public function saveEvaluation() {
        $this->isAuthenticated();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employeeId = $_POST['employee_id'];
            $reviewerId = $_POST['reviewer_id'] ?? null;
            $reviewerDesignation = $_POST['reviewer_designation'] ?? null;
            
            // Create or update evaluation form record
            $evaluationId = $this->scoreCardModel->saveEvaluationForm($employeeId, $reviewerId, $reviewerDesignation);
            
            if ($evaluationId) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Evaluation form saved successfully',
                    'evaluation_id' => $evaluationId
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to save evaluation form'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }
        exit;
    }
    
    // Lock a KRA category section
    public function lockKRASection() {
        $this->isAuthenticated();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $evaluationId = $_POST['evaluation_id'];
            $categoryId = $_POST['category_id'];
            $kraData = json_decode($_POST['kra_data'], true);
            
            // Delete existing records for this evaluation and category
            $this->scoreCardModel->deleteKRAData($evaluationId, $categoryId);
            
            // Insert new KRA data
            $success = true;
            foreach ($kraData as $kra) {
                $result = $this->scoreCardModel->saveKRAData(
                    $evaluationId, 
                    $categoryId,
                    $kra['kra_type'],
                    $kra['goal'],
                    $kra['measurement'],
                    $kra['weight'],
                    $kra['target'],
                    $kra['rating_period'],
                    $kra['jan'] ?? null,
                    $kra['feb'] ?? null,
                    $kra['mar'] ?? null,
                    $kra['apr'] ?? null,
                    $kra['may'] ?? null,
                    $kra['jun'] ?? null,
                    $kra['jul'] ?? null,
                    $kra['aug'] ?? null,
                    $kra['sep'] ?? null,
                    $kra['oct'] ?? null,
                    $kra['nov'] ?? null,
                    $kra['dec'] ?? null,
                    $kra['rating'] ?? null,
                    $kra['score'] ?? null,
                    $kra['evidence'] ?? null,
                    true // locked
                );
                
                if (!$result) {
                    $success = false;
                }
            }
            
            // Update evaluation score
            $totalScore = $this->scoreCardModel->calculateTotalScore($evaluationId);
            $this->scoreCardModel->updateEvaluationScore($evaluationId, $totalScore);
            
            if ($success) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'KRA section locked successfully',
                    'total_score' => $totalScore
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to lock KRA section'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }
        exit;
    }
    
    // Final submission of the entire evaluation
    public function submitEvaluation() {
        $this->isAuthenticated();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $evaluationId = $_POST['evaluation_id'];
            
            // Check if all sections are locked
            $allLocked = $this->scoreCardModel->checkAllSectionsLocked($evaluationId);
            
            if (!$allLocked) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'All KRA sections must be locked before submission'
                ]);
                exit;
            }
            
            // Calculate final score
            $totalScore = $this->scoreCardModel->calculateTotalScore($evaluationId);
            
            // Update evaluation with final score
            $result = $this->scoreCardModel->finalizeEvaluation($evaluationId, $totalScore);
            
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Evaluation submitted successfully',
                    'total_score' => $totalScore
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to submit evaluation'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }
        exit;
    }
}