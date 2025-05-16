<?php
// EmployeeController.php
class EmployeeController extends Controller {
    private $empModel;
    private $jobTitleModel;
    private $departmentModel;
    private $locationModel;

    public function __construct(){
        parent::__construct();
        $this->isAuthenticated();
        $this->empModel = $this->model('EmployeeModel');
        $this->jobTitleModel = $this->model('JobTitleModel');
        $this->departmentModel = $this->model('DepartmentModel');
        $this->locationModel = $this->model('LocationModel');
    }

    public function index() {
        // Redirect to view page
        header('Location: /employee/view');
        exit;
    }

    public function add(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $d = array_map('trim', $_POST);
            
            // Insert the employee
            $employee_id = $this->empModel->insertEmployee($d);
            
            if($employee_id){
                $response = [
                    'success' => true,
                    'message' => 'Employee added successfully'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Failed to add employee'
                ];
            }
            
            // Return JSON response for AJAX
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            // Set flash message for non-AJAX
            $_SESSION['flash'] = [
                'type' => $response['success'] ? 'success' : 'error',
                'title' => $response['success'] ? 'Success!' : 'Error!',
                'message' => $response['message']
            ];
            
            header('Location: /employee/view');
            exit;
        } else {
            // For GET requests, just redirect to the view page
            // This is because we're using a modal to add employees now
            header('Location: /employee/view');
            exit;
        }
    }

    public function view(){
        // Get all employee data
        $employee_data = $this->empModel->getAllWithRelations();
        
        // Get data for job titles, departments, and locations for the dropdown menus
        $job_titles = $this->jobTitleModel->getAll();
        $departments = $this->departmentModel->getAll();
        $locations = $this->locationModel->getAll();
        
        $this->view->render('employee/viewEmployees', array_merge(
            $this->setPageData('View Employees', 'employees'),
            [
                'employee_data' => $employee_data,
                'job_titles' => $job_titles,
                'departments' => $departments,
                'locations' => $locations
            ]
        ));
    }
    
    public function edit($id = null){
        if(!$id) {
            header('Location: /employee/view');
            exit;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $d = array_map('trim', $_POST);
            
            $result = $this->empModel->updateEmployee($id, $d);
            
            // Return JSON response for AJAX
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Employee updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update employee']);
                }
                exit;
            }
            
            // Regular form submission response
            if($result){
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'title' => 'Success!',
                    'message' => 'Employee updated successfully'
                ];
                header('Location: /employee/view');
                exit;
            } else {
                $this->view->render('employee/employeeForm', array_merge(
                    $this->setPageData('Edit Employee', 'employees'),
                    [
                        'error' => 'Failed to update employee',
                        'employee' => $this->empModel->getByIdWithRelations($id),
                        'job_titles' => $this->jobTitleModel->getAll(),
                        'departments' => $this->departmentModel->getAll(),
                        'locations' => $this->locationModel->getAll()
                    ]
                ));
            }
        } else {
            $employee = $this->empModel->getByIdWithRelations($id);
            if(!$employee) {
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'title' => 'Error!',
                    'message' => 'Employee not found'
                ];
                header('Location: /employee/view');
                exit;
            }
            
            $this->view->render('employee/employeeForm', array_merge(
                $this->setPageData('Edit Employee', 'employees'),
                [
                    'employee' => $employee,
                    'job_titles' => $this->jobTitleModel->getAll(),
                    'departments' => $this->departmentModel->getAll(),
                    'locations' => $this->locationModel->getAll()
                ]
            ));
        }
    }
    
    public function delete($id = null){
        $result = false;
        if($id) {
            $result = $this->empModel->deleteEmployee($id);
        }
        
        // Return JSON response for AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Employee deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete employee']);
            }
            exit;
        }
        
        // Regular response
        $_SESSION['flash'] = [
            'type' => $result ? 'success' : 'error',
            'title' => $result ? 'Success!' : 'Error!',
            'message' => $result ? 'Employee deleted successfully' : 'Failed to delete employee'
        ];
        
        header('Location: /employee/view');
        exit;
    }
}