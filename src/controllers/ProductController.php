<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ProductService;

class ProductController extends Controller
{
    private ProductService $productService;
    private int $perPage = 9;

    public function __construct()
    {
        $this->productService = new ProductService();
    }

    public function index(): void
    {
        // Get filter parameters
        $category = $this->getParam('category');
        $search = $this->getParam('search');
        $page = max(1, (int) $this->getParam('page', 1));
        $sortBy = $this->getParam('sort', 'name');
        $sortDirection = $this->getParam('direction', 'asc');
        
        // Get all available categories for the filter buttons
        $categories = $this->productService->getCategories();
        
        // Initialize variables
        $products = [];
        $pageTitle = 'All Products';
        $totalProducts = 0;
        
        // Get products based on filters
        if ($search) {
            $products = $this->productService->searchProducts($search, $sortBy, $sortDirection, $page, $this->perPage);
            $totalProducts = $this->productService->getSearchCount($search);
            $pageTitle = "Search Results for '{$search}'";
        } elseif ($category) {
            $products = $this->productService->getProductsByCategory($category, $sortBy, $sortDirection, $page, $this->perPage);
            $totalProducts = $this->productService->getCategoryCount($category);
            $pageTitle = ucfirst($category) . " Products";
        } else {
            $products = $this->productService->getAllProducts($sortBy, $sortDirection, $page, $this->perPage);
            $totalProducts = $this->productService->getProductCount();
            $pageTitle = "All Products";
        }
        
        // Calculate pagination values
        $totalPages = ceil($totalProducts / $this->perPage);
        
        // Build the base URL for pagination links (preserving filters)
        $baseUrl = '/products?';
        if ($category) {
            $baseUrl .= "category={$category}&";
        }
        if ($search) {
            $baseUrl .= "search={$search}&";
        }
        if ($sortBy !== 'name') {
            $baseUrl .= "sort={$sortBy}&";
        }
        if ($sortDirection !== 'asc') {
            $baseUrl .= "direction={$sortDirection}&";
        }
        
        $this->render('products/index', [
            'products' => $products,
            'pageTitle' => $pageTitle,
            'categories' => $categories,
            'currentCategory' => $category,
            'currentSearch' => $search,
            'currentSort' => $sortBy,
            'currentDirection' => $sortDirection,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            'baseUrl' => $baseUrl
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
        
        // Get products from the same category for "Related Products" section
        $relatedProducts = $this->productService->getProductsByCategory($product->getCategory(), 'name', 'asc', 1, 4);
        
        // Filter out the current product from related products
        $relatedProducts = array_filter($relatedProducts, function ($item) use ($id) {
            return $item->getId() !== $id;
        });
        
        $this->render('products/show', [
            'product' => $product,
            'relatedProducts' => array_slice($relatedProducts, 0, 3), // Show max 3 related products
            'pageTitle' => $product->getName()
        ]);
    }
} 