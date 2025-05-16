<?php
class FormController extends Controller {
    private $formModel;

    public function __construct() {
        parent::__construct();
        $this->formModel = $this->model('FormModel');
    }

    // Main page for the balanced scorecard form
  // In your FormController.php - keep this method as is
public function view() {
    $this->isAuthenticated();
    
    // Get all employees for the dropdown selection
    $employees = $this->formModel->getAllEmployees();
    $kras = $this->formModel->getAllKras(); // This fetches KRAs correctly
    
    // Format employees for the dropdown (lastname, firstname)
    $formattedEmployees = [];
    foreach ($employees as $employee) {
        $fullname = $employee['lastname'] . ', ' . $employee['firstname'] . ' ' . 
                   ($employee['middlename'] ? $employee['middlename'] : '') . ' ' .
                   ($employee['suffix'] ? $employee['suffix'] : '');
        
        $employee['fullname'] = $fullname;
        $formattedEmployees[] = $employee;
    }
    
    $this->view->render('employee/formEmployee', [
        'employees' => $formattedEmployees,
        'kras' => $kras  // This passes KRAs to the view
    ]);
}
    
    // Get employee data by ID via AJAX
    public function getEmployeeData() {
        $this->isAuthenticated();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
            $employeeId = $_POST['employee_id'];
            $employeeData = $this->formModel->getEmployeeById($employeeId);
            
            if ($employeeData) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $employeeData
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
                'message' => 'Invalid request'
            ]);
        }
        exit;
    }
}