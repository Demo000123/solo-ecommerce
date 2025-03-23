<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class UserService
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getUserById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $users = $this->db->query($sql, [$id]);
        
        return $users[0] ?? null;
    }

    public function getUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $users = $this->db->query($sql, [$email]);
        
        return $users[0] ?? null;
    }

    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $result = $this->db->query($sql, [$email]);
        
        return (int)($result[0]['count'] ?? 0) > 0;
    }

    public function authenticateUser(string $email, string $password): ?array
    {
        $user = $this->getUserByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            return null;
        }
        
        return $user;
    }

    public function registerUser(string $fullname, string $email, string $password, string $phone = ''): int
    {
        $sql = "INSERT INTO users (fullname, email, password, phone, role, created_at) 
                VALUES (?, ?, ?, ?, 'customer', NOW())";
                
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        return $this->db->insert($sql, [$fullname, $email, $hashedPassword, $phone]);
    }

    public function updateLastLogin(int $userId): bool
    {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$userId]);
    }

    public function updateUserProfile(
        int $userId, 
        string $fullname, 
        string $email, 
        string $phone = '', 
        string $address = '', 
        string $province = '', 
        string $district = '', 
        string $ward = ''
    ): bool {
        $sql = "UPDATE users SET 
                fullname = ?, 
                email = ?, 
                phone = ?, 
                address = ?, 
                province = ?, 
                district = ?, 
                ward = ?, 
                updated_at = NOW() 
                WHERE id = ?";
                
        return $this->db->execute($sql, [
            $fullname, $email, $phone, $address, $province, $district, $ward, $userId
        ]);
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        // Get user details
        $user = $this->getUserById($userId);
        
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return false;
        }
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
        
        return $this->db->execute($sql, [$hashedPassword, $userId]);
    }

    public function getUserAddresses(int $userId): array
    {
        $sql = "SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC";
        return $this->db->query($sql, [$userId]);
    }

    public function addUserAddress(
        int $userId, 
        string $fullname, 
        string $phone, 
        string $address, 
        string $province, 
        string $district, 
        string $ward, 
        bool $isDefault = false
    ): int {
        // If this is the default address, unset any existing default addresses
        if ($isDefault) {
            $this->unsetDefaultAddresses($userId);
        }
        
        $sql = "INSERT INTO user_addresses (
                    user_id, fullname, phone, address, province, district, ward, is_default, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                
        return $this->db->insert($sql, [
            $userId, $fullname, $phone, $address, $province, $district, $ward, $isDefault ? 1 : 0
        ]);
    }

    public function updateUserAddress(
        int $addressId, 
        string $fullname, 
        string $phone, 
        string $address, 
        string $province, 
        string $district, 
        string $ward, 
        bool $isDefault = false
    ): bool {
        // Get the user ID for this address
        $sql = "SELECT user_id FROM user_addresses WHERE id = ?";
        $result = $this->db->query($sql, [$addressId]);
        
        if (empty($result)) {
            return false;
        }
        
        $userId = (int)$result[0]['user_id'];
        
        // If this is the default address, unset any existing default addresses
        if ($isDefault) {
            $this->unsetDefaultAddresses($userId);
        }
        
        $sql = "UPDATE user_addresses SET 
                fullname = ?, 
                phone = ?, 
                address = ?, 
                province = ?, 
                district = ?, 
                ward = ?, 
                is_default = ?, 
                updated_at = NOW() 
                WHERE id = ?";
                
        return $this->db->execute($sql, [
            $fullname, $phone, $address, $province, $district, $ward, $isDefault ? 1 : 0, $addressId
        ]);
    }

    public function deleteUserAddress(int $addressId): bool
    {
        $sql = "DELETE FROM user_addresses WHERE id = ?";
        return $this->db->execute($sql, [$addressId]);
    }

    public function setDefaultAddress(int $addressId): bool
    {
        // Get the user ID for this address
        $sql = "SELECT user_id FROM user_addresses WHERE id = ?";
        $result = $this->db->query($sql, [$addressId]);
        
        if (empty($result)) {
            return false;
        }
        
        $userId = (int)$result[0]['user_id'];
        
        // Unset any existing default addresses
        $this->unsetDefaultAddresses($userId);
        
        // Set this address as default
        $sql = "UPDATE user_addresses SET is_default = 1 WHERE id = ?";
        return $this->db->execute($sql, [$addressId]);
    }

    private function unsetDefaultAddresses(int $userId): bool
    {
        $sql = "UPDATE user_addresses SET is_default = 0 WHERE user_id = ?";
        return $this->db->execute($sql, [$userId]);
    }

    public function getUserOrders(int $userId): array
    {
        $sql = "SELECT o.*, COUNT(oi.id) as item_count 
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.user_id = ?
                GROUP BY o.id
                ORDER BY o.created_at DESC";
                
        return $this->db->query($sql, [$userId]);
    }

    public function getTotalUsers(): int
    {
        $sql = "SELECT COUNT(*) as count FROM users";
        $result = $this->db->query($sql);
        
        return (int)($result[0]['count'] ?? 0);
    }

    public function getRecentUsers(int $limit = 5): array
    {
        $sql = "SELECT * FROM users ORDER BY created_at DESC LIMIT ?";
        return $this->db->query($sql, [$limit]);
    }

    public function getAllUsers(int $page = 1, int $perPage = 10, string $search = ''): array
    {
        $params = [];
        $sql = "SELECT * FROM users";
        
        if (!empty($search)) {
            $sql .= " WHERE fullname LIKE ? OR email LIKE ? OR phone LIKE ?";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $perPage;
        
        return $this->db->query($sql, $params);
    }

    public function getUserCount(string $search = ''): int
    {
        $params = [];
        $sql = "SELECT COUNT(*) as count FROM users";
        
        if (!empty($search)) {
            $sql .= " WHERE fullname LIKE ? OR email LIKE ? OR phone LIKE ?";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        $result = $this->db->query($sql, $params);
        
        return (int)($result[0]['count'] ?? 0);
    }
} 