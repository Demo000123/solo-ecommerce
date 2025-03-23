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
        $category = (int) $this->getParam('category', 0);
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
        } elseif ($category > 0) {
            $products = $this->productService->getProductsByCategory($category, $sortBy, $sortDirection, $page, $this->perPage);
            $totalProducts = $this->productService->countProducts($category);
            // Find category name
            $categoryName = "Products";
            foreach ($categories as $cat) {
                if ($cat['id'] == $category) {
                    $categoryName = $cat['name'];
                    break;
                }
            }
            $pageTitle = $categoryName . " Products";
        } else {
            $products = $this->productService->getAllProducts($sortBy, $sortDirection, $page, $this->perPage);
            $totalProducts = $this->productService->getProductCount();
            $pageTitle = "All Products";
        }
        
        // Calculate pagination values
        $totalPages = ceil($totalProducts / $this->perPage);
        
        // Build the base URL for pagination links (preserving filters)
        $baseUrl = '/products?';
        if ($category > 0) {
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
        
        // Get related products using our enhanced algorithm
        $relatedProducts = $this->productService->getRelatedProducts($id, 4);
        
        $this->render('products/show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'pageTitle' => $product['name']
        ]);
    }
} 