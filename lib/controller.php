<?php
namespace lib;
use lib\View;
use lib\Models;

class Controller {
    protected $render;
    protected $run;

    public function __construct(){
        $this->render = new View();
        $this->run = new Models();
    }
}
?>
