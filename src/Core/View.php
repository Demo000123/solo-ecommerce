<?php

namespace App\Core;

/**
 * View Class
 * 
 * Handles view rendering
 */
class View
{
    /**
     * Render a view
     * 
     * @param string $view The view file to render
     * @param array $params Parameters to pass to the view
     * @return string The rendered view content
     */
    public static function render($view, $params = [])
    {
        return self::renderView($view, $params);
    }
    
    /**
     * Render a view with layout
     * 
     * @param string $view The view file to render
     * @param array $params Parameters to pass to the view
     * @return string The rendered view with layout
     */
    public static function renderWithLayout($view, $params = [])
    {
        $layoutContent = self::layoutContent($params);
        $viewContent = self::renderView($view, $params);
        
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    
    /**
     * Get the layout content
     * 
     * @param array $params Parameters to pass to the layout
     * @return string The layout content
     */
    private static function layoutContent($params = [])
    {
        // Extract params to make them available in the layout
        extract($params);
        
        ob_start();
        include_once ROOT_PATH . '/src/views/layouts/main.php';
        return ob_get_clean();
    }
    
    /**
     * Render a view file
     * 
     * @param string $view The view file to render
     * @param array $params Parameters to pass to the view
     * @return string The rendered view content
     */
    private static function renderView($view, $params = [])
    {
        // Extract params to make them available in the view
        extract($params);
        
        $viewPath = ROOT_PATH . '/src/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found");
        }
        
        ob_start();
        include_once $viewPath;
        return ob_get_clean();
    }
    
    /**
     * Render a partial view
     * 
     * @param string $partial The partial view file to render
     * @param array $params Parameters to pass to the partial
     * @return string The rendered partial content
     */
    public static function renderPartial($partial, $params = [])
    {
        return self::renderView('partials/' . $partial, $params);
    }
} 