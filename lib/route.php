<?php
class Route {
    public function __construct($url){
        $url = rtrim($url, '/');
        $url = explode('/', $url);

        // Ang unang bahagi ang controller
        $controllerName = $url[0] . 'Controller';
        require_once 'mvc/controller/' . $controllerName . '.php';

        $controllerInstance = new $controllerName();

        // Kung may action na tinukoy; kung wala, default ay "index"
        $action = isset($url[1]) ? $url[1] : 'index';
        if (method_exists($controllerInstance, $action)){
            $controllerInstance->$action();
        } else {
            echo "Method not found!";
        }
    }
}
?>
