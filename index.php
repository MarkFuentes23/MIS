<?php
// Autoload classes under /lib, /controllers, /models
spl_autoload_register(function($class){
    $paths = [__DIR__.'/lib/', __DIR__.'/app/controllers/', __DIR__.'/app/models/'];
    foreach($paths as $path){
        $file = $path . $class . '.php';
        if(file_exists($file)){
            require_once $file;
            return;
        }
    }
});

session_start();

// Parse URL; default to auth/login instead of dashboard/index
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : 'auth/login';
$segments = explode('/', $url);

// Controller & method
$controllerName = ucfirst(array_shift($segments)) . 'Controller';
$method         = array_shift($segments) ?: 'index';
$params         = $segments;

if (
    !isset($_SESSION['user']) &&                     // no user in session
    $controllerName !== 'AuthController'             // not already handling login/register
) {
    header('Location: /auth/login');
    exit;
}

// Dispatch
if (class_exists($controllerName)) {
    $controller = new $controllerName;
    if (method_exists($controller, $method)) {
        call_user_func_array([$controller, $method], $params);
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Method $method not found.";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "Controller $controllerName not found.";
}
