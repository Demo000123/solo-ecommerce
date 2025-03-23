<?php
/**
 * Placeholder Image Generator
 * 
 * This script creates simple placeholder images for products
 * It can be run from the command line with: php tools/create-placeholders.php
 */

// Product images needed
$products = [
    'smartphone.jpg' => 'Smartphone X11',
    'headphones.jpg' => 'Wireless Headphones',
    'smartwatch.jpg' => 'Smart Watch',
    'laptop.jpg' => 'Laptop Pro', 
    'speaker.jpg' => 'Wireless Speaker',
    'tshirt.jpg' => 'Men\'s T-Shirt',
    'jeans.jpg' => 'Women\'s Jeans',
    'shoes.jpg' => 'Running Shoes',
    'jacket.jpg' => 'Winter Jacket',
    'dress.jpg' => 'Summer Dress',
    'coffeemaker.jpg' => 'Coffee Maker',
    'bedding.jpg' => 'Bedding Set',
    'lamp.jpg' => 'Table Lamp',
    'knives.jpg' => 'Kitchen Knife Set',
    'pillows.jpg' => 'Throw Pillows'
];

// Target directory
$imageDir = __DIR__ . '/../public/images';

// Create the directory if it doesn't exist
if (!file_exists($imageDir)) {
    mkdir($imageDir, 0755, true);
    echo "Created directory: $imageDir\n";
}

// Generate placeholder images
foreach ($products as $filename => $productName) {
    $targetFile = $imageDir . '/' . $filename;
    
    // Skip if file already exists
    if (file_exists($targetFile)) {
        echo "Image already exists: $filename\n";
        continue;
    }
    
    echo "Creating placeholder image for: $productName\n";
    
    // Create colors based on product type
    $bgColor = [240, 240, 240]; // Default light gray
    $accentColor = [50, 100, 240]; // Default blue accent
    
    // Set different colors based on product type
    if (strpos($filename, 'smartphone') !== false || 
        strpos($filename, 'headphones') !== false || 
        strpos($filename, 'laptop') !== false || 
        strpos($filename, 'smartwatch') !== false || 
        strpos($filename, 'speaker') !== false) {
        // Electronics - blue theme
        $bgColor = [230, 240, 255];
        $accentColor = [30, 80, 220];
    } elseif (strpos($filename, 'tshirt') !== false || 
        strpos($filename, 'jeans') !== false || 
        strpos($filename, 'shoes') !== false || 
        strpos($filename, 'jacket') !== false || 
        strpos($filename, 'dress') !== false) {
        // Clothing - green theme
        $bgColor = [230, 250, 240];
        $accentColor = [30, 180, 120];
    } elseif (strpos($filename, 'coffeemaker') !== false || 
        strpos($filename, 'bedding') !== false || 
        strpos($filename, 'lamp') !== false || 
        strpos($filename, 'knives') !== false || 
        strpos($filename, 'pillows') !== false) {
        // Home & Kitchen - orange theme
        $bgColor = [255, 245, 230];
        $accentColor = [220, 120, 30];
    }
    
    // Create a simple placeholder image with product name
    $image = imagecreatetruecolor(600, 400);
    
    // Set background color
    $bgColorResource = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
    imagefill($image, 0, 0, $bgColorResource);
    
    // Create accent color block
    $accentColorResource = imagecolorallocate($image, $accentColor[0], $accentColor[1], $accentColor[2]);
    imagefilledrectangle($image, 0, 0, 600, 80, $accentColorResource);
    
    // Set text colors
    $whiteColor = imagecolorallocate($image, 255, 255, 255);
    $textColor = imagecolorallocate($image, 50, 50, 50);
    
    // Add product name to header
    imagestring($image, 5, 20, 30, strtoupper($productName), $whiteColor);
    
    // Add category label based on filename
    $category = "CATEGORY: ";
    if (strpos($filename, 'smartphone') !== false || 
        strpos($filename, 'headphones') !== false || 
        strpos($filename, 'laptop') !== false || 
        strpos($filename, 'smartwatch') !== false || 
        strpos($filename, 'speaker') !== false) {
        $category .= "ELECTRONICS";
    } elseif (strpos($filename, 'tshirt') !== false || 
        strpos($filename, 'jeans') !== false || 
        strpos($filename, 'shoes') !== false || 
        strpos($filename, 'jacket') !== false || 
        strpos($filename, 'dress') !== false) {
        $category .= "CLOTHING";
    } else {
        $category .= "HOME & KITCHEN";
    }
    
    // Add product description as simulated item
    imagestring($image, 4, 30, 140, "Product: " . $productName, $textColor);
    imagestring($image, 3, 30, 170, $category, $textColor);
    imagestring($image, 3, 30, 200, "Demo image for e-commerce site", $textColor);
    imagestring($image, 3, 30, 230, "ID: " . substr(md5($filename), 0, 8), $textColor);
    
    // Draw a product outline
    imagerectangle($image, 150, 260, 450, 360, $accentColorResource);
    imagefilledrectangle($image, 180, 290, 420, 330, $accentColorResource);
    
    // Save image
    imagejpeg($image, $targetFile, 90);
    imagedestroy($image);
    
    echo "Created: $filename\n";
}

echo "\nDone! Created " . count($products) . " placeholder images in $imageDir\n";
echo "You can now browse the products at http://localhost:8080/products\n"; 