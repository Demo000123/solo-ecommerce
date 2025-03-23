<?php
declare(strict_types=1);

// Application configuration
return [
    'app' => [
        'name' => 'Solo E-commerce',
        'url' => 'http://localhost/solo-ecommerce',
        'environment' => 'development'
    ],
    'database' => [
        'host' => 'localhost',
        'dbname' => 'ecommerce',
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