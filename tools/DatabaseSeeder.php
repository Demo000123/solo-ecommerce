<?php
/**
 * Database Seeder Script
 * 
 * This script populates the database with sample data for testing
 * It can be run from the command line with: php tools/DatabaseSeeder.php
 */

// Load database configuration
require_once __DIR__ . '/../src/config/database.php';

class DatabaseSeeder
{
    private $db;
    private $imageSourceDir;
    private $imageTargetDir;

    public function __construct()
    {
        $this->db = \App\Config\Database::getInstance()->getConnection();
        $this->imageSourceDir = __DIR__ . '/sample-images';
        $this->imageTargetDir = __DIR__ . '/../public/images';
        
        // Create the images directory if it doesn't exist
        if (!file_exists($this->imageTargetDir)) {
            mkdir($this->imageTargetDir, 0755, true);
        }
    }

    public function run()
    {
        echo "Starting database seeding...\n";
        
        // First drop and recreate tables
        $this->resetTables();
        
        // Seed categories
        $this->seedCategories();
        
        // Seed products
        $this->seedProducts();
        
        echo "Database seeding completed successfully!\n";
    }

    private function resetTables()
    {
        echo "Resetting database tables...\n";
        
        // Drop existing tables
        $this->db->exec('DROP TABLE IF EXISTS products');
        $this->db->exec('DROP TABLE IF EXISTS categories');
        
        // Create categories table
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ');
        
        // Create products table
        $this->db->exec('
            CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                price DECIMAL(10, 2) NOT NULL,
                image VARCHAR(255) NOT NULL,
                stock INT NOT NULL DEFAULT 0,
                category_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (category_id) REFERENCES categories(id)
            )
        ');
        
        // Create indexes for faster searching
        $this->db->exec('CREATE INDEX idx_product_category ON products(category_id)');
        $this->db->exec('CREATE INDEX idx_product_name ON products(name)');
        
        echo "Tables reset successfully.\n";
    }

