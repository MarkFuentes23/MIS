<?php
namespace lib;

class Models {
    public function models($filename){
        require_once 'mvc/model/' . $filename . '.php';
        return new $filename();
    }
}
?>
