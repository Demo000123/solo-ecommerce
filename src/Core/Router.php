<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Router Class
 * 
 * Handles URL routing and dispatching to controllers
 */
class Router
{
    /**
     * @var array Array of registered routes
     */
    private $routes = [];
    
    /**
     * @var Request Request instance
     */
    private $request;
    
    /**
     * @var Response Response instance
     */
    private $response;
    
    /**
     * Constructor
     * 
     * @param Request $request Request instance
     * @param Response $response Response instance
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    /**
     * Register a GET route
     * 
     * @param string $path The route path
     * @param mixed $callback The callback to execute
     */
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    
    /**
     * Register a POST route
     * 
     * @param string $path The route path
     * @param mixed $callback The callback to execute
     */
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }
    
    /**
     * Register a PUT route
     * 
     * @param string $path The route path
     * @param mixed $callback The callback to execute
     */
    public function put($path, $callback)
    {
        $this->routes['put'][$path] = $callback;
    }
    
    /**
     * Register a DELETE route
     * 
     * @param string $path The route path
     * @param mixed $callback The callback to execute
     */
    public function delete($path, $callback)
    {
        $this->routes['delete'][$path] = $callback;
    }
    
    /**
     * Resolve the current route
     * 
     * Find and execute the appropriate callback for the current request
     * 
     * @throws RouteNotFoundException When route is not found
     */
    public function resolve()
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();
        $callback = $this->findRoute($method, $path);
        
        if ($callback === false) {
            throw new RouteNotFoundException("Route {$path} not found", 404);
        }
        
        if (is_string($callback)) {
            return $this->callControllerAction($callback);
        }
        
        return call_user_func($callback, $this->request, $this->response);
    }
    
    /**
     * Find a registered route that matches the given method and path
     * 
     * @param string $method The HTTP method
     * @param string $path The route path
     * @return mixed The callback for the route or false if not found
     */
    private function findRoute($method, $path)
    {
        $method = strtolower($method);
        
        if (!isset($this->routes[$method])) {
            return false;
        }
        
        // Check for exact match
        if (isset($this->routes[$method][$path])) {
            return $this->routes[$method][$path];
        }
        
        // Check for route with parameters
        foreach ($this->routes[$method] as $route => $callback) {
            $pattern = $this->convertRouteToRegex($route);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $this->request->setRouteParams($matches);
                return $callback;
            }
        }
        
        return false;
    }
    
    /**
     * Convert a route pattern to a regular expression
     * 
     * @param string $route The route pattern
     * @return string The regular expression
     */
    private function convertRouteToRegex($route)
    {
        if (strpos($route, '{') === false) {
            return "#^" . $route . "$#";
        }
        
        $route = preg_replace('/{([a-zA-Z0-9_]+)}/', '([^/]+)', $route);
        return "#^" . $route . "$#";
    }
    
    /**
     * Call a controller action
     * 
     * @param string $callback The controller action in format "Controller@action"
     * @return mixed The result of the controller action
     * @throws \Exception If controller or action not found
     */
    private function callControllerAction($callback)
    {
        [$controller, $action] = explode('@', $callback);
        
        if (strpos($controller, '\\') === false) {
            $controller = "\\App\\Controllers\\{$controller}";
        } else {
            $controller = "\\App\\Controllers\\{$controller}";
        }
        
        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} not found");
        }
        
        $controllerInstance = new $controller();
        
        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception("Action {$action} not found in controller {$controller}");
        }
        
        $params = $this->request->getRouteParams();
        return call_user_func_array([$controllerInstance, $action], $params);
    }
} 