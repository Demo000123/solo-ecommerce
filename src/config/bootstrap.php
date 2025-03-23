<?php
declare(strict_types=1);

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Define base paths
define('BASE_PATH', dirname(__DIR__, 2));
define('SRC_PATH', BASE_PATH . '/src');
define('VIEW_PATH', SRC_PATH . '/views');
define('CONFIG_PATH', SRC_PATH . '/config');

// Simple autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = SRC_PATH . '/';
    
    // Check if the class uses the namespace prefix
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Get the relative class name
    $relative_class = substr($class, $len);
    
    // Replace namespace separators with directory separators
    // and append .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Start session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 