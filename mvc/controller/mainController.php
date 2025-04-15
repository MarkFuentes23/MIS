<?php
require_once 'lib/controller.php';
use lib\Controller;

class mainController extends Controller {
    public function index(){
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location: /auth/login");
            exit();
        }
        header("Location: /main/dashboard");
        exit();
    }

    public function dashboard(){
        session_start();
        if(!isset($_SESSION['user'])){
            header("Location: /auth/login");
            exit();
        }
        $this->view->views('Dashboard'); 
    }
}
?>
