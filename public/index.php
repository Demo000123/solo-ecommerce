<?php
// Router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

// Get the base directory after localhost
$baseDir = isset($uri[0]) ? $uri[0] : '';

// If we're in a subdirectory on localhost (like localhost/solo-ecommerce)
if ($baseDir === 'solo-ecommerce') {
    // Remove the project folder name from the uri array
    array_shift($uri);
}

// If the URI is empty (i.e., root URL), load the home page
if (empty($uri[0])) {
    require_once __DIR__ . '/../src/controllers/HomeController.php';
    $controller = new \App\Controllers\HomeController();
    $controller->index();
    exit;
}

// Process other routes
switch ($uri[0]) {
    case 'products':
        require_once __DIR__ . '/../src/controllers/ProductController.php';
        $controller = new \App\Controllers\ProductController();
        
        if (isset($uri[1]) && is_numeric($uri[1])) {
            $_GET['id'] = $uri[1]; // Set as GET parameter instead of passing directly
            $controller->show(); // Product detail
        } else {
            $controller->index(); // Product list
        }
        break;
        
    case 'categories':
        require_once __DIR__ . '/../src/controllers/CategoryController.php';
        $controller = new \App\Controllers\CategoryController();
        $controller->index();
        break;
        
    case 'home':
        require_once __DIR__ . '/../src/controllers/HomeController.php';
        $controller = new \App\Controllers\HomeController();
        $controller->index();
        break;
        
    case 'public':
        // Serve static files
        $filePath = __DIR__ . implode('/', $uri);
        
        if (file_exists($filePath) && is_file($filePath)) {
            // Determine content type
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            switch ($extension) {
                case 'css':
                    header('Content-Type: text/css');
                    break;
                case 'js':
                    header('Content-Type: application/javascript');
                    break;
                case 'jpg':
                case 'jpeg':
                    header('Content-Type: image/jpeg');
                    break;
                case 'png':
                    header('Content-Type: image/png');
                    break;
                case 'gif':
                    header('Content-Type: image/gif');
                    break;
                case 'svg':
                    header('Content-Type: image/svg+xml');
                    break;
            }
            
            readfile($filePath);
            exit;
        }
        
        // If file doesn't exist, continue to 404
        
    default:
        // 404 - Not Found
        header("HTTP/1.0 404 Not Found");
        echo '404 - Page Not Found';
        break;
} 