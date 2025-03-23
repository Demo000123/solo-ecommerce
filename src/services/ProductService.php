<?php
declare(strict_types=1);

namespace App\Services;

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/CategoryService.php';

use App\Core\Database;
use App\Models\Product;
use PDO;

class ProductService
{
    private $db;
    private $categoryService;

    public function __construct()
    {
        $this->db = \App\Config\Database::getInstance()->getConnection();
        $this->categoryService = new CategoryService();
    }

    /**
     * Get products with optional filtering and pagination
     * 
     * @param int|null $categoryId Filter by category ID
     * @param string $sort Field to sort by
     * @param string $sortDirection Sort direction ('ASC' or 'DESC')
     * @param int $page Page number
     * @param int $limit Products per page
     * @return array Array of products
     */
    public function getProducts(
        ?int $categoryId = null,
        string $sort = 'id',
        string $sortDirection = 'ASC',
        int $page = 1,
        int $limit = 12
    ): array {
        try {
            $query = "SELECT p.*, c.name AS category_name 
                     FROM products p
                     LEFT JOIN categories c ON p.category_id = c.id";
            
            $params = [];
            
            // Add category filter if provided
            if ($categoryId !== null) {
                $query .= " WHERE p.category_id = ?";
                $params[] = $categoryId;
            }
            
            // Add sorting
            $allowedSortFields = ['id', 'name', 'price', 'created_at'];
            $sort = in_array($sort, $allowedSortFields) ? $sort : 'id';
            $sortDirection = strtoupper($sortDirection) === 'DESC' ? 'DESC' : 'ASC';
            
            $query .= " ORDER BY p.$sort $sortDirection";
            
            // Add pagination
            $offset = ($page - 1) * $limit;
            $query .= " LIMIT ?, ?";
            $params[] = (int)$offset;
            $params[] = (int)$limit;
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            
            $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return !empty($products) ? $products : [];
        } catch (\PDOException $e) {
            // For demo purposes, return placeholder products
            return [];
        }
    }

    /**
     * Get a product by ID
     * 
     * @param int $id Product ID
     * @return array|null Product data or null if not found
     */
    public function getProductById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT p.*, c.name AS category_name 
                                        FROM products p 
                                        LEFT JOIN categories c ON p.category_id = c.id 
                                        WHERE p.id = ?");
            $stmt->execute([$id]);
            
