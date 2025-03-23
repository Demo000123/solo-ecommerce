<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [
        'GET' => [
            '/' => ['controller' => 'ProductController', 'action' => 'index'],
            '/products' => ['controller' => 'ProductController', 'action' => 'index'],
            '/product' => ['controller' => 'ProductController', 'action' => 'show'],
            '/cart' => ['controller' => 'CartController', 'action' => 'index'],
        ],
        'POST' => [
            '/cart/add' => ['controller' => 'CartController', 'action' => 'add'],
            '/cart/remove' => ['controller' => 'CartController', 'action' => 'remove'],
        ]
    ];

    public function route(string $uri, string $method): void
    {
        // Remove base path from URI if needed
        $basePathArray = explode('/', $_SERVER['SCRIPT_NAME']);
        array_pop($basePathArray); // Remove index.php
        $basePath = implode('/', $basePathArray);
        $uri = str_replace($basePath, '', $uri);
        
        // Split URI into parts
        $uriParts = explode('?', $uri);
        $uri = $uriParts[0];
        
        // Normalize URI (remove trailing slashes except for root)
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }
        
        // Check if route exists
        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $controllerName = "\\App\\Controllers\\{$route['controller']}";
            $action = $route['action'];
            
            $controller = new $controllerName();
            $controller->$action();
            return;
        }
        
        // Route not found
        $this->notFound();
    }
    
    private function notFound(): void
    {
        http_response_code(404);
        require VIEW_PATH . '/layouts/404.php';
        exit;
    }
} 