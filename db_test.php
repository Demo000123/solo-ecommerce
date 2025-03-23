<?php
declare(strict_types=1);

// Include the bootstrap file to load autoloader and configuration
require_once __DIR__ . '/src/config/bootstrap.php';

// Database connection test
try {
    // Get the database connection using the singleton instance
    $db = \App\Core\Database::getInstance();
    
    // Test query to check if connection is working
    $stmt = $db->query("SELECT DATABASE() as current_database");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Output connection success information
    echo '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">';
    echo '<h1 style="color: #3a86ff;">Database Connection Test</h1>';
    echo '<div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">';
    echo '<strong>Success:</strong> Connected to the database successfully!';
    echo '</div>';
    
    echo '<h2>Connection Information:</h2>';
    echo '<ul>';
    echo '<li><strong>Current Database:</strong> ' . $result['current_database'] . '</li>';
    echo '<li><strong>PHP Version:</strong> ' . phpversion() . '</li>';
    echo '<li><strong>PDO Driver:</strong> ' . $db->getAttribute(PDO::ATTR_DRIVER_NAME) . '</li>';
    echo '<li><strong>Server Version:</strong> ' . $db->getAttribute(PDO::ATTR_SERVER_VERSION) . '</li>';
    echo '</ul>';
    
    // Test product count
    $productStmt = $db->query("SELECT COUNT(*) as product_count FROM products");
    $productCount = $productStmt->fetch(PDO::FETCH_ASSOC);
    
    echo '<h2>Database Stats:</h2>';
    echo '<ul>';
    echo '<li><strong>Products in database:</strong> ' . $productCount['product_count'] . '</li>';
    echo '</ul>';
    
    echo '<div style="margin-top: 30px;">';
    echo '<a href="/" style="display: inline-block; background-color: #3a86ff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Go to Homepage</a>';
    echo '</div>';
    
} catch (PDOException $e) {
    // Display error information
    echo '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">';
    echo '<h1 style="color: #dc3545;">Database Connection Error</h1>';
    echo '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">';
    echo '<strong>Error:</strong> Could not connect to the database.';
    echo '</div>';
    
    echo '<h2>Error Details:</h2>';
    echo '<p style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; font-family: monospace;">';
    echo $e->getMessage();
    echo '</p>';
    
    // Display troubleshooting tips
    echo '<h2>Troubleshooting Tips:</h2>';
    echo '<ol>';
    echo '<li>Check if the MySQL server is running.</li>';
    echo '<li>Verify the database credentials in <code>src/config/config.php</code>.</li>';
    echo '<li>Make sure the database "ecommerce" exists.</li>';
    echo '<li>Check if the MySQL user has proper permissions.</li>';
    echo '<li>Run the database setup script: <code>mysql -u root -p < database/ecommerce.sql</code></li>';
    echo '</ol>';
}
echo '</div>'; 