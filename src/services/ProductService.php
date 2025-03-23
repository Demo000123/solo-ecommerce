<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Product;
use PDO;

class ProductService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllProducts(string $sortBy = 'name', string $sortDirection = 'asc', int $page = 1, int $perPage = 9): array
    {
        $offset = ($page - 1) * $perPage;
        
        // Validate sort parameters to prevent SQL injection
        $allowedSortFields = ['name', 'price', 'category'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
        
        $sortDirection = strtolower($sortDirection) === 'desc' ? 'DESC' : 'ASC';
        
        $stmt = $this->db->prepare("SELECT * FROM products ORDER BY {$sortBy} {$sortDirection} LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $productsData = $stmt->fetchAll();
        
        $products = [];
        foreach ($productsData as $productData) {
            $products[] = $this->createProductFromData($productData);
        }
        
        return $products;
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

    public function getProductById(int $id): ?Product
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $productData = $stmt->fetch();
        
        if (!$productData) {
            return null;
        }
        
        return $this->createProductFromData($productData);
    }

    public function getProductsByCategory(
        string $category, 
        string $sortBy = 'name', 
        string $sortDirection = 'asc', 
        int $page = 1, 
        int $perPage = 9
    ): array {
        $offset = ($page - 1) * $perPage;
        
        // Validate sort parameters
        $allowedSortFields = ['name', 'price', 'category'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
        
        $sortDirection = strtolower($sortDirection) === 'desc' ? 'DESC' : 'ASC';
        
        $stmt = $this->db->prepare(
            "SELECT * FROM products WHERE category = :category ORDER BY {$sortBy} {$sortDirection} LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $productsData = $stmt->fetchAll();
        
        $products = [];
        foreach ($productsData as $productData) {
            $products[] = $this->createProductFromData($productData);
        }
        
        return $products;
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
        $allowedSortFields = ['name', 'price', 'category'];
        $sortBy = in_array($sortBy, $allowedSortFields) ? $sortBy : 'name';
        
        $sortDirection = strtolower($sortDirection) === 'desc' ? 'DESC' : 'ASC';
        
        $stmt = $this->db->prepare(
            "SELECT * FROM products WHERE name LIKE :term OR description LIKE :term ORDER BY {$sortBy} {$sortDirection} LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':term', "%{$term}%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $productsData = $stmt->fetchAll();
        
        $products = [];
        foreach ($productsData as $productData) {
            $products[] = $this->createProductFromData($productData);
        }
        
        return $products;
    }
    
    public function getCategories(): array
    {
        $stmt = $this->db->query('SELECT DISTINCT category FROM products ORDER BY category');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
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