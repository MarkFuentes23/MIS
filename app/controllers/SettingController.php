<?php
class SettingController extends Controller {
    private $settingModel;
    private $employeeModel;

    public function __construct() {
        parent::__construct();
        $this->settingModel = $this->model('SettingModel');
        $this->employeeModel = $this->model('EmployeeModel');
        
    }

    // Main records page showing all tabs
    public function view() {
        $this->isAuthenticated();
        
        $data = $this->setPageData('Records Management', 'records');
        $data['employees'] = $this->settingModel->getAllEmployees();
        $data['job_titles'] = $this->settingModel->getAllJobTitles();
        $data['departments'] = $this->settingModel->getAllDepartments();
        $data['locations'] = $this->settingModel->getAllLocations();
        $data['kras'] = $this->settingModel->getAllKras();
        
        // Change this line to use the correct view path
        $this->view->render('employee/viewEmployees', $data);
    }
    

    public function add() {
        $this->isAuthenticated();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employeeData = [
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'middlename' => !empty($_POST['middlename']) ? trim($_POST['middlename']) : null,
                'suffix' => !empty($_POST['suffix']) ? trim($_POST['suffix']) : null,
                'location' => trim($_POST['location']),
                'department' => trim($_POST['department']),
                'job_title' => trim($_POST['job_title']),
                'evaluation' => !empty($_POST['evaluation']) ? floatval($_POST['evaluation']) : 0.0
            ];
            
            if ($this->settingModel->addEmployee($employeeData)) {
                // Redirect to your view route
                header('Location: /setting/view');
                exit;
            } else {
                // Handle error
                $data = $this->setPageData('Error', 'records');
                $data['error'] = 'Failed to add employee. Please try again.';
                $this->view->render('errors/error', $data);
            }
        } else {
            // If not POST, redirect to view page
            header('Location: /setting/view');
            exit;
        }
    }
    
    public function edit($id = null) {
        $this->isAuthenticated();
        
        if (!$id) {
            header('Location: /records');
            exit;
        }
        
        $employee = $this->settingModel->getEmployeeById($id);
        
        if (!$employee) {
            header('Location: /records');
            exit;
        }
        
        $data = $this->setPageData('Edit Employee', 'records');
        $data['employee'] = $employee;
        $data['job_titles'] = $this->settingModel->getAllJobTitles();
        $data['departments'] = $this->settingModel->getAllDepartments();
        $data['locations'] = $this->settingModel->getAllLocations();
        
        $this->view->render('employee/edit', $data);
    }
    
    public function update() {
        $this->isAuthenticated();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employeeData = [
                'id' => intval($_POST['id']),
                'firstname' => trim($_POST['firstname']),
                'lastname' => trim($_POST['lastname']),
                'middlename' => !empty($_POST['middlename']) ? trim($_POST['middlename']) : null,
                'suffix' => !empty($_POST['suffix']) ? trim($_POST['suffix']) : null,
                'location' => trim($_POST['location']),
                'department' => trim($_POST['department']),
                'job_title' => trim($_POST['job_title']),
                'evaluation' => !empty($_POST['evaluation']) ? floatval($_POST['evaluation']) : 0.0
            ];
            
            if ($this->settingModel->updateEmployee($employeeData)) {
                // Redirect to your view route
                header('Location: /setting/view');
                exit;
            } else {
                // Handle error
                $data = $this->setPageData('Error', 'records');
                $data['error'] = 'Failed to update employee. Please try again.';
                $this->view->render('errors/error', $data);
            }
        } else {
            // If not POST, redirect to view page
            header('Location: /setting/view');
            exit;
        }
    }
    
    public function delete($id = null) {
        $this->isAuthenticated();
        
        if ($id && $this->settingModel->deleteEmployee($id)) {
            header('Location: /setting/view');
            exit;
        } else {
            $data = $this->setPageData('Error', 'records');
            $data['error'] = 'Failed to delete employee. Please try again.';
            $this->view->render('errors/error', $data);
        }
    }
    
    // Job Title Methods
    public function jobTitle($action = null, $id = null) {
        $this->isAuthenticated();
        
        switch ($action) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $jobTitle = trim($_POST['job_title']);
                    
                    if ($this->settingModel->addJobTitle($jobTitle)) {
                        header('Location: /setting/view');
                        exit;
                    } else {
                        $data = $this->setPageData('Error', 'records');
                        $data['error'] = 'Failed to add job title. Please try again.';
                        $this->view->render('errors/error', $data);
                    }
                }
                break;
                
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['id'];
                    $jobTitle = trim($_POST['job_title']);
                    
                    if ($this->settingModel->updateJobTitle($id, $jobTitle)) {
                        header('Location: /setting/view');
                        exit;
                    } else {
                        $data = $this->setPageData('Error', 'records');
                        $data['error'] = 'Failed to update job title. Please try again.';
                        $this->view->render('errors/error', $data);
                    }
                }
                break;
                
            case 'delete':
                if ($id && $this->settingModel->deleteJobTitle($id)) {
                    header('Location: /setting/view');
                    exit;
                } else {
                    $data = $this->setPageData('Error', 'records');
                    $data['error'] = 'Failed to delete job title. Please try again.';
                    $this->view->render('errors/error', $data);
                }
                break;
                
            default:
                header('Location: /setting/view');
                exit;
        }
    }
    
    // Department Methods
    public function department($action = null, $id = null) {
        $this->isAuthenticated();
        
        switch ($action) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $department = trim($_POST['department']);
                    
                    if ($this->settingModel->addDepartment($department)) {
                        header('Location: /setting/view');
                        exit;
                    } else {
                        $data = $this->setPageData('Error', 'records');
                        $data['error'] = 'Failed to add department. Please try again.';
                        $this->view->render('errors/error', $data);
                    }
                }
                break;
                
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['id'];
                    $department = trim($_POST['department']);
                    
                    if ($this->settingModel->updateDepartment($id, $department)) {
                        header('Location: /setting/view');
                        exit;
                    } else {
                        $data = $this->setPageData('Error', 'records');
                        $data['error'] = 'Failed to update department. Please try again.';
                        $this->view->render('errors/error', $data);
                    }
                }
                break;
                
            case 'delete':
                if ($id && $this->settingModel->deleteDepartment($id)) {
                    header('Location: /setting/view');
                    exit;
                } else {
                    $data = $this->setPageData('Error', 'records');
                    $data['error'] = 'Failed to delete department. Please try again.';
                    $this->view->render('errors/error', $data);
                }
                break;
                
            default:
                header('Location: /setting/view');
                exit;
        }
    }



    
    // Location Methods
    public function location($action = null, $id = null) {
        $this->isAuthenticated();
        
        switch ($action) {
            case 'add':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $location = trim($_POST['location']);
                    
                    if ($this->settingModel->addLocation($location)) {
                        header('Location: /setting/view');
                        exit;
                    } else {
                        $data = $this->setPageData('Error', 'records');
                        $data['error'] = 'Failed to add location. Please try again.';
                        $this->view->render('errors/error', $data);
                    }
                }
                break;
                
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['id'];
                    $location = trim($_POST['location']);
                    
                    if ($this->settingModel->updateLocation($id, $location)) {
                        header('Location: /setting/view');
                        exit;
                    } else {
                        $data = $this->setPageData('Error', 'records');
                        $data['error'] = 'Failed to update location. Please try again.';
                        $this->view->render('errors/error', $data);
                    }
                }
                break;
                
            case 'delete':
                if ($id && $this->settingModel->deleteLocation($id)) {
                    header('Location: /setting/view');
                    exit;
                } else {
                    $data = $this->setPageData('Error', 'records');
                    $data['error'] = 'Failed to delete location. Please try again.';
                    $this->view->render('errors/error', $data);
                }
                break;
                
            default:
                header('Location: /setting/view');
                exit;
        }
    }

        // kra // Add this new KRA method to the SettingController class

// KRA Methods
public function kra($action = null, $id = null) {
    $this->isAuthenticated();
    
    switch ($action) {
        case 'add':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $kra = trim($_POST['kra']);
                
                if ($this->settingModel->addKra($kra)) {
                    header('Location: /setting/view');
                    exit;
                } else {
                    $data = $this->setPageData('Error', 'records');
                    $data['error'] = 'Failed to add KRA. Please try again.';
                    $this->view->render('errors/error', $data);
                }
            }
            break;
            
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];
                $kra = trim($_POST['kra']);
                
                if ($this->settingModel->updateKra($id, $kra)) {
                    header('Location: /setting/view');
                    exit;
                } else {
                    $data = $this->setPageData('Error', 'records');
                    $data['error'] = 'Failed to update KRA. Please try again.';
                    $this->view->render('errors/error', $data);
                }
            }
            break;
            
        case 'delete':
            if ($id && $this->settingModel->deleteKra($id)) {
                header('Location: /setting/view');
                exit;
            } else {
                $data = $this->setPageData('Error', 'records');
                $data['error'] = 'Failed to delete KRA. Please try again.';
                $this->view->render('errors/error', $data);
            }
            break;
            
        default:
            header('Location: /setting/view');
            exit;
    }
}
}