            $product = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $product ?: null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    /**
     * Get related products for a given product
     * 
     * @param int $productId Current product ID
     * @param int $limit Maximum number of related products
     * @return array Array of related products
     */
    public function getRelatedProducts(int $productId, int $limit = 4): array
    {
        try {
            // Get current product
            $product = $this->getProductById($productId);
            
            if (!$product) {
                return [];
            }
            
            // First try: Get products from the same category, excluding the current product
            $stmt = $this->db->prepare("
                SELECT p.*, c.name AS category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = ? AND p.id != ? 
                ORDER BY RAND() 
                LIMIT ?
            ");
            $stmt->execute([$product['category_id'], $productId, $limit]);
            
            $relatedProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // If we have enough related products, return them
            if (count($relatedProducts) >= $limit) {
                return $relatedProducts;
            }
            
            // Second try: Get products in a similar price range (±30% of the current product price)
            $priceMin = $product['price'] * 0.7;
            $priceMax = $product['price'] * 1.3;
            $remaining = $limit - count($relatedProducts);
            
            // Exclude products already selected and the current product
            $existingIds = array_merge([$productId], array_column($relatedProducts, 'id'));
            $placeholders = implode(',', array_fill(0, count($existingIds), '?'));
            
            $params = array_merge($existingIds, [$priceMin, $priceMax, $remaining]);
            
            $stmt = $this->db->prepare("
                SELECT p.*, c.name AS category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id NOT IN ($placeholders) 
                  AND p.price BETWEEN ? AND ? 
                ORDER BY RAND() 
                LIMIT ?
            ");
            $stmt->execute($params);
            
            $priceRelatedProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $relatedProducts = array_merge($relatedProducts, $priceRelatedProducts);
            
            // If we still don't have enough, just get random products
            if (count($relatedProducts) < $limit) {
                $remaining = $limit - count($relatedProducts);
                $existingIds = array_merge([$productId], array_column($relatedProducts, 'id'));
                $placeholders = implode(',', array_fill(0, count($existingIds), '?'));
                
                $params = array_merge($existingIds, [$remaining]);
                
                $stmt = $this->db->prepare("
                    SELECT p.*, c.name AS category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.id NOT IN ($placeholders) 
                    ORDER BY RAND() 
                    LIMIT ?
                ");
                $stmt->execute($params);
                
                $randomProducts = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $relatedProducts = array_merge($relatedProducts, $randomProducts);
            }
            
            return $relatedProducts;
        } catch (\PDOException $e) {
            // Log error in production
            return [];
        }
    }

    /**
     * Count total products matching the current filters
     * 
     * @param int|null $categoryId Filter by category
     * @return int Total products count
     */
    public function countProducts(?int $categoryId = null): int
    {
        try {
            $query = "SELECT COUNT(*) FROM products";
            $params = [];
            
            if ($categoryId !== null) {
                $query .= " WHERE category_id = ?";
                $params[] = $categoryId;
            }
            
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    public function getAllProducts(string $sortBy = 'name', string $sortDirection = 'asc', int $page = 1, int $perPage = 9): array
    {
        $offset = ($page - 1) * $perPage;
        
        // Validate sort parameters to prevent SQL injection
        $allowedSortFields = ['name', 'price', 'category_id'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
        
        $sortDirection = strtolower($sortDirection) === 'desc' ? 'DESC' : 'ASC';
        
        $stmt = $this->db->prepare("SELECT p.*, c.name AS category_name 
                                   FROM products p 
                                   LEFT JOIN categories c ON p.category_id = c.id 
                                   ORDER BY p.{$sortBy} {$sortDirection} 
                                   LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductCount(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM products');
        return (int) $stmt->fetchColumn();
    }
    
    public function getCategoryCount(string $category): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM products WHERE category = :category');
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
    
    public function getSearchCount(string $term): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM products WHERE name LIKE :term OR description LIKE :term');
        $stmt->bindValue(':term', "%{$term}%", PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getProductsByCategory(
        int $categoryId, 
        string $sortBy = 'name', 
        string $sortDirection = 'asc', 
        int $page = 1, 
        int $perPage = 9
    ): array {
        $offset = ($page - 1) * $perPage;
        
        // Validate sort parameters
        $allowedSortFields = ['name', 'price', 'created_at'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
        
        $sortDirection = strtolower($sortDirection) === 'desc' ? 'DESC' : 'ASC';
        
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name 
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.category_id = :category_id 
             ORDER BY p.{$sortBy} {$sortDirection} 
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchProducts(
        string $term, 
        string $sortBy = 'name', 
        string $sortDirection = 'asc', 
        int $page = 1, 
        int $perPage = 9
    ): array {
        $offset = ($page - 1) * $perPage;
        
        // Validate sort parameters
        $allowedSortFields = ['name', 'price', 'category_id'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
        
        $sortDirection = strtolower($sortDirection) === 'desc' ? 'DESC' : 'ASC';
        
        $stmt = $this->db->prepare(
            "SELECT p.*, c.name AS category_name 
             FROM products p
             LEFT JOIN categories c ON p.category_id = c.id
             WHERE p.name LIKE :term OR p.description LIKE :term 
             ORDER BY p.{$sortBy} {$sortDirection} 
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':term', "%{$term}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getCategories(): array
    {
        return $this->categoryService->getCategories();
    }

    private function createProductFromData(array $data): Product
    {
        return new Product(
            (int) $data['id'],
            $data['name'],
            $data['description'],
            (float) $data['price'],
            $data['image'],
            (int) $data['stock'],
            $data['category']
        );
    }
} 