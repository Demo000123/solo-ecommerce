<?php
/**
 * Solo E-commerce - Configuration File
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'solo_ecommerce');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Path configurations
define('BASE_URL', 'http://localhost/solo-ecommerce');
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads');
define('VIEW_PATH', ROOT_PATH . '/src/views');

// Session configuration
define('SESSION_NAME', 'solo_ecommerce_session');
define('SESSION_LIFETIME', 7200); // 2 hours

// Email configuration
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'noreply@example.com');
define('MAIL_PASSWORD', 'your-password');
define('MAIL_FROM_ADDRESS', 'noreply@example.com');
define('MAIL_FROM_NAME', 'Solo E-commerce');

// Other settings
define('SITE_NAME', 'Solo E-commerce');
define('PAGINATION_PER_PAGE', 12);
define('SALT', 'change-this-to-a-random-string');

// Set timezone
date_default_timezone_set('UTC');

// Application configuration
return [
    'app' => [
        'name' => 'Solo E-commerce',
        'url' => 'http://localhost/solo-ecommerce',
        'environment' => 'development'
    ],
    'database' => [
        'host' => 'localhost',
        'dbname' => 'solo_ecommerce',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ]
]; 