<?php
declare(strict_types=1);

namespace App\Controllers;

require_once __DIR__ . '/../services/ProductService.php';
require_once __DIR__ . '/../services/CategoryService.php';

use App\Services\ProductService;
use App\Services\CategoryService;

class HomeController
{
    private ProductService $productService;
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
    }

    public function index()
    {
        // Get featured products (newest 8 products)
        $featuredProducts = $this->productService->getProducts(
            limit: 8,
            sort: 'created_at',
            sortDirection: 'DESC'
        );
        
        // Get all categories
        $categories = $this->categoryService->getCategories();
        
        // Get a showcase product from each category (for demo purposes)
        $categoryShowcase = [];
        foreach ($categories as $category) {
            $products = $this->productService->getProducts(
                limit: 1,
                categoryId: $category['id']
            );
            
            if (!empty($products)) {
                $categoryShowcase[$category['name']] = $products[0];
            }
        }
        
        // For the demo homepage, let's add some placeholder images if needed
        if (empty($featuredProducts)) {
            // Create placeholder products if database is empty
            $featuredProducts = $this->getPlaceholderProducts();
        }
        
        // Render the home page
        include __DIR__ . '/../views/home/index.php';
    }
    
    /**
     * Returns placeholder products if the database is empty
     * This is for demo purposes only
     */
    private function getPlaceholderProducts(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Smartphone',
                'description' => 'Latest model smartphone with advanced features',
                'price' => 799.99,
                'image' => 'smartphone.svg',
                'stock' => 10,
                'category_id' => 1,
                'category_name' => 'Electronics',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
            ],
            [
                'id' => 2,
                'name' => 'Laptop',
                'description' => 'Powerful laptop for work and gaming',
                'price' => 1299.99,
                'image' => 'laptop.svg',
                'stock' => 5,
                'category_id' => 1,
                'category_name' => 'Electronics',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days'))
            ],
            [
                'id' => 3,
                'name' => 'Smartwatch',
                'description' => 'Smart wearable with health tracking features',
                'price' => 249.99,
                'image' => 'smartwatch.svg',
                'stock' => 15,
                'category_id' => 1,
                'category_name' => 'Electronics',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
            ],
            [
                'id' => 4,
                'name' => 'Coffee Maker',
                'description' => 'Premium coffee maker for your kitchen',
                'price' => 89.99,
                'image' => 'coffeemaker.svg',
                'stock' => 8,
                'category_id' => 2,
                'category_name' => 'Home & Kitchen',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days'))
            ],
            [
                'id' => 5,
                'name' => 'T-Shirt',
                'description' => 'Comfortable cotton t-shirt',
                'price' => 19.99,
                'image' => 'tshirt.svg',
                'stock' => 25,
                'category_id' => 3,
                'category_name' => 'Clothing',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ],
            [
                'id' => 6,
                'name' => 'Kitchen Knives Set',
                'description' => 'Professional set of kitchen knives',
                'price' => 129.99,
                'image' => 'knives.svg',
                'stock' => 7,
                'category_id' => 2,
                'category_name' => 'Home & Kitchen',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days'))
            ],
            [
                'id' => 7,
                'name' => 'Running Shoes',
                'description' => 'Lightweight running shoes for athletes',
                'price' => 89.99,
                'image' => 'shoes.svg',
                'stock' => 12,
                'category_id' => 3,
                'category_name' => 'Clothing',
                'created_at' => date('Y-m-d H:i:s', strtotime('-15 days'))
            ],
            [
                'id' => 8,
                'name' => 'Bedding Set',
                'description' => 'Luxury bedding set for better sleep',
                'price' => 149.99,
                'image' => 'bedding.svg',
                'stock' => 0, // Out of stock
                'category_id' => 2,
                'category_name' => 'Home & Kitchen',
                'created_at' => date('Y-m-d H:i:s', strtotime('-20 days'))
            ]
        ];
    }
} 