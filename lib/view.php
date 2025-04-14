<?php
namespace lib;

class View {
    public function views($filename, $data = []){
        // Pinapasa ang data para magamit sa view
        extract($data);
        require_once 'mvc/view/' . $filename . '.php';
    }
}
?>
