<?php
declare(strict_types=1);

namespace App\Models;

class CartItem
{
    private int $id;
    private int $cart_id;
    private int $product_id;
    private ?int $variant_id;
    private int $quantity;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        int $cart_id,
        int $product_id,
        ?int $variant_id = null,
        int $quantity = 1,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->cart_id = $cart_id;
        $this->product_id = $product_id;
        $this->variant_id = $variant_id;
        $this->quantity = $quantity;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCartId(): int
    {
        return $this->cart_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getVariantId(): ?int
    {
        return $this->variant_id;
    }

    public function hasVariant(): bool
    {
        return $this->variant_id !== null;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
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