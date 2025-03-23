<?php
declare(strict_types=1);

namespace App\Models;

class Coupon
{
    private int $id;
    private string $code;
    private string $type;
    private float $value;
    private float $min_order_amount;
    private ?float $max_discount_amount;
    private ?string $start_date;
    private ?string $end_date;
    private ?int $usage_limit;
    private int $usage_count;
    private int $status;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        string $code,
        string $type,
        float $value,
        float $min_order_amount = 0.0,
        ?float $max_discount_amount = null,
        ?string $start_date = null,
        ?string $end_date = null,
        ?int $usage_limit = null,
        int $usage_count = 0,
        int $status = 1,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->value = $value;
        $this->min_order_amount = $min_order_amount;
        $this->max_discount_amount = $max_discount_amount;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->usage_limit = $usage_limit;
        $this->usage_count = $usage_count;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getMinOrderAmount(): float
    {
        return $this->min_order_amount;
    }

    public function getMaxDiscountAmount(): ?float
    {
        return $this->max_discount_amount;
    }

    public function getStartDate(): ?string
    {
        return $this->start_date;
    }

    public function getEndDate(): ?string
    {
        return $this->end_date;
    }

    public function getUsageLimit(): ?int
    {
        return $this->usage_limit;
    }

    public function getUsageCount(): int
    {
        return $this->usage_count;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function isPercentage(): bool
    {
        return $this->type === 'percentage';
    }

    public function isFixed(): bool
    {
        return $this->type === 'fixed';
    }

    public function hasUsageLimit(): bool
    {
        return $this->usage_limit !== null;
    }

    public function hasReachedUsageLimit(): bool
    {
        return $this->hasUsageLimit() && $this->usage_count >= $this->usage_limit;
    }

    public function hasExpired(): bool
    {
        if ($this->end_date === null) {
            return false;
        }
        return strtotime($this->end_date) < time();
    }

    public function hasStarted(): bool
    {
        if ($this->start_date === null) {
            return true;
        }
        return strtotime($this->start_date) <= time();
    }

    public function isValid(): bool
    {
        return $this->isActive() && $this->hasStarted() && !$this->hasExpired() && !$this->hasReachedUsageLimit();
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($orderTotal < $this->min_order_amount) {
            return 0;
        }

        $discount = $this->isPercentage() 
            ? $orderTotal * ($this->value / 100) 
            : $this->value;

        if ($this->max_discount_amount !== null && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return $discount;
    }
} 