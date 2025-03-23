<?php
declare(strict_types=1);

namespace App\Services;

require_once __DIR__ . '/../config/database.php';

class CategoryService
{
    private $db;

    public function __construct()
    {
        $this->db = \App\Config\Database::getInstance()->getConnection();
    }

    /**
     * Get all categories
     * 
     * @return array Array of categories
     */
    public function getCategories(): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name");
            $stmt->execute();
            
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: $this->getPlaceholderCategories();
        } catch (\PDOException $e) {
            // For the demo, return placeholder categories if there's a database error
            return $this->getPlaceholderCategories();
        }
    }

    /**
     * Get a category by ID
     * 
     * @param int $id Category ID
     * @return array|null Category data or null if not found
     */
    public function getCategoryById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            
            $category = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $category ?: null;
        } catch (\PDOException $e) {
            // Search in placeholder categories
            foreach ($this->getPlaceholderCategories() as $category) {
                if ($category['id'] === $id) {
                    return $category;
                }
            }
            
            return null;
        }
    }
    
    /**
     * Returns placeholder categories for demo purposes
     * 
     * @return array
     */
    private function getPlaceholderCategories(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Electronics',
                'description' => 'Cutting-edge technology and gadgets for modern living',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'name' => 'Home & Kitchen',
                'description' => 'Transform your living space with our essential products',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'name' => 'Clothing',
                'description' => 'Stay stylish with our latest fashion collections',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'name' => 'Beauty & Personal Care',
                'description' => 'Enhance your natural beauty with premium products',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'name' => 'Sports & Outdoors',
                'description' => 'Gear up for your active lifestyle and adventures',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];
    }
} 