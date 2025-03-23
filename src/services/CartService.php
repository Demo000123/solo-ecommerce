<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class CartService
{
    private Database $db;
    private ProductService $productService;

    public function __construct()
    {
        $this->db = new Database();
        $this->productService = new ProductService();
    }

    public function getCart(?int $userId, string $sessionId): array
    {
        $items = [];
        $subtotal = 0;
        
        if ($userId) {
            // Get cart items for logged in user
            $sql = "SELECT c.*, p.name, p.image, p.price, p.sale_price 
                    FROM cart_items c
                    JOIN products p ON c.product_id = p.id
                    WHERE c.user_id = ?";
                    
            $items = $this->db->query($sql, [$userId]);
        } else {
            // Get cart items for guest user (by session ID)
            $sql = "SELECT c.*, p.name, p.image, p.price, p.sale_price 
                    FROM cart_items c
                    JOIN products p ON c.product_id = p.id
                    WHERE c.session_id = ?";
                    
            $items = $this->db->query($sql, [$sessionId]);
        }
        
        // Calculate item prices and subtotal
        foreach ($items as &$item) {
            // Use sale price if available, otherwise use regular price
            $item['price'] = $item['sale_price'] ? $item['sale_price'] : $item['price'];
            $item['total'] = $item['price'] * $item['quantity'];
            $subtotal += $item['total'];
        }
        
        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'count' => count($items)
        ];
    }

    public function addToCart(int $productId, int $quantity, ?int $userId, string $sessionId): bool
    {
        // Get product information to check stock
        $product = $this->productService->getProductById($productId);
        
        if (!$product || $product['stock'] < $quantity) {
            return false;
        }
        
        // Check if product already exists in cart
        if ($userId) {
            $sql = "SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?";
            $existing = $this->db->query($sql, [$userId, $productId]);
        } else {
            $sql = "SELECT * FROM cart_items WHERE session_id = ? AND product_id = ?";
            $existing = $this->db->query($sql, [$sessionId, $productId]);
        }
        
        if (!empty($existing)) {
            // Update quantity if product already in cart
            $newQuantity = $existing[0]['quantity'] + $quantity;
            
            // Check if new quantity exceeds stock
            if ($newQuantity > $product['stock']) {
                $newQuantity = $product['stock'];
            }
            
            if ($userId) {
                $sql = "UPDATE cart_items SET quantity = ?, updated_at = NOW() 
                        WHERE user_id = ? AND product_id = ?";
                return $this->db->execute($sql, [$newQuantity, $userId, $productId]);
            } else {
                $sql = "UPDATE cart_items SET quantity = ?, updated_at = NOW() 
                        WHERE session_id = ? AND product_id = ?";
                return $this->db->execute($sql, [$newQuantity, $sessionId, $productId]);
            }
        } else {
            // Add new item to cart
            if ($userId) {
                $sql = "INSERT INTO cart_items (user_id, product_id, quantity, created_at) 
                        VALUES (?, ?, ?, NOW())";
                return $this->db->execute($sql, [$userId, $productId, $quantity]);
            } else {
                $sql = "INSERT INTO cart_items (session_id, product_id, quantity, created_at) 
                        VALUES (?, ?, ?, NOW())";
                return $this->db->execute($sql, [$sessionId, $productId, $quantity]);
            }
        }
    }

    public function updateCartItem(int $itemId, int $quantity, ?int $userId, string $sessionId): bool
    {
        // Validate ownership of cart item
        if ($userId) {
            $sql = "SELECT * FROM cart_items WHERE id = ? AND user_id = ?";
            $item = $this->db->query($sql, [$itemId, $userId]);
        } else {
            $sql = "SELECT * FROM cart_items WHERE id = ? AND session_id = ?";
            $item = $this->db->query($sql, [$itemId, $sessionId]);
        }
        
        if (empty($item)) {
            return false;
        }
        
        // Check product stock
        $productId = $item[0]['product_id'];
        $product = $this->productService->getProductById($productId);
        
        if (!$product) {
            return false;
        }
        
        // Ensure quantity doesn't exceed stock
        if ($quantity > $product['stock']) {
            $quantity = $product['stock'];
        }
        
        // Update quantity
        $sql = "UPDATE cart_items SET quantity = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$quantity, $itemId]);
    }

    public function removeCartItem(int $itemId, ?int $userId, string $sessionId): bool
    {
        // Validate ownership of cart item
        if ($userId) {
            $sql = "DELETE FROM cart_items WHERE id = ? AND user_id = ?";
            return $this->db->execute($sql, [$itemId, $userId]);
        } else {
            $sql = "DELETE FROM cart_items WHERE id = ? AND session_id = ?";
            return $this->db->execute($sql, [$itemId, $sessionId]);
        }
    }

    public function clearCart(?int $userId, string $sessionId): bool
    {
        if ($userId) {
            $sql = "DELETE FROM cart_items WHERE user_id = ?";
            return $this->db->execute($sql, [$userId]);
        } else {
            $sql = "DELETE FROM cart_items WHERE session_id = ?";
            return $this->db->execute($sql, [$sessionId]);
        }
    }

    public function mergeGuestCart(int $userId, string $sessionId): bool
    {
        // Get guest cart items
        $sql = "SELECT * FROM cart_items WHERE session_id = ?";
        $guestItems = $this->db->query($sql, [$sessionId]);
        
        if (empty($guestItems)) {
            return true; // No items to merge
        }
        
        // Get user cart items
        $sql = "SELECT * FROM cart_items WHERE user_id = ?";
        $userItems = $this->db->query($sql, [$userId]);
        
        // Create a map of product IDs to quantities in user cart
        $userProductMap = [];
        foreach ($userItems as $item) {
            $userProductMap[$item['product_id']] = $item['quantity'];
        }
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Process each guest item
            foreach ($guestItems as $guestItem) {
                $productId = $guestItem['product_id'];
                $quantity = $guestItem['quantity'];
                
                // Get product to check stock
                $product = $this->productService->getProductById($productId);
                
                if (!$product) {
                    continue; // Skip invalid products
                }
                
                if (isset($userProductMap[$productId])) {
                    // Product exists in user cart, update quantity
                    $newQuantity = $userProductMap[$productId] + $quantity;
                    
                    // Ensure quantity doesn't exceed stock
                    if ($newQuantity > $product['stock']) {
                        $newQuantity = $product['stock'];
                    }
                    
                    $sql = "UPDATE cart_items SET quantity = ?, updated_at = NOW() 
                            WHERE user_id = ? AND product_id = ?";
                    $this->db->execute($sql, [$newQuantity, $userId, $productId]);
                } else {
                    // Product not in user cart, add it
                    $sql = "INSERT INTO cart_items (user_id, product_id, quantity, created_at) 
                            VALUES (?, ?, ?, NOW())";
                    $this->db->execute($sql, [$userId, $productId, $quantity]);
                }
            }
            
            // Remove guest cart items
            $sql = "DELETE FROM cart_items WHERE session_id = ?";
            $this->db->execute($sql, [$sessionId]);
            
            // Commit transaction
            $this->db->commit();
            
            return true;
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->rollback();
            
            // Log error
            error_log("Cart merge failed: " . $e->getMessage());
            
            return false;
        }
    }

    public function getCartItemCount(?int $userId, string $sessionId): int
    {
        if ($userId) {
            $sql = "SELECT COUNT(*) as count FROM cart_items WHERE user_id = ?";
            $result = $this->db->query($sql, [$userId]);
        } else {
            $sql = "SELECT COUNT(*) as count FROM cart_items WHERE session_id = ?";
            $result = $this->db->query($sql, [$sessionId]);
        }
        
        return (int)($result[0]['count'] ?? 0);
    }

    public function validateCart(?int $userId, string $sessionId): bool
    {
        $cart = $this->getCart($userId, $sessionId);
        
        if (empty($cart['items'])) {
            return false; // Cart is empty
        }
        
        // Check stock for each item
        $valid = true;
        foreach ($cart['items'] as $item) {
            $product = $this->productService->getProductById($item['product_id']);
            
            if (!$product || $product['stock'] < $item['quantity']) {
                $valid = false;
                break;
            }
        }
        
        return $valid;
    }
} 