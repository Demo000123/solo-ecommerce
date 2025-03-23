<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class ReviewService
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getProductReviews(int $productId, string $status = 'approved'): array
    {
        $sql = "SELECT r.*, u.fullname as user_name 
                FROM reviews r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.product_id = ? AND r.status = ?
                ORDER BY r.created_at DESC";
                
        return $this->db->query($sql, [$productId, $status]);
    }

    public function getReviewById(int $reviewId): ?array
    {
        $sql = "SELECT r.*, u.fullname as user_name 
                FROM reviews r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.id = ?";
                
        $reviews = $this->db->query($sql, [$reviewId]);
        
        return $reviews[0] ?? null;
    }

    public function createReview(
        int $productId, 
        int $userId, 
        int $rating, 
        string $title, 
        string $comment
    ): int {
        $sql = "INSERT INTO reviews (
                    product_id, user_id, rating, title, comment, status, created_at
                ) VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
                
        return $this->db->insert($sql, [$productId, $userId, $rating, $title, $comment]);
    }

    public function updateReviewStatus(int $reviewId, string $status): bool
    {
        $sql = "UPDATE reviews SET status = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$status, $reviewId]);
    }

    public function deleteReview(int $reviewId): bool
    {
        $sql = "DELETE FROM reviews WHERE id = ?";
        return $this->db->execute($sql, [$reviewId]);
    }

    public function getReviewsByStatus(string $status, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT r.*, u.fullname as user_name, p.name as product_name 
                FROM reviews r
                LEFT JOIN users u ON r.user_id = u.id
                LEFT JOIN products p ON r.product_id = p.id
                WHERE r.status = ?
                ORDER BY r.created_at DESC
                LIMIT ?, ?";
                
        return $this->db->query($sql, [$status, $offset, $perPage]);
    }

    public function countReviewsByStatus(string $status): int
    {
        $sql = "SELECT COUNT(*) as count FROM reviews WHERE status = ?";
        $result = $this->db->query($sql, [$status]);
        
        return (int)($result[0]['count'] ?? 0);
    }

    public function getAverageRating(int $productId): float
    {
        $sql = "SELECT AVG(rating) as average_rating 
                FROM reviews 
                WHERE product_id = ? AND status = 'approved'";
                
        $result = $this->db->query($sql, [$productId]);
        
        return round((float)($result[0]['average_rating'] ?? 0), 1);
    }

    public function getReviewCount(int $productId, string $status = 'approved'): int
    {
        $sql = "SELECT COUNT(*) as count 
                FROM reviews 
                WHERE product_id = ? AND status = ?";
                
        $result = $this->db->query($sql, [$productId, $status]);
        
        return (int)($result[0]['count'] ?? 0);
    }

    public function getRatingDistribution(int $productId): array
    {
        $distribution = [];
        
        // Initialize distribution array with 0 counts for ratings 1-5
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = 0;
        }
        
        $sql = "SELECT rating, COUNT(*) as count 
                FROM reviews 
                WHERE product_id = ? AND status = 'approved' 
                GROUP BY rating";
                
        $results = $this->db->query($sql, [$productId]);
        
        foreach ($results as $result) {
            $rating = (int)$result['rating'];
            $count = (int)$result['count'];
            
            $distribution[$rating] = $count;
        }
        
        return $distribution;
    }

    public function hasUserReviewedProduct(int $userId, int $productId): bool
    {
        $sql = "SELECT COUNT(*) as count 
                FROM reviews 
                WHERE user_id = ? AND product_id = ?";
                
        $result = $this->db->query($sql, [$userId, $productId]);
        
        return (int)($result[0]['count'] ?? 0) > 0;
    }

    public function getUserReview(int $userId, int $productId): ?array
    {
        $sql = "SELECT * FROM reviews WHERE user_id = ? AND product_id = ?";
        $reviews = $this->db->query($sql, [$userId, $productId]);
        
        return $reviews[0] ?? null;
    }

    public function updateReview(
        int $reviewId, 
        int $rating, 
        string $title, 
        string $comment
    ): bool {
        $sql = "UPDATE reviews SET 
                rating = ?, 
                title = ?, 
                comment = ?, 
                status = 'pending', 
                updated_at = NOW() 
                WHERE id = ?";
                
        return $this->db->execute($sql, [$rating, $title, $comment, $reviewId]);
    }
} 