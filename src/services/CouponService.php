<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class CouponService
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllCoupons(int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM coupons ORDER BY created_at DESC LIMIT ?, ?";
        
        return $this->db->query($sql, [$offset, $perPage]);
    }

    public function getTotalCoupons(): int
    {
        $sql = "SELECT COUNT(*) as count FROM coupons";
        $result = $this->db->query($sql);
        
        return (int)($result[0]['count'] ?? 0);
    }

    public function getCouponById(int $id): ?array
    {
        $sql = "SELECT * FROM coupons WHERE id = ?";
        $coupons = $this->db->query($sql, [$id]);
        
        return $coupons[0] ?? null;
    }

    public function getCouponByCode(string $code): ?array
    {
        $sql = "SELECT * FROM coupons WHERE code = ?";
        $coupons = $this->db->query($sql, [$code]);
        
        return $coupons[0] ?? null;
    }

    public function codeExists(string $code): bool
    {
        $sql = "SELECT COUNT(*) as count FROM coupons WHERE code = ?";
        $result = $this->db->query($sql, [$code]);
        
        return (int)($result[0]['count'] ?? 0) > 0;
    }

    public function createCoupon(
        string $code,
        string $type,
        float $value,
        float $minOrderAmount = 0,
        ?float $maxDiscountAmount = null,
        string $startDate = '',
        string $endDate = '',
        ?int $usageLimit = null,
        int $status = 1
    ): int {
        $sql = "INSERT INTO coupons (
                    code, type, value, min_order_amount, max_discount_amount,
                    start_date, end_date, usage_limit, used_count, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, NOW())";
                
        $params = [
            $code, $type, $value, $minOrderAmount, $maxDiscountAmount,
            !empty($startDate) ? $startDate : null,
            !empty($endDate) ? $endDate : null,
            $usageLimit, $status
        ];
        
        return $this->db->insert($sql, $params);
    }

    public function updateCoupon(
        int $id,
        string $code,
        string $type,
        float $value,
        float $minOrderAmount = 0,
        ?float $maxDiscountAmount = null,
        string $startDate = '',
        string $endDate = '',
        ?int $usageLimit = null,
        int $status = 1
    ): bool {
        $sql = "UPDATE coupons SET
                    code = ?, type = ?, value = ?, min_order_amount = ?,
                    max_discount_amount = ?, start_date = ?, end_date = ?,
                    usage_limit = ?, status = ?, updated_at = NOW()
                WHERE id = ?";
                
        $params = [
            $code, $type, $value, $minOrderAmount, $maxDiscountAmount,
            !empty($startDate) ? $startDate : null,
            !empty($endDate) ? $endDate : null,
            $usageLimit, $status, $id
        ];
        
        return $this->db->execute($sql, $params);
    }

    public function deleteCoupon(int $id): bool
    {
        $sql = "DELETE FROM coupons WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    public function incrementCouponUsage(int $id): bool
    {
        $sql = "UPDATE coupons SET used_count = used_count + 1, updated_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    public function validateCoupon(array $coupon, float $subtotal): bool
    {
        // Check if coupon is active
        if ($coupon['status'] != 1) {
            return false;
        }
        
        // Check if minimum order amount is met
        if ($coupon['min_order_amount'] > 0 && $subtotal < $coupon['min_order_amount']) {
            return false;
        }
        
        // Check if usage limit is reached
        if ($coupon['usage_limit'] !== null && $coupon['used_count'] >= $coupon['usage_limit']) {
            return false;
        }
        
        // Check if coupon is within valid date range
        $now = time();
        
        if ($coupon['start_date'] !== null) {
            $startDate = strtotime($coupon['start_date']);
            if ($now < $startDate) {
                return false;
            }
        }
        
        if ($coupon['end_date'] !== null) {
            $endDate = strtotime($coupon['end_date']);
            if ($now > $endDate) {
                return false;
            }
        }
        
        return true;
    }

    public function calculateDiscount(array $coupon, float $subtotal): float
    {
        $discount = 0;
        
        if ($coupon['type'] === 'percentage') {
            $discount = $subtotal * ($coupon['value'] / 100);
        } else if ($coupon['type'] === 'fixed') {
            $discount = $coupon['value'];
        }
        
        // Ensure discount doesn't exceed the maximum allowed discount amount
        if ($coupon['max_discount_amount'] !== null && $discount > $coupon['max_discount_amount']) {
            $discount = $coupon['max_discount_amount'];
        }
        
        // Ensure discount doesn't exceed the subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }
        
        return $discount;
    }

    public function getActiveCoupons(): array
    {
        $now = date('Y-m-d H:i:s');
        
        $sql = "SELECT * FROM coupons 
                WHERE status = 1 
                AND (start_date IS NULL OR start_date <= ?) 
                AND (end_date IS NULL OR end_date >= ?) 
                AND (usage_limit IS NULL OR used_count < usage_limit)";
                
        return $this->db->query($sql, [$now, $now]);
    }
} 