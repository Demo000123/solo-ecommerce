<?php

namespace App\Controllers;

use App\Core\View;

/**
 * Base Controller Class
 * 
 * Base controller that other controllers extend
 */
class Controller
{
    /**
     * Render a view
     * 
     * @param string $view The view file to render
     * @param array $params Parameters to pass to the view
     * @return string The rendered view content
     */
    protected function render($view, $params = [])
    {
        $content = View::render($view, $params);
        
        return View::renderWithLayout('layouts/main', array_merge($params, [
            'content' => $content
        ]));
    }
    
    /**
     * Render a view without a layout
     * 
     * @param string $view The view file to render
     * @param array $params Parameters to pass to the view
     * @return string The rendered view content
     */
    protected function renderPartial($view, $params = [])
    {
        return View::render($view, $params);
    }
    
    /**
     * Set a flash message
     * 
     * @param string $type The message type (success, error, info, warning)
     * @param string $message The message text
     */
    protected function setFlash($type, $message)
    {
        $_SESSION["{$type}_message"] = $message;
    }
    
    /**
     * Redirect to another URL
     * 
     * @param string $url The URL to redirect to
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Send JSON response
     * 
     * @param mixed $data The data to send
     * @param int $statusCode The HTTP status code
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Get the current user ID
     * 
     * @return int|null The user ID or null if not logged in
     */
    protected function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool True if user is logged in
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user is admin
     * 
     * @return bool True if user is admin
     */
    protected function isAdmin()
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    /**
     * Require user to be logged in
     * 
     * Redirects to login page if not logged in
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->setFlash('error', 'Please login to access this page');
            $this->redirect('/login');
        }
    }
    
    /**
     * Require user to be admin
     * 
     * Redirects to home page if not admin
     */
    protected function requireAdmin()
    {
        if (!$this->isAdmin()) {
            $this->setFlash('error', 'You do not have permission to access this page');
            $this->redirect('/');
        }
    }
} 