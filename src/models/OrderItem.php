<?php
declare(strict_types=1);

namespace App\Models;

class OrderItem
{
    private int $id;
    private int $order_id;
    private int $product_id;
    private ?int $variant_id;
    private string $name;
    private int $quantity;
    private float $price;
    private float $subtotal;
    private string $created_at;

    public function __construct(
        int $id,
        int $order_id,
        int $product_id,
        ?int $variant_id,
        string $name,
        int $quantity,
        float $price,
        float $subtotal,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->order_id = $order_id;
        $this->product_id = $product_id;
        $this->variant_id = $variant_id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->subtotal = $subtotal;
        $this->created_at = $created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->order_id;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 2);
    }

    public function getSubtotal(): float
    {
        return $this->subtotal;
    }

    public function getFormattedSubtotal(): string
    {
        return number_format($this->subtotal, 2);
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
} 