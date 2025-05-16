<?php
// app/controllers/AuthController.php

class AuthController extends Controller {
    /** @var UserModel */
    private $userModel;

    public function __construct(){
        parent::__construct();
        // use the base Controller::model() method directly
        $this->userModel = $this->model('UserModel');
    }

    public function register(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ok = $this->userModel->register(
                trim($_POST['username']),
                trim($_POST['password'])
            );

            if ($ok) {
                header('Location: /auth/login');
                exit;
            } else {
                $this->view->render('auth/register', [
                    'error'     => 'Username already exists',
                    'pageTitle' => 'Register'
                ]);
            }

        } else {
            // GET: show registration form
            $this->view->render('auth/register', $this->setPageData('Register'));
        }
    }

    public function login(){
        // if already logged in, go to dashboard
        if (isset($_SESSION['user'])) {
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userModel->login(
                trim($_POST['username']),
                trim($_POST['password'])
            );

            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: /dashboard');
                exit;
            } else {
                $this->view->render('auth/login', [
                    'error'     => 'Invalid login credentials',
                    'pageTitle' => 'Login'
                ]);
            }

        } else {
            // GET: show login form
            $this->view->render('auth/login', $this->setPageData('Login'));
        }
    }

    public function logout(){
        session_destroy();
        header('Location: /auth/login');
        exit;
    }
}
