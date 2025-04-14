<?php
    class misController extends Controller {
        private $controller;
        function __construct(){
            $this->controller = new Controller();
            $this->controller->render->views('mis', "555");
        }
}
?>
