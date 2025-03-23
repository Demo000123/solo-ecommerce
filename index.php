<?php
declare(strict_types=1);

// Bootstrap the application
require_once __DIR__ . '/src/config/bootstrap.php';

// Route the request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Simple router
$router = new \App\Core\Router();
$router->route($uri, $method); 