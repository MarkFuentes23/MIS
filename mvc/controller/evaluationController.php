<?php
require_once 'lib/controller.php';
use lib\Controller;

class evaluationController extends Controller {
    private $evaluationModel;
    private $employeeModel;
    private $jobTitleModel;
    private $departmentModel;
    
    public function __construct(){
        parent::__construct();
        $this->evaluationModel = $this->run->models('evaluationModel');
        $this->employeeModel = $this->run->models('employeeModel');
        $this->jobTitleModel = $this->run->models('jobTitleModel');
        $this->departmentModel = $this->run->models('departmentModel');
    }
    
    // Ipakita ang form para sa evaluation
    public function form(){
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location: /auth/login");
            exit();
        }
        $data = [];
        // Kunin ang data para sa dropdowns mula sa iba't ibang tables
        $data['employees'] = $this->employeeModel->getAll();
        $data['job_titles'] = $this->jobTitleModel->getAll();
        $data['departments'] = $this->departmentModel->getAll();
        
        $this->render->views('evaluation_form', $data);
    }
    
    // Save function para sa evaluation form
    public function save(){
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location: /auth/login");
            exit();
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $evaluationData = [
                'employee_id' => $_POST['employee_id'] ?? null,
                'job_title_id' => $_POST['job_title_id'] ?? null,
                'reviewer_id' => $_POST['reviewer_id'] ?? null,
                'evaluation_period' => $_POST['evaluation_period'] ?? null,
                'department_id' => $_POST['department_id'] ?? null,
                'reviewer_designation_id' => $_POST['reviewer_designation_id'] ?? null,
            ];
            
            $result = $this->evaluationModel->saveEvaluation($evaluationData);
            if($result){
                header("Location: /evaluation/listRecords");
                exit();
            } else {
                $data['error'] = "Failed to save evaluation. Please try again.";
                $data['employees'] = $this->employeeModel->getAll();
                $data['job_titles'] = $this->jobTitleModel->getAll();
                $data['departments'] = $this->departmentModel->getAll();
                $this->render->views('evaluation_form', $data);
            }
        }
    }
    
    // Listahan ng lahat ng evaluation records
    public function listRecords(){
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location: /auth/login");
            exit();
        }
        $data = [];
        $data['records'] = $this->evaluationModel->getAllRecords();
        $this->render->views('evaluation_list', $data);
    }
    
    // Ipakita ang detalye ng isang record
    public function viewRecord(){
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location: /auth/login");
            exit();
        }
        $record_id = $_GET['id'] ?? null;
        if($record_id){
            $data['record'] = $this->evaluationModel->getRecordById($record_id);
            $this->render->views('evaluation_detail', $data);
        } else {
            echo "Record not found.";
        }
    }
}
?>
