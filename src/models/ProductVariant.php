<?php
declare(strict_types=1);

namespace App\Models;

class ProductVariant
{
    private int $id;
    private int $product_id;
    private ?string $sku;
    private float $price_adjustment;
    private int $stock;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        int $product_id,
        ?string $sku = null,
        float $price_adjustment = 0.0,
        int $stock = 0,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->sku = $sku;
        $this->price_adjustment = $price_adjustment;
        $this->stock = $stock;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getPriceAdjustment(): float
    {
        return $this->price_adjustment;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
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