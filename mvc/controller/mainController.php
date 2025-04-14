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
        // Pagkatapos mag-login, idirekta ang user papunta sa listahan ng evaluations
        header("Location: /evaluation/listRecords");
        exit();
    }
}
?>
