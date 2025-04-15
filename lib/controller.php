<?php
namespace lib;

use lib\View;
use lib\Models;

class Controller {
    protected $view;
    protected $models;

    public function __construct(){
        $this->view = new View();
        $this->models = new Models();
    }
}
?>
