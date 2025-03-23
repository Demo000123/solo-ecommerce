<?php
declare(strict_types=1);

namespace App\Services;

require_once __DIR__ . '/../config/database.php';

use PDO;

class WishlistService
{
    private $db;

    public function __construct()
    {
        $this->db = \App\Config\Database::getInstance()->getConnection();
        $this->ensureTableExists();
    }

    /**
     * Ensure the wishlist table exists
     */
    private function ensureTableExists(): void
    {
        try {
            // Check if table exists
            $tableExists = $this->db->query("SHOW TABLES LIKE 'wishlists'")->rowCount() > 0;
            
            if (!$tableExists) {
                // Create wishlist table if it doesn't exist
                $this->db->exec('
                    CREATE TABLE IF NOT EXISTS wishlists (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        user_id INT NOT NULL,
                        product_id INT NOT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        UNIQUE KEY user_product (user_id, product_id),
                        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
                    )
                ');
                
                // Create index for faster queries
                $this->db->exec('CREATE INDEX idx_wishlist_user ON wishlists(user_id)');
            }
        } catch (\PDOException $e) {
            // In a real application, log this error
        }
    }

    /**
     * Add a product to a user's wishlist
     * 
     * @param int $userId User ID
     * @param int $productId Product ID
     * @return bool Success status
     */
    public function addToWishlist(int $userId, int $productId): bool
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO wishlists (user_id, product_id) 
                VALUES (:user_id, :product_id)
                ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP
            ');
            
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            // In a real application, log this error
            return false;
        }
    }

    /**
     * Remove a product from a user's wishlist
     * 
     * @param int $userId User ID
     * @param int $productId Product ID
     * @return bool Success status
     */
    public function removeFromWishlist(int $userId, int $productId): bool
    {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM wishlists 
                WHERE user_id = :user_id AND product_id = :product_id
            ');
            
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (\PDOException $e) {
            // In a real application, log this error
            return false;
        }
    }

    /**
     * Check if a product is in a user's wishlist
     * 
     * @param int $userId User ID
     * @param int $productId Product ID
     * @return bool True if in wishlist
     */
    public function isInWishlist(int $userId, int $productId): bool
    {
        try {
            $stmt = $this->db->prepare('
                SELECT 1 FROM wishlists 
                WHERE user_id = :user_id AND product_id = :product_id
            ');
            
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            // In a real application, log this error
            return false;
        }
    }

    /**
     * Get a user's wishlist
     * 
     * @param int $userId User ID
     * @return array Wishlist items with product details
     */
    public function getWishlist(int $userId): array
    {
        try {
            $stmt = $this->db->prepare('
                SELECT p.*, c.name AS category_name, w.created_at AS added_at
                FROM wishlists w
                JOIN products p ON w.product_id = p.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE w.user_id = :user_id
                ORDER BY w.created_at DESC
            ');
            
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            // In a real application, log this error
            return [];
        }
    }

    /**
     * Count items in user's wishlist
     * 
     * @param int $userId User ID
     * @return int Count of wishlist items
     */
    public function getWishlistCount(int $userId): int
    {
        try {
            $stmt = $this->db->prepare('
                SELECT COUNT(*) FROM wishlists 
                WHERE user_id = :user_id
            ');
            
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            // In a real application, log this error
            return 0;
        }
    }
} 