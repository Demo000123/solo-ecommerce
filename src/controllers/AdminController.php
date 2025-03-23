<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\OrderService;
use App\Services\UserService;

class AdminController extends Controller
{
    private ProductService $productService;
    private CategoryService $categoryService;
    private OrderService $orderService;
    private UserService $userService;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
        $this->orderService = new OrderService();
        $this->userService = new UserService();
    }

    public function index(): void
    {
        $this->checkAdminAccess();
        
        // Get dashboard stats
        $totalOrders = $this->orderService->getTotalOrders();
        $totalProducts = $this->productService->getProductCount();
        $totalUsers = $this->userService->getTotalUsers();
        $recentOrders = $this->orderService->getRecentOrders(5);
        
        $this->render('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard',
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'recentOrders' => $recentOrders
        ]);
    }

    // Product Management
    public function products(): void
    {
        $this->checkAdminAccess();
        
        $page = max(1, (int)$this->getParam('page', 1));
        $search = $this->getParam('search', '');
        $perPage = 10;
        
        $products = $this->productService->getAdminProducts($search, $page, $perPage);
        $totalProducts = $this->productService->getAdminProductCount($search);
        $totalPages = ceil($totalProducts / $perPage);
        
        $this->render('admin/products/index', [
            'pageTitle' => 'Manage Products',
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ]);
    }

    public function createProduct(): void
    {
        $this->checkAdminAccess();
        
        $categories = $this->categoryService->getAllCategories();
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->filterInput($this->postParam('name', ''));
            $description = $this->postParam('description', '');
            $short_description = $this->filterInput($this->postParam('short_description', ''));
            $price = (float)$this->postParam('price', 0);
            $sale_price = !empty($this->postParam('sale_price')) ? (float)$this->postParam('sale_price') : null;
            $stock = (int)$this->postParam('stock', 0);
            $category_id = (int)$this->postParam('category_id', 0);
            $status = $this->filterInput($this->postParam('status', 'active'));
            $is_featured = (int)$this->postParam('is_featured', 0);
            
            // Validate product data
            if (empty($name) || empty($description) || $price <= 0 || $category_id <= 0) {
                $error = 'Please fill in all required fields';
            } else {
                // Handle image upload
                $image = '/public/images/product-placeholder.jpg'; // Default image
                
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/images/products/';
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $image = '/public/images/products/' . $fileName;
                    } else {
                        $error = 'Failed to upload image';
                    }
                }
                
                if (empty($error)) {
                    // Create product
                    $productId = $this->productService->createProduct(
                        $name, 
                        $description, 
                        $short_description,
                        $price, 
                        $sale_price,
                        $image, 
                        $stock, 
                        $category_id,
                        $status,
                        $is_featured
                    );
                    
                    if ($productId) {
                        $this->redirect('/admin/products');
                        return;
                    } else {
                        $error = 'Failed to create product';
                    }
                }
            }
        }
        
        $this->render('admin/products/create', [
            'pageTitle' => 'Add New Product',
            'categories' => $categories,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function editProduct(): void
    {
        $this->checkAdminAccess();
        
        $productId = (int)$this->getParam('id', 0);
        
        if ($productId <= 0) {
            $this->redirect('/admin/products');
            return;
        }
        
        $product = $this->productService->getProductById($productId);
        
        if (!$product) {
            $this->redirect('/admin/products');
            return;
        }
        
        $categories = $this->categoryService->getAllCategories();
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->filterInput($this->postParam('name', ''));
            $description = $this->postParam('description', '');
            $short_description = $this->filterInput($this->postParam('short_description', ''));
            $price = (float)$this->postParam('price', 0);
            $sale_price = !empty($this->postParam('sale_price')) ? (float)$this->postParam('sale_price') : null;
            $stock = (int)$this->postParam('stock', 0);
            $category_id = (int)$this->postParam('category_id', 0);
            $status = $this->filterInput($this->postParam('status', 'active'));
            $is_featured = (int)$this->postParam('is_featured', 0);
            
            // Validate product data
            if (empty($name) || empty($description) || $price <= 0 || $category_id <= 0) {
                $error = 'Please fill in all required fields';
            } else {
                // Handle image upload if a new image is provided
                $image = $product['image']; // Keep existing image by default
                
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/public/images/products/';
                    $fileName = time() . '_' . basename($_FILES['image']['name']);
                    $targetFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $image = '/public/images/products/' . $fileName;
                    } else {
                        $error = 'Failed to upload image';
                    }
                }
                
                if (empty($error)) {
                    // Update product
                    $updated = $this->productService->updateProduct(
                        $productId,
                        $name, 
                        $description, 
                        $short_description,
                        $price, 
                        $sale_price,
                        $image, 
                        $stock, 
                        $category_id,
                        $status,
                        $is_featured
                    );
                    
                    if ($updated) {
                        $success = 'Product updated successfully';
                        // Refresh the product data
                        $product = $this->productService->getProductById($productId);
                    } else {
                        $error = 'Failed to update product';
                    }
                }
            }
        }
        
        $this->render('admin/products/edit', [
            'pageTitle' => 'Edit Product',
            'product' => $product,
            'categories' => $categories,
            'error' => $error,
            'success' => $success
        ]);
    }

    public function deleteProduct(): void
    {
        $this->checkAdminAccess();
        
        $productId = (int)$this->getParam('id', 0);
        
        if ($productId <= 0) {
            $this->redirect('/admin/products');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deleted = $this->productService->deleteProduct($productId);
            
            if ($deleted) {
                $_SESSION['admin_message'] = 'Product deleted successfully';
            } else {
                $_SESSION['admin_error'] = 'Failed to delete product';
            }
        }
        
        $this->redirect('/admin/products');
    }

    // Order Management
    public function orders(): void
    {
        $this->checkAdminAccess();
        
        $page = max(1, (int)$this->getParam('page', 1));
        $status = $this->getParam('status', '');
        $perPage = 10;
        
        $orders = $this->orderService->getAdminOrders($status, $page, $perPage);
        $totalOrders = $this->orderService->getAdminOrderCount($status);
        $totalPages = ceil($totalOrders / $perPage);
        
        $this->render('admin/orders/index', [
            'pageTitle' => 'Manage Orders',
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'currentStatus' => $status
        ]);
    }

    public function viewOrder(): void
    {
        $this->checkAdminAccess();
        
        $orderId = (int)$this->getParam('id', 0);
        
        if ($orderId <= 0) {
            $this->redirect('/admin/orders');
            return;
        }
        
        $order = $this->orderService->getOrderById($orderId);
        
        if (!$order) {
            $this->redirect('/admin/orders');
            return;
        }
        
        $this->render('admin/orders/view', [
            'pageTitle' => 'Order #' . $order['order_number'],
            'order' => $order
        ]);
    }

    public function updateOrderStatus(): void
    {
        $this->checkAdminAccess();
        
        $orderId = (int)$this->getParam('id', 0);
        
        if ($orderId <= 0 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/orders');
            return;
        }
        
        $status = $this->filterInput($this->postParam('status', ''));
        $paymentStatus = $this->filterInput($this->postParam('payment_status', ''));
        
        if (!empty($status)) {
            $updated = $this->orderService->updateOrderStatus($orderId, $status);
            
            if ($updated) {
                $_SESSION['admin_message'] = 'Order status updated successfully';
            } else {
                $_SESSION['admin_error'] = 'Failed to update order status';
            }
        }
        
        if (!empty($paymentStatus)) {
            $updated = $this->orderService->updatePaymentStatus($orderId, $paymentStatus);
            
            if ($updated) {
                $_SESSION['admin_message'] = 'Payment status updated successfully';
            } else {
                $_SESSION['admin_error'] = 'Failed to update payment status';
            }
        }
        
        $this->redirect('/admin/orders/view/' . $orderId);
    }

    // Helper Methods
    private function checkAdminAccess(): void
    {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
            exit;
        }
    }
} 