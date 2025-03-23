<?php

namespace App\Core;

/**
 * RouteNotFoundException
 * 
 * Exception thrown when a route is not found
 */
class RouteNotFoundException extends \Exception
{
    /**
     * Constructor
     * 
     * @param string $message Error message
     * @param int $code Error code
     * @param \Throwable $previous Previous exception
     */
    public function __construct($message = "Route not found", $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
} 