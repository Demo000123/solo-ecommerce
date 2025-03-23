<?php

namespace App\Core;

/**
 * Response Class
 * 
 * Handles HTTP responses
 */
class Response
{
    /**
     * Set the HTTP status code
     * 
     * @param int $code The HTTP status code
     */
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }
    
    /**
     * Redirect to another URL
     * 
     * @param string $url The URL to redirect to
     */
    public function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
    
    /**
     * Send a JSON response
     * 
     * @param mixed $data The data to send
     * @param int $statusCode The HTTP status code
     */
    public function json($data, $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Send an HTML response
     * 
     * @param string $html The HTML content
     * @param int $statusCode The HTTP status code
     */
    public function html($html, $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
        header('Content-Type: text/html');
        echo $html;
        exit;
    }
    
    /**
     * Send a file download response
     * 
     * @param string $filePath The file path
     * @param string $fileName The file name
     * @param string $mimeType The MIME type
     */
    public function download($filePath, $fileName = null, $mimeType = null)
    {
        if (!file_exists($filePath)) {
            $this->setStatusCode(404);
            return;
        }
        
        $fileName = $fileName ?? basename($filePath);
        $mimeType = $mimeType ?? mime_content_type($filePath);
        
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
        exit;
    }
    
    /**
     * Set a response header
     * 
     * @param string $name The header name
     * @param string $value The header value
     */
    public function setHeader($name, $value)
    {
        header("{$name}: {$value}");
    }
} 