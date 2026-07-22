<?php
/**
 * Router - Handles URL routing and controller dispatching
 */
class Router
{
    private $routes = [];
    private $params = [];
    
    public function __construct()
    {
        $this->routes = require SRC_PATH . '/routes.php';
    }
    
    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');
        
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            return $this->executeRoute($route);
        }
        
        http_response_code(404);
        die('Route not found: ' . $method . ' ' . $uri);
    }
    
    private function executeRoute($route)
    {
        list($controller, $method) = $route;
        $controllerPath = CONTROLLERS_PATH . '/' . $controller . '.php';
        
        if (!file_exists($controllerPath)) {
            die("Controller not found: $controller");
        }
        
        require_once $controllerPath;
        $instance = new $controller();
        
        if (!method_exists($instance, $method)) {
            die("Method not found: $controller::$method");
        }
        
        return $instance->$method();
    }
}
