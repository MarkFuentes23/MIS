<?php
class DashboardController extends Controller {
    private $userModel;
    private $employeeModel;

    public function __construct(){
        parent::__construct();
        $this->isAuthenticated();

        // pareho ang paraan ng pag-load ng models
        $this->userModel     = $this->model('UserModel');
        $this->employeeModel = $this->model('EmployeeModel');
    }

    public function index(){
        // Kunin yung mga stats para sa dashboard
        $stats = [
            'totalEmployees'  => $this->employeeModel->getCount(),
            'recentEmployees' => $this->employeeModel->getRecent(5),
            'monthlyStats'    => $this->employeeModel->getMonthlyStats()
        ];
        
        // I-render ang view kasama ang dashboard data
        $this->view->render(
            'dashboard/index',
            array_merge(
                $this->setPageData('Dashboard', 'dashboard'),
                $stats
            )
        );
    }
}
