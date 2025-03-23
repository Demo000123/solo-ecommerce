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
    private Database $db;
    private $categoryService;

    public function __construct()
    {
        $this->db = new Database();
        $this->categoryService = new CategoryService();
    }

    /**
     * Get products with optional filtering and pagination
     * 
     * @param string $search Search term
     * @param int $categoryId Filter by category ID
     * @param string $sortBy Sort by field
     * @param int $page Page number
     * @param int $perPage Products per page
     * @return array Array of products
     */
    public function getProducts(
        string $search = '', 
        int $categoryId = 0, 
        string $sortBy = 'newest', 
        int $page = 1, 
        int $perPage = 12
    ): array {
        $params = [];
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active'";

        if (!empty($search)) {
            $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if ($categoryId > 0) {
            $sql .= " AND p.category_id = ?";
            $params[] = $categoryId;
        }

        switch ($sortBy) {
            case 'price_low':
                $sql .= " ORDER BY p.price ASC";
                break;
            case 'price_high':
                $sql .= " ORDER BY p.price DESC";
                break;
            case 'name':
                $sql .= " ORDER BY p.name ASC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY p.created_at DESC";
                break;
        }

        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $perPage;

        return $this->db->query($sql, $params);
    }

    /**
     * Get a product by ID
     * 
     * @param int $id Product ID
     * @return array|null Product data or null if not found
     */
    public function getProductById(int $id): ?array
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.id = ?";

        $products = $this->db->query($sql, [$id]);
        
        return $products[0] ?? null;
    }

    /**
     * Get related products for a given product
     * 
     * @param int $productId Current product ID
     * @param int $categoryId Related category ID
     * @param int $limit Maximum number of related products
     * @return array Array of related products
     */
    public function getRelatedProducts(int $productId, int $categoryId, int $limit = 4): array
    {
        $sql = "SELECT * FROM products 
                WHERE status = 'active' 
                AND id != ? 
                AND category_id = ?
                ORDER BY RAND() 
                LIMIT ?";

        return $this->db->query($sql, [$productId, $categoryId, $limit]);
    }

    /**
     * Get featured products
     * 
     * @param int $limit Maximum number of featured products
     * @return array Array of featured products
     */
    public function getFeaturedProducts(int $limit = 8): array
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active' AND p.is_featured = 1
                ORDER BY p.created_at DESC
                LIMIT ?";

        return $this->db->query($sql, [$limit]);
    }

    /**
     * Get new arrivals
     * 
     * @param int $limit Maximum number of new arrivals
     * @return array Array of new arrivals
     */
    public function getNewArrivals(int $limit = 8): array
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active'
                ORDER BY p.created_at DESC
                LIMIT ?";

        return $this->db->query($sql, [$limit]);
    }

    /**
     * Get popular products
     * 
     * @param int $limit Maximum number of popular products
     * @return array Array of popular products
     */
    public function getPopularProducts(int $limit = 8): array
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active'
                ORDER BY p.sales_count DESC
                LIMIT ?";

        return $this->db->query($sql, [$limit]);
    }

    /**
     * Get admin products
     * 
     * @param string $search Search term
     * @param int $page Page number
     * @param int $perPage Products per page
     * @return array Array of admin products
     */
    public function getAdminProducts(string $search = '', int $page = 1, int $perPage = 10): array
    {
        $params = [];
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id";

        if (!empty($search)) {
            $sql .= " WHERE p.name LIKE ? OR p.description LIKE ?";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $sql .= " ORDER BY p.id DESC";

        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $perPage;

        return $this->db->query($sql, $params);
    }

    /**
     * Get admin product count
     * 
     * @param string $search Search term
     * @return int Total admin products count
     */
    public function getAdminProductCount(string $search = ''): int
    {
        $params = [];
        $sql = "SELECT COUNT(*) as count FROM products";

        if (!empty($search)) {
            $sql .= " WHERE name LIKE ? OR description LIKE ?";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $result = $this->db->query($sql, $params);
        return (int)($result[0]['count'] ?? 0);
    }

    /**
     * Create a new product
     * 
     * @param string $name Product name
     * @param string $description Product description
     * @param string $shortDescription Product short description
     * @param float $price Product price
     * @param ?float $salePrice Product sale price
     * @param string $image Product image
     * @param int $stock Product stock
     * @param int $categoryId Product category ID
     * @param string $status Product status
     * @param int $isFeatured Product featured status
     * @return int Inserted product ID
     */
    public function createProduct(
        string $name,
        string $description,
        string $shortDescription,
        float $price,
        ?float $salePrice,
        string $image,
        int $stock,
        int $categoryId,
        string $status = 'active',
        int $isFeatured = 0
    ): int {
        $sql = "INSERT INTO products (
                    name, description, short_description, price, sale_price, 
                    image, stock, category_id, status, is_featured, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $params = [
            $name, $description, $shortDescription, $price, $salePrice,
            $image, $stock, $categoryId, $status, $isFeatured
        ];

        return $this->db->insert($sql, $params);
    }

    /**
     * Update an existing product
     * 
     * @param int $productId Product ID
     * @param string $name Product name
     * @param string $description Product description
     * @param string $shortDescription Product short description
     * @param float $price Product price
     * @param ?float $salePrice Product sale price
     * @param string $image Product image
     * @param int $stock Product stock
     * @param int $categoryId Product category ID
     * @param string $status Product status
     * @param int $isFeatured Product featured status
     * @return bool True if update was successful, false otherwise
     */
    public function updateProduct(
        int $productId,
        string $name,
        string $description,
        string $shortDescription,
        float $price,
        ?float $salePrice,
        string $image,
        int $stock,
        int $categoryId,
        string $status = 'active',
        int $isFeatured = 0
    ): bool {
        $sql = "UPDATE products SET
                    name = ?, description = ?, short_description = ?, price = ?, 
                    sale_price = ?, image = ?, stock = ?, category_id = ?, 
                    status = ?, is_featured = ?, updated_at = NOW()
                WHERE id = ?";

        $params = [
            $name, $description, $shortDescription, $price, $salePrice,
            $image, $stock, $categoryId, $status, $isFeatured, $productId
        ];

        return $this->db->execute($sql, $params);
    }

    /**
     * Delete a product
     * 
     * @param int $productId Product ID
     * @return bool True if deletion was successful, false otherwise
     */
    public function deleteProduct(int $productId): bool
    {
        $sql = "DELETE FROM products WHERE id = ?";
        return $this->db->execute($sql, [$productId]);
    }

    /**
     * Update product stock
     * 
     * @param int $productId Product ID
     * @param int $quantity Quantity to subtract from stock
     * @return bool True if stock update was successful, false otherwise
     */
    public function updateStock(int $productId, int $quantity): bool
    {
        $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
        return $this->db->execute($sql, [$quantity, $productId]);
    }

    /**
     * Increment product sales count
     * 
     * @param int $productId Product ID
     * @param int $quantity Quantity to add to sales count
     * @return bool True if sales count update was successful, false otherwise
     */
    public function incrementSalesCount(int $productId, int $quantity = 1): bool
    {
        $sql = "UPDATE products SET sales_count = sales_count + ? WHERE id = ?";
        return $this->db->execute($sql, [$quantity, $productId]);
    }

    /**
     * Get product count
     * 
     * @param string $search Search term
     * @param int $categoryId Filter by category ID
     * @return int Total products count
     */
    public function getProductCount(string $search = '', int $categoryId = 0): int
    {
        $params = [];
        $sql = "SELECT COUNT(*) as count FROM products WHERE status = 'active'";

        if (!empty($search)) {
            $sql .= " AND (name LIKE ? OR description LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        if ($categoryId > 0) {
            $sql .= " AND category_id = ?";
            $params[] = $categoryId;
        }

        $result = $this->db->query($sql, $params);
        return (int)($result[0]['count'] ?? 0);
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

    public function getCategoryCount(string $category): int
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