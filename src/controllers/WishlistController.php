<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Services\WishlistService;
use App\Services\ProductService;

class WishlistController extends Controller
{
    private WishlistService $wishlistService;
    private ProductService $productService;

    public function __construct()
    {
        $this->wishlistService = new WishlistService();
        $this->productService = new ProductService();
    }

    /**
     * Show the user's wishlist
     */
    public function index(): void
    {
        // Get the current user ID (in a real app, this would come from authentication)
        $userId = $_SESSION['user_id'] ?? 1; // Default to user 1 for demo
        
        // Get the items in the user's wishlist
        $wishlistItems = $this->wishlistService->getWishlist($userId);
        
        $this->render('wishlist/index', [
            'pageTitle' => 'Danh sách yêu thích của bạn',
            'wishlistItems' => $wishlistItems
        ]);
    }

    /**
     * Add a product to the wishlist
     */
    public function add(): void
    {
        // Check if this is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/products');
            return;
        }
        
        // Get the product ID from the form
        $productId = (int) ($_POST['product_id'] ?? 0);
        
        if ($productId <= 0) {
            $this->redirect('/products');
            return;
        }
        
        // Get the current user ID (in a real app, this would come from authentication)
        $userId = $_SESSION['user_id'] ?? 1; // Default to user 1 for demo
        
        // Add the product to the wishlist
        $success = $this->wishlistService->addToWishlist($userId, $productId);
        
        // Get the return URL (the page the user was on)
        $returnUrl = $_POST['return_url'] ?? '/products';
        
        // Redirect back to the product page or wherever the user came from
        $this->redirect($returnUrl);
    }

    /**
     * Remove a product from the wishlist
     */
    public function remove(): void
    {
        // Check if this is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/wishlist');
            return;
        }
        
        // Get the product ID from the form
        $productId = (int) ($_POST['product_id'] ?? 0);
        
        if ($productId <= 0) {
            $this->redirect('/wishlist');
            return;
        }
        
        // Get the current user ID (in a real app, this would come from authentication)
        $userId = $_SESSION['user_id'] ?? 1; // Default to user 1 for demo
        
        // Remove the product from the wishlist
        $success = $this->wishlistService->removeFromWishlist($userId, $productId);
        
        // Get the return URL (the page the user was on)
        $returnUrl = $_POST['return_url'] ?? '/wishlist';
        
        // Redirect back to the wishlist page or wherever the user came from
        $this->redirect($returnUrl);
    }

    /**
     * Toggle a product in the wishlist (add if not present, remove if present)
     */
    public function toggle(): void
    {
        // Check if this is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        // Get the product ID from the form
        $productId = (int) ($_POST['product_id'] ?? 0);
        
        if ($productId <= 0) {
            $this->sendJsonResponse(['success' => false, 'message' => 'Invalid product ID']);
            return;
        }
        
        // Get the current user ID (in a real app, this would come from authentication)
        $userId = $_SESSION['user_id'] ?? 1; // Default to user 1 for demo
        
        // Check if the product is already in the wishlist
        $isInWishlist = $this->wishlistService->isInWishlist($userId, $productId);
        
        if ($isInWishlist) {
            // Remove the product from the wishlist
            $success = $this->wishlistService->removeFromWishlist($userId, $productId);
            $message = 'Product removed from wishlist';
        } else {
            // Add the product to the wishlist
            $success = $this->wishlistService->addToWishlist($userId, $productId);
            $message = 'Product added to wishlist';
        }
        
        // Get the updated wishlist count
        $wishlistCount = $this->wishlistService->getWishlistCount($userId);
        
        // Return a JSON response
        $this->sendJsonResponse([
            'success' => $success,
            'message' => $message,
            'in_wishlist' => !$isInWishlist, // Toggle the state
            'wishlist_count' => $wishlistCount
        ]);
    }
    
    /**
     * Helper method to send a JSON response
     * 
     * @param array $data The data to send
     */
    private function sendJsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 