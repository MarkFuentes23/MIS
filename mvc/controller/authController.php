<?php
class authController extends \lib\Controller {
    private $authModel;

    public function __construct(){
        parent::__construct(); 
        $this->authModel = $this->models->models('authModel');
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $result = $this->authModel->register($username, $password);
            if ($result){
                header("Location: /auth/login");
                exit();
            } else {
                $data = "Registration failed. Please try again.";
                $this->view->views('register', ['error' => $data]);
            }
        } else {
            $this->view->views('register');
        }
    }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $user = $this->authModel->login($username, $password);
            if ($user){
                session_start();
                session_regenerate_id(true);
                $_SESSION['user'] = $user;
                header("Location: /auth/dashboard");
                exit();
            } else {
                $data = "Login failed. Check your credentials.";
                $this->view->views('login', ['error' => $data]);
            }
        } else {
            $this->view->views('login');
        }
    }

    public function dashboard(){
        session_start();
        if (!isset($_SESSION['user'])){
            header("Location: /auth/login");
            exit();
        }
        $data = $_SESSION['user'];
        $this->view->views('Dashboard', ['user' => $data]);
    }

    public function logout(){
        session_start();
        session_destroy();
        header("Location: /auth/login");
        exit();
    }
}
?>
