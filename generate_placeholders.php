<?php
// Create placeholder images for our e-commerce site
// Run this script once to generate sample images

// Define colors for different categories
$colors = [
    'electronics' => ['background' => '#3a86ff', 'text' => '#ffffff'],
    'clothing' => ['background' => '#ff006e', 'text' => '#ffffff'],
    'home' => ['background' => '#ff9e00', 'text' => '#ffffff'],
    'beauty' => ['background' => '#8338ec', 'text' => '#ffffff'],
    'books' => ['background' => '#06d6a0', 'text' => '#ffffff'],
    'sports' => ['background' => '#118ab2', 'text' => '#ffffff']
];

// Product images to generate
$products = [
    'smartphone.jpg' => ['text' => 'Smartphone', 'category' => 'electronics'],
    'laptop.jpg' => ['text' => 'Laptop', 'category' => 'electronics'],
    'smartwatch.jpg' => ['text' => 'Smartwatch', 'category' => 'electronics'],
    'coffeemaker.jpg' => ['text' => 'Coffee Maker', 'category' => 'home'],
    'bedding.jpg' => ['text' => 'Bedding Set', 'category' => 'home'],
    'knives.jpg' => ['text' => 'Kitchen Knives', 'category' => 'home'],
    'tshirt.jpg' => ['text' => 'T-Shirt', 'category' => 'clothing'],
    'shoes.jpg' => ['text' => 'Shoes', 'category' => 'clothing'],
    'dress.jpg' => ['text' => 'Dress', 'category' => 'clothing'],
    'placeholder.jpg' => ['text' => 'Product', 'category' => 'electronics']
];

// Showcase images
$showcases = [
    'electronics-showcase.jpg' => ['text' => 'Electronics', 'category' => 'electronics', 'width' => 800, 'height' => 500],
    'clothing-showcase.jpg' => ['text' => 'Clothing', 'category' => 'clothing', 'width' => 800, 'height' => 500],
    'home-showcase.jpg' => ['text' => 'Home & Kitchen', 'category' => 'home', 'width' => 800, 'height' => 500]
];

// Function to create a placeholder image
function createPlaceholder($filename, $text, $category, $width = 500, $height = 500) {
    global $colors;
    
    // Use category colors or default
    $bgColor = isset($colors[$category]) ? $colors[$category]['background'] : '#cccccc';
    $textColor = isset($colors[$category]) ? $colors[$category]['text'] : '#000000';
    
    // Create the image
    $image = imagecreatetruecolor($width, $height);
    
    // Convert hex colors to RGB
    $rgb = sscanf($bgColor, "#%02x%02x%02x");
    $bgColorAllocated = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    
    $rgb = sscanf($textColor, "#%02x%02x%02x");
    $textColorAllocated = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    
    // Fill the background
    imagefill($image, 0, 0, $bgColorAllocated);
    
    // Add some variety with shapes
    $accent = imagecolorallocate($image, 255, 255, 255);
    imagefilledellipse($image, $width / 2, $height / 2, $width * 0.8, $height * 0.8, imagecolorallocatealpha($image, 255, 255, 255, 100));
    
    // Add text
    $fontSize = 5;
    $fontWidth = imagefontwidth($fontSize);
    $fontHeight = imagefontheight($fontSize);
    $textWidth = $fontWidth * strlen($text);
    $textX = ($width - $textWidth) / 2;
    $textY = ($height - $fontHeight) / 2;
    
    // Draw a background for the text
    imagefilledrectangle(
        $image,
        $textX - 10,
        $textY - 10,
        $textX + $textWidth + 10,
        $textY + $fontHeight + 10,
        imagecolorallocatealpha($image, 0, 0, 0, 80)
    );
    
    imagestring($image, $fontSize, $textX, $textY, $text, $textColorAllocated);
    
    // Save the image
    imagejpeg($image, 'public/images/' . $filename, 90);
    imagedestroy($image);
    
    echo "Created: public/images/$filename\n";
}

// Check if the image directory exists
if (!is_dir('public/images')) {
    mkdir('public/images', 0755, true);
    echo "Created directory: public/images\n";
}

// Generate product images
foreach ($products as $filename => $details) {
    createPlaceholder($filename, $details['text'], $details['category']);
}

// Generate showcase images
foreach ($showcases as $filename => $details) {
    createPlaceholder(
        $filename, 
        $details['text'], 
        $details['category'],
        $details['width'],
        $details['height']
    );
}

echo "All placeholder images have been generated successfully!\n"; 