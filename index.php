<?php
/**
 * Solo E-commerce - Front Controller
 * 
 * This file serves as the entry point for all requests to the application
 */

// Define root path
define('ROOT_PATH', __DIR__);

// Define application environment
define('APP_ENV', getenv('APP_ENV') ?: 'development');

// Set error reporting based on environment
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('DEBUG_MODE', true);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    define('DEBUG_MODE', false);
}

// Initialize session
session_start();

// Load configuration and autoloader
require_once 'src/config/config.php';
require_once 'src/autoload.php';

// Initialize the application
$app = new \App\Core\Application();

// Process the request
$app->run(); 