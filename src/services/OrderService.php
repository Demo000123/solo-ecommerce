<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class OrderService
{
    private Database $db;
    private ProductService $productService;

    public function __construct()
    {
        $this->db = new Database();
        $this->productService = new ProductService();
    }

    public function createOrder(
        ?int $userId,
        array $cart,
        string $fullname,
        string $email,
        string $phone,
        string $address,
        string $province,
        string $district,
        string $ward,
        string $shippingMethod,
        string $paymentMethod,
        string $notes = ''
    ): int {
        // Generate a unique order number
        $orderNumber = $this->generateOrderNumber();
        
        // Calculate order details
        $subtotal = $cart['subtotal'] ?? 0;
        $shippingCost = 0;
        $discount = 0;
        $total = $subtotal + $shippingCost - $discount;
        
        // Get shipping cost based on method
        $shippingMethodService = new ShippingMethodService();
        $shippingMethods = $shippingMethodService->getActiveShippingMethods();
        
        foreach ($shippingMethods as $method) {
            if ($method['name'] === $shippingMethod) {
                $shippingCost = $method['price'];
                break;
            }
        }
        
        // Check for coupon discount
        if (isset($_SESSION['coupon_code'])) {
            $couponService = new CouponService();
            $coupon = $couponService->getCouponByCode($_SESSION['coupon_code']);
            
            if ($coupon && $couponService->validateCoupon($coupon, $subtotal)) {
                $discount = $couponService->calculateDiscount($coupon, $subtotal);
                
                // Increment coupon usage
                $couponService->incrementCouponUsage($coupon['id']);
            }
        }
        
        // Calculate final total
        $total = $subtotal + $shippingCost - $discount;
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Insert order
            $sql = "INSERT INTO orders (
                        user_id, order_number, fullname, email, phone, address, 
                        province, district, ward, shipping_method, payment_method, 
                        subtotal, shipping_cost, discount, total, notes, status, 
                        payment_status, created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                    
            $params = [
                $userId, $orderNumber, $fullname, $email, $phone, $address,
                $province, $district, $ward, $shippingMethod, $paymentMethod,
                $subtotal, $shippingCost, $discount, $total, $notes, 'pending',
                ($paymentMethod === 'cod') ? 'pending' : 'awaiting_payment'
            ];
            
            $orderId = $this->db->insert($sql, $params);
            
            if (!$orderId) {
                throw new \Exception("Failed to insert order");
            }
            
            // Insert order items
            foreach ($cart['items'] as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $name = $item['name'];
                
                $sql = "INSERT INTO order_items (
                            order_id, product_id, name, price, quantity, created_at
                        ) VALUES (?, ?, ?, ?, ?, NOW())";
                        
                $orderItemId = $this->db->insert($sql, [
                    $orderId, $productId, $name, $price, $quantity
                ]);
                
                if (!$orderItemId) {
                    throw new \Exception("Failed to insert order item");
                }
                
                // Update product stock
                $this->productService->updateStock($productId, $quantity);
                
                // Update product sales count
                $this->productService->incrementSalesCount($productId, $quantity);
            }
            
            // Commit transaction
            $this->db->commit();
            
            return $orderId;
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->rollback();
            
            // Log error
            error_log("Order creation failed: " . $e->getMessage());
            
            return 0;
        }
    }

    public function getOrderById(int $orderId): ?array
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $orders = $this->db->query($sql, [$orderId]);
        
        if (empty($orders)) {
            return null;
        }
        
        $order = $orders[0];
        
        // Get order items
        $sql = "SELECT * FROM order_items WHERE order_id = ?";
        $items = $this->db->query($sql, [$orderId]);
        
        $order['items'] = $items;
        
        return $order;
    }

    public function getOrderByOrderNumber(string $orderNumber): ?array
    {
        $sql = "SELECT * FROM orders WHERE order_number = ?";
        $orders = $this->db->query($sql, [$orderNumber]);
        
        if (empty($orders)) {
            return null;
        }
        
        $order = $orders[0];
        
        // Get order items
        $sql = "SELECT * FROM order_items WHERE order_id = ?";
        $items = $this->db->query($sql, [$order['id']]);
        
        $order['items'] = $items;
        
        return $order;
    }

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $sql = "UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$status, $orderId]);
    }

    public function updatePaymentStatus(int $orderId, string $paymentStatus): bool
    {
        $sql = "UPDATE orders SET payment_status = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$paymentStatus, $orderId]);
    }

    public function cancelOrder(int $orderId): bool
    {
        // Get the order first
        $order = $this->getOrderById($orderId);
        
        if (!$order) {
            return false;
        }
        
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Update order status
            $sql = "UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = ?";
            $updated = $this->db->execute($sql, [$orderId]);
            
            if (!$updated) {
                throw new \Exception("Failed to update order status");
            }
            
            // Restore product stock for each order item
            foreach ($order['items'] as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                
                // Restore stock
                $sql = "UPDATE products SET stock = stock + ? WHERE id = ?";
                $updated = $this->db->execute($sql, [$quantity, $productId]);
                
                if (!$updated) {
                    throw new \Exception("Failed to restore product stock");
                }
                
                // Decrement sales count
                $sql = "UPDATE products SET sales_count = sales_count - ? WHERE id = ?";
                $this->db->execute($sql, [$quantity, $productId]);
            }
            
            // Commit transaction
            $this->db->commit();
            
            return true;
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->rollback();
            
            // Log error
            error_log("Order cancellation failed: " . $e->getMessage());
            
            return false;
        }
    }

    public function getUserAddresses(int $userId): array
    {
        $sql = "SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC";
        return $this->db->query($sql, [$userId]);
    }

    public function saveUserAddress(
        int $userId,
        string $fullname,
        string $phone,
        string $address,
        string $province,
        string $district,
        string $ward,
        bool $isDefault = true
    ): int {
        // Check if user already has this exact address
        $sql = "SELECT id FROM user_addresses 
                WHERE user_id = ? AND address = ? AND province = ? AND district = ? AND ward = ?";
                
        $result = $this->db->query($sql, [$userId, $address, $province, $district, $ward]);
        
        if (!empty($result)) {
            // Address already exists, update it
            $addressId = (int)$result[0]['id'];
            
            $sql = "UPDATE user_addresses SET 
                    fullname = ?, 
                    phone = ?, 
                    updated_at = NOW() 
                    WHERE id = ?";
                    
            $this->db->execute($sql, [$fullname, $phone, $addressId]);
            
            // Set as default if requested
            if ($isDefault) {
                $this->setDefaultAddress($userId, $addressId);
            }
            
            return $addressId;
        }
        
        // If set as default, unset any existing default address
        if ($isDefault) {
            $this->unsetDefaultAddresses($userId);
        }
        
        // Insert new address
        $sql = "INSERT INTO user_addresses (
                    user_id, fullname, phone, address, province, district, ward, is_default, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                
        return $this->db->insert($sql, [
            $userId, $fullname, $phone, $address, $province, $district, $ward, $isDefault ? 1 : 0
        ]);
    }

    private function setDefaultAddress(int $userId, int $addressId): bool
    {
        // Unset all default addresses for this user
        $this->unsetDefaultAddresses($userId);
        
        // Set this address as default
        $sql = "UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?";
        return $this->db->execute($sql, [$addressId, $userId]);
    }

    private function unsetDefaultAddresses(int $userId): bool
    {
        $sql = "UPDATE user_addresses SET is_default = 0 WHERE user_id = ?";
        return $this->db->execute($sql, [$userId]);
    }

    public function getAdminOrders(string $status = '', int $page = 1, int $perPage = 10): array
    {
        $params = [];
        $sql = "SELECT o.*, COUNT(oi.id) as item_count 
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id";
                
        if (!empty($status)) {
            $sql .= " WHERE o.status = ?";
            $params[] = $status;
        }
        
        $sql .= " GROUP BY o.id ORDER BY o.created_at DESC";
        
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $perPage;
        
        return $this->db->query($sql, $params);
    }

    public function getAdminOrderCount(string $status = ''): int
    {
        $params = [];
        $sql = "SELECT COUNT(*) as count FROM orders";
        
        if (!empty($status)) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        $result = $this->db->query($sql, $params);
        
        return (int)($result[0]['count'] ?? 0);
    }

    public function getTotalOrders(): int
    {
        $sql = "SELECT COUNT(*) as count FROM orders";
        $result = $this->db->query($sql);
        
        return (int)($result[0]['count'] ?? 0);
    }

    public function getRecentOrders(int $limit = 5): array
    {
        $sql = "SELECT o.*, COUNT(oi.id) as item_count 
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                GROUP BY o.id
                ORDER BY o.created_at DESC
                LIMIT ?";
                
        return $this->db->query($sql, [$limit]);
    }

    public function getOrdersByStatus(string $status, int $limit = 5): array
    {
        $sql = "SELECT o.*, COUNT(oi.id) as item_count 
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.status = ?
                GROUP BY o.id
                ORDER BY o.created_at DESC
                LIMIT ?";
                
        return $this->db->query($sql, [$status, $limit]);
    }

    public function getOrderCountByStatus(string $status): int
    {
        $sql = "SELECT COUNT(*) as count FROM orders WHERE status = ?";
        $result = $this->db->query($sql, [$status]);
        
        return (int)($result[0]['count'] ?? 0);
    }

    public function getTotalRevenue(): float
    {
        $sql = "SELECT SUM(total) as total FROM orders WHERE status != 'cancelled'";
        $result = $this->db->query($sql);
        
        return (float)($result[0]['total'] ?? 0);
    }

    private function generateOrderNumber(): string
    {
        // Generate a unique order number in the format ORD-YYYYMMDD-XXXX
        $date = date('Ymd');
        $uniqueId = substr(uniqid(), -4);
        
        return "ORD-{$date}-{$uniqueId}";
    }
} 