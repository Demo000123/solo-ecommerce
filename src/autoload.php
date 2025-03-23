<?php
/**
 * Solo E-commerce - Simple Autoloader
 */

spl_autoload_register(function ($class) {
    // Base directory for the namespace prefix
    $baseDir = __DIR__ . '/';
    
    // Replace namespace prefix with base directory, replace namespace
    // separators with directory separators and append .php
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    
    // Remove the App\ prefix as our files are in the src/ directory
    $file = str_replace('App/', '', $file);
    
    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
        return true;
    }
    
    return false;
}); 