<?php
class Controller {
    protected $view;
    protected $models; 
    protected $pageTitle = 'MVC Application';
    protected $activeMenu = '';

    public function __construct(){
        $this->view = new View();
    }

    // ngayon direktang method na sa Controller
    public function model($name){
        $modelFile = dirname(__DIR__) . '/app/models/' . $name . '.php';
        if (! file_exists($modelFile)) {
            throw new Exception("Model file not found: {$modelFile}");
        }
        require_once $modelFile;
        return new $name;
    }

    protected function isAuthenticated() {
        if(!isset($_SESSION['user'])){
            header('Location: /auth/login');
            exit;
        }
        return true;
    }

    protected function setPageData($title = '', $activeMenu = '') {
        if ($title) $this->pageTitle = $title;
        if ($activeMenu) $this->activeMenu = $activeMenu;

        return [
            'pageTitle'  => $this->pageTitle,
            'activeMenu' => $this->activeMenu
        ];
    }
}
