<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ProductService;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index(): void
    {
        $category = $this->getParam('category');
        $search = $this->getParam('search');
        
        if ($search) {
            $products = $this->productService->searchProducts($search);
            $pageTitle = "Search Results for '{$search}'";
        } elseif ($category) {
            $products = $this->productService->getProductsByCategory($category);
            $pageTitle = ucfirst($category) . " Products";
        } else {
            $products = $this->productService->getAllProducts();
            $pageTitle = "All Products";
        }
        
        $this->render('products/index', [
            'products' => $products,
            'pageTitle' => $pageTitle
        ]);
    }

    public function show(): void
    {
        $id = (int) $this->getParam('id', 0);
        
        if ($id <= 0) {
            $this->redirect('/products');
            return;
        }
        
        $product = $this->productService->getProductById($id);
        
        if (!$product) {
            $this->redirect('/products');
            return;
        }
        
        $this->render('products/show', [
            'product' => $product,
            'pageTitle' => $product->getName()
        ]);
    }
} 