<?php
class employeeController extends \lib\Controller {
    private $employeeModel;

    public function __construct(){
        parent::__construct();
        $this->employeeModel = $this->models->models('employeeModel');
    }
    
    // Display the form to add a new employee (employeeForm.php)
    public function add(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                'firstname'  => trim($_POST['firstname']),
                'lastname'   => trim($_POST['lastname']),
                'middlename' => trim($_POST['middlename']),
                'suffix'     => trim($_POST['suffix'])
            ];
            $result = $this->employeeModel->insertEmployee($data);
            if($result){
                header("Location: /employee/viewEmployees");
                exit();
            } else {
                $error = "Failed to add employee.";
                $this->view->views('employeeForm', ['error' => $error]);
            }
        } else {
            $this->view->views('employeeForm');
        }
    }
    
    // Display the list of employees (viewEmployees.php)
    public function viewEmployees(){
        $employees = $this->employeeModel->getAll();
        $this->view->views('viewEmployees', ['employees' => $employees]);
    }
}
?>
