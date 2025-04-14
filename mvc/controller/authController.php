<?php
require_once 'lib/controller.php';
use lib\Controller;

class authController extends Controller {
    private $authModel;
    
    public function __construct(){
        parent::__construct();
        $this->authModel = $this->run->models('authModel');
    }
    
    public function login(){
        session_start();
        if(isset($_SESSION['user'])){
            header("Location: /main/index");
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->authModel->login($username, $password);
            if($user){
                $_SESSION['user'] = $user;
                header("Location: /main/index");
                exit();
            } else {
                $data['error'] = "Invalid username or password.";
                $this->render->views('login', $data);
            }
        } else {
            $this->render->views('login');
        }
    }
    
    public function logout(){
        session_start();
        session_destroy();
        header("Location: /auth/login");
        exit();
    }
}
?>
