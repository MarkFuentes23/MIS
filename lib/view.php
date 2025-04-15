<?php
namespace lib;

class View {
    public function views($filename, $data = []){
        extract($data);
        require_once 'mvc/view/' . $filename . '.php';
    }
}
?>
