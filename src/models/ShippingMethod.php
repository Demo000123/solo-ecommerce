<?php
declare(strict_types=1);

namespace App\Models;

class ShippingMethod
{
    private int $id;
    private string $name;
    private ?string $description;
    private float $price;
    private ?string $estimated_days;
    private int $status;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        string $name,
        ?string $description = null,
        float $price = 0.0,
        ?string $estimated_days = null,
        int $status = 1,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->estimated_days = $estimated_days;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 2);
    }

    public function getEstimatedDays(): ?string
    {
        return $this->estimated_days;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status === 1;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }
} 