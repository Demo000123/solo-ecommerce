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

    public function getAllProducts(): array
    {
        $stmt = $this->db->query('SELECT * FROM products ORDER BY name');
        $productsData = $stmt->fetchAll();
        
        $products = [];
        foreach ($productsData as $productData) {
            $products[] = $this->createProductFromData($productData);
        }
        
        return $products;
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

    public function getProductsByCategory(string $category): array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE category = :category ORDER BY name');
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        
        $productsData = $stmt->fetchAll();
        
        $products = [];
        foreach ($productsData as $productData) {
            $products[] = $this->createProductFromData($productData);
        }
        
        return $products;
    }

    public function searchProducts(string $term): array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE name LIKE :term OR description LIKE :term ORDER BY name');
        $stmt->bindValue(':term', "%{$term}%", PDO::PARAM_STR);
        $stmt->execute();
        
        $productsData = $stmt->fetchAll();
        
        $products = [];
        foreach ($productsData as $productData) {
            $products[] = $this->createProductFromData($productData);
        }
        
        return $products;
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