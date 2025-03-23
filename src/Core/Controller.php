<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        
        require VIEW_PATH . '/layouts/main.php';
    }
    
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
    
    protected function getParam(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
    
    protected function postParam(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
    
    protected function filterInput(string $data): string
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
} 