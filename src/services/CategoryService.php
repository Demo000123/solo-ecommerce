<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class CategoryService
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllCategories(): array
    {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->db->query($sql);
    }

    public function getCategoryById(int $id): ?array
    {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $categories = $this->db->query($sql, [$id]);
        
        return $categories[0] ?? null;
    }

    public function getCategoryBySlug(string $slug): ?array
    {
        $sql = "SELECT * FROM categories WHERE slug = ?";
        $categories = $this->db->query($sql, [$slug]);
        
        return $categories[0] ?? null;
    }

    public function createCategory(string $name, string $description = '', string $slug = ''): int
    {
        // Generate slug if not provided
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }
        
        $sql = "INSERT INTO categories (name, description, slug, created_at) 
                VALUES (?, ?, ?, NOW())";
                
        return $this->db->insert($sql, [$name, $description, $slug]);
    }

    public function updateCategory(int $id, string $name, string $description = '', string $slug = ''): bool
    {
        // Generate slug if not provided
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }
        
        $sql = "UPDATE categories SET 
                name = ?, 
                description = ?, 
                slug = ?, 
                updated_at = NOW() 
                WHERE id = ?";
                
        return $this->db->execute($sql, [$name, $description, $slug, $id]);
    }

    public function deleteCategory(int $id): bool
    {
        $sql = "DELETE FROM categories WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    public function getCategoriesWithProductCount(): array
    {
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM categories c
                LEFT JOIN products p ON c.id = p.category_id AND p.status = 'active'
                GROUP BY c.id
                ORDER BY c.name ASC";
                
        return $this->db->query($sql);
    }

    public function getParentCategories(): array
    {
        $sql = "SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name ASC";
        return $this->db->query($sql);
    }

    public function getSubcategories(int $parentId): array
    {
        $sql = "SELECT * FROM categories WHERE parent_id = ? ORDER BY name ASC";
        return $this->db->query($sql, [$parentId]);
    }

    public function getCategoryPath(int $categoryId): array
    {
        $path = [];
        $currentCategory = $this->getCategoryById($categoryId);
        
        if ($currentCategory) {
            $path[] = $currentCategory;
            
            while (isset($currentCategory['parent_id']) && $currentCategory['parent_id']) {
                $currentCategory = $this->getCategoryById($currentCategory['parent_id']);
                if ($currentCategory) {
                    array_unshift($path, $currentCategory);
                } else {
                    break;
                }
            }
        }
        
        return $path;
    }

    private function generateSlug(string $name): string
    {
        // Convert to lowercase
        $slug = strtolower($name);
        
        // Replace non-alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        
        // Replace multiple hyphens with a single hyphen
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Remove leading and trailing hyphens
        $slug = trim($slug, '-');
        
        // Check if slug already exists
        $baseSlug = $slug;
        $count = 1;
        
        while ($this->slugExists($slug)) {
            $slug = $baseSlug . '-' . $count++;
        }
        
        return $slug;
    }

    private function slugExists(string $slug): bool
    {
        $sql = "SELECT COUNT(*) as count FROM categories WHERE slug = ?";
        $result = $this->db->query($sql, [$slug]);
        
        return (int)($result[0]['count'] ?? 0) > 0;
    }
}