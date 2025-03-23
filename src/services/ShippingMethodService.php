<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class ShippingMethodService
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllShippingMethods(): array
    {
        $sql = "SELECT * FROM shipping_methods ORDER BY price ASC";
        return $this->db->query($sql);
    }

    public function getActiveShippingMethods(): array
    {
        $sql = "SELECT * FROM shipping_methods WHERE status = 1 ORDER BY price ASC";
        return $this->db->query($sql);
    }

    public function getShippingMethodById(int $id): ?array
    {
        $sql = "SELECT * FROM shipping_methods WHERE id = ?";
        $methods = $this->db->query($sql, [$id]);
        
        return $methods[0] ?? null;
    }

    public function createShippingMethod(
        string $name,
        string $description,
        float $price,
        string $estimatedDays,
        int $status = 1
    ): int {
        $sql = "INSERT INTO shipping_methods (
                    name, description, price, estimated_days, status, created_at
                ) VALUES (?, ?, ?, ?, ?, NOW())";
                
        return $this->db->insert($sql, [$name, $description, $price, $estimatedDays, $status]);
    }

    public function updateShippingMethod(
        int $id,
        string $name,
        string $description,
        float $price,
        string $estimatedDays,
        int $status = 1
    ): bool {
        $sql = "UPDATE shipping_methods SET
                    name = ?, description = ?, price = ?, estimated_days = ?,
                    status = ?, updated_at = NOW()
                WHERE id = ?";
                
        return $this->db->execute($sql, [$name, $description, $price, $estimatedDays, $status, $id]);
    }

    public function deleteShippingMethod(int $id): bool
    {
        $sql = "DELETE FROM shipping_methods WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    public function toggleShippingMethodStatus(int $id): bool
    {
        $sql = "UPDATE shipping_methods SET status = 1 - status, updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    public function getDefaultShippingMethod(): ?array
    {
        // Get the first active shipping method
        $sql = "SELECT * FROM shipping_methods WHERE status = 1 ORDER BY price ASC LIMIT 1";
        $methods = $this->db->query($sql);
        
        return $methods[0] ?? null;
    }

    public function getShippingCost(string $methodName): float
    {
        $sql = "SELECT price FROM shipping_methods WHERE name = ? AND status = 1";
        $result = $this->db->query($sql, [$methodName]);
        
        return (float)($result[0]['price'] ?? 0);
    }
} 