    private function seedCategories()
    {
        echo "Seeding categories...\n";
        
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Cutting-edge technology and gadgets for modern living'
            ],
            [
                'name' => 'Home & Kitchen',
                'description' => 'Transform your living space with our essential products'
            ],
            [
                'name' => 'Clothing',
                'description' => 'Stay stylish with our latest fashion collections'
            ],
            [
                'name' => 'Beauty & Personal Care',
                'description' => 'Enhance your natural beauty with premium products'
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Gear up for your active lifestyle and adventures'
            ]
        ];
        
        $stmt = $this->db->prepare('
            INSERT INTO categories (name, description) 
            VALUES (:name, :description)
        ');
        
        foreach ($categories as $category) {
            $stmt->bindParam(':name', $category['name']);
            $stmt->bindParam(':description', $category['description']);
            $stmt->execute();
        }
        
        echo "Categories seeded successfully.\n";
    }
    
    private function seedProducts()
    {
        echo "Seeding products...\n";
        
        $products = [
            // Electronics
            [
                'name' => 'Smartphone X11',
                'description' => 'The latest smartphone with advanced camera capabilities, 5G connectivity, and a powerful processor.',
                'price' => 799.99,
                'image' => 'smartphone.jpg',
                'stock' => 25,
                'category_id' => 1
            ],
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium noise-cancelling wireless headphones with 30-hour battery life.',
                'price' => 199.99,
                'image' => 'headphones.jpg',
                'stock' => 50,
                'category_id' => 1
            ],
            [
                'name' => 'Smart Watch',
                'description' => 'Track your fitness goals, receive notifications, and more with this premium smart watch.',
                'price' => 249.99,
                'image' => 'smartwatch.jpg',
                'stock' => 30,
                'category_id' => 1
            ],
            [
                'name' => 'Laptop Pro',
                'description' => 'Powerful laptop for professionals and creators.',
                'price' => 1299.99,
                'image' => 'laptop.jpg',
                'stock' => 15,
                'category_id' => 1
            ],
            [
                'name' => 'Wireless Speaker',
                'description' => 'Portable Bluetooth speaker with immersive sound quality.',
                'price' => 89.99,
                'image' => 'speaker.jpg',
                'stock' => 40,
                'category_id' => 1
            ],
            
            // Clothing
            [
                'name' => 'Men\'s T-Shirt',
                'description' => 'Classic cotton t-shirt for everyday comfort.',
                'price' => 24.99,
                'image' => 'tshirt.jpg',
                'stock' => 100,
                'category_id' => 3
            ],
            [
                'name' => 'Women\'s Jeans',
                'description' => 'High-quality denim jeans with the perfect blend of style and comfort.',
                'price' => 59.99,
                'image' => 'jeans.jpg',
                'stock' => 75,
                'category_id' => 3
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Lightweight running shoes designed for maximum comfort and performance.',
                'price' => 129.99,
                'image' => 'shoes.jpg',
                'stock' => 35,
                'category_id' => 3
            ],
            [
                'name' => 'Winter Jacket',
                'description' => 'Stay warm with this insulated winter jacket.',
                'price' => 149.99,
                'image' => 'jacket.jpg',
                'stock' => 25,
                'category_id' => 3
            ],
            [
                'name' => 'Summer Dress',
                'description' => 'Elegant summer dress made from lightweight, breathable fabric.',
                'price' => 79.99,
                'image' => 'dress.jpg',
                'stock' => 30,
                'category_id' => 3
            ],
            
            // Home & Kitchen
            [
                'name' => 'Coffee Maker',
                'description' => 'Programmable coffee maker with 12-cup capacity and built-in grinder.',
                'price' => 79.99,
                'image' => 'coffeemaker.jpg',
                'stock' => 20,
                'category_id' => 2
            ],
            [
                'name' => 'Bedding Set',
                'description' => 'Luxury bedding set including duvet cover, fitted sheet, and pillowcases.',
                'price' => 129.99,
                'image' => 'bedding.jpg',
                'stock' => 25,
                'category_id' => 2
            ],
            [
                'name' => 'Table Lamp',
                'description' => 'Modern table lamp with adjustable brightness for reading or ambient lighting.',
                'price' => 49.99,
                'image' => 'lamp.jpg',
                'stock' => 40,
                'category_id' => 2
            ],
            [
                'name' => 'Kitchen Knife Set',
                'description' => 'Professional-grade kitchen knife set with ergonomic handles.',
                'price' => 99.99,
                'image' => 'knives.jpg',
                'stock' => 15,
                'category_id' => 2
            ],
            [
                'name' => 'Throw Pillows (Set of 2)',
                'description' => 'Decorative throw pillows to accent your living space.',
                'price' => 39.99,
                'image' => 'pillows.jpg',
                'stock' => 50,
                'category_id' => 2
            ]
        ];
        
        $stmt = $this->db->prepare('
            INSERT INTO products (name, description, price, image, stock, category_id) 
            VALUES (:name, :description, :price, :image, :stock, :category_id)
        ');
        
        foreach ($products as $product) {
            // Create image path
            $imagePath = '/public/images/' . $product['image'];
            
            // Bind parameters
            $stmt->bindParam(':name', $product['name']);
            $stmt->bindParam(':description', $product['description']);
            $stmt->bindParam(':price', $product['price']);
            $stmt->bindParam(':image', $imagePath);
            $stmt->bindParam(':stock', $product['stock']);
            $stmt->bindParam(':category_id', $product['category_id']);
            
            $stmt->execute();
            
            // Create placeholder image if it doesn't exist
            $this->createPlaceholderImage($product['image'], $product['name']);
        }
        
        echo "Products seeded successfully.\n";
    }
    
    private function createPlaceholderImage($filename, $productName)
    {
        $targetFile = $this->imageTargetDir . '/' . $filename;
        
        // Skip if file already exists
        if (file_exists($targetFile)) {
            return;
        }
        
        // Create a simple placeholder image with product name
        $image = imagecreatetruecolor(600, 400);
        
        // Set background color (light gray)
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        imagefill($image, 0, 0, $bgColor);
        
        // Set text color (dark gray)
        $textColor = imagecolorallocate($image, 50, 50, 50);
        
        // Add product name to image
        $text = wordwrap($productName, 20, "\n", true);
        $lines = explode("\n", $text);
        $lineHeight = 30;
        $y = (400 - count($lines) * $lineHeight) / 2;
        
        foreach ($lines as $line) {
            $textWidth = imagefontwidth(5) * strlen($line);
            $x = (600 - $textWidth) / 2;
            imagestring($image, 5, $x, $y, $line, $textColor);
            $y += $lineHeight;
        }
        
        // Save image
        imagejpeg($image, $targetFile, 90);
        imagedestroy($image);
    }
}

// Run the seeder
$seeder = new DatabaseSeeder();
$seeder->run();

echo "Done! You can now browse the products at http://localhost:8080/products\n"; 