<?php

namespace App\Core;

/**
 * Request Class
 * 
 * Handles HTTP request data
 */
class Request
{
    /**
     * @var array Route parameters from URL
     */
    private $routeParams = [];
    
    /**
     * Get the request path
     * 
     * @return string The request path
     */
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        
        if ($position === false) {
            return $path;
        }
        
        return substr($path, 0, $position);
    }
    
    /**
     * Get the request method
     * 
     * @return string The HTTP method
     */
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * Get all request data (GET/POST/JSON)
     * 
     * @return array The request data
     */
    public function getBody()
    {
        $body = [];
        
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        
        if ($this->getMethod() === 'post') {
            // Check for JSON content
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            
            if (strpos($contentType, 'application/json') !== false) {
                $jsonData = file_get_contents('php://input');
                $body = json_decode($jsonData, true) ?? [];
            } else {
                foreach ($_POST as $key => $value) {
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
        
        return $body;
    }
    
    /**
     * Get a specific request input value
     * 
     * @param string $key The input key
     * @param mixed $default Default value if not found
     * @return mixed The input value or default
     */
    public function input($key, $default = null)
    {
        $body = $this->getBody();
        return $body[$key] ?? $default;
    }
    
    /**
     * Check if input exists
     * 
     * @param string $key The input key
     * @return bool True if input exists
     */
    public function has($key)
    {
        $body = $this->getBody();
        return isset($body[$key]);
    }
    
    /**
     * Set route parameters
     * 
     * @param array $params The route parameters
     */
    public function setRouteParams(array $params)
    {
        $this->routeParams = $params;
    }
    
    /**
     * Get route parameters
     * 
     * @return array The route parameters
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }
    
    /**
     * Get a specific route parameter
     * 
     * @param string $key The parameter key
     * @param mixed $default Default value if not found
     * @return mixed The parameter value or default
     */
    public function param($key, $default = null)
    {
        return $this->routeParams[$key] ?? $default;
    }
    
    /**
     * Check if the request is Ajax
     * 
     * @return bool True if the request is Ajax
     */
    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Get server value
     * 
     * @param string $key The server key
     * @param mixed $default Default value if not found
     * @return mixed The server value or default
     */
    public function server($key, $default = null)
    {
        return $_SERVER[strtoupper($key)] ?? $default;
    }
} 