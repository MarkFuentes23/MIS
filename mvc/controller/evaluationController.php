<?php
class evaluationController extends \lib\Controller {
    private $evaluationModel;
    private $employeeModel;
    private $jobTitleModel;
    private $departmentModel;

    public function __construct(){
        parent::__construct();
        $this->evaluationModel = $this->models->models('evaluationModel');
        $this->employeeModel   = $this->models->models('employeeModel');
        $this->jobTitleModel   = $this->models->models('job_titleModel');
        $this->departmentModel = $this->models->models('departmentModel');
    }

    // Display the evaluation form page (overallForm.php)
    public function index(){
        $employees   = $this->employeeModel->getAll();
        $jobTitles   = $this->jobTitleModel->getAll();
        $departments = $this->departmentModel->getAll();
        $this->view->views('overallForm', [
            'employees'   => $employees,
            'jobTitles'   => $jobTitles,
            'departments' => $departments
        ]);
    }

    // Save evaluation record from overallForm.php
    public function save(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $employee_id            = $_POST['employee_id'];
            $position_title_id      = $_POST['position_title_id'];
            $reviewer_id            = $_POST['reviewer_id'];
            $evaluation_period      = $_POST['evaluation_period'];
            $department_id          = $_POST['department_id'];
            $reviewer_designation_id= $_POST['reviewer_designation_id'];

            $res = $this->evaluationModel->saveRecord(
                $employee_id,
                $position_title_id,
                $reviewer_id,
                $evaluation_period,
                $department_id,
                $reviewer_designation_id
            );
            if($res){
                header("Location: /evaluation/list");
                exit();
            } else {
                echo "Error saving record!";
            }
        }
    }

    // List all evaluation records (each clickable for details)
    public function list(){
        $records = $this->evaluationModel->getAllRecords();
        $this->view->views('listRecords', ['records' => $records]);
    }
}
?>
