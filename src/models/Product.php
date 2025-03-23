<?php
declare(strict_types=1);

namespace App\Models;

class Product
{
    private int $id;
    private string $name;
    private string $description;
    private ?string $short_description;
    private float $price;
    private ?float $sale_price;
    private string $image;
    private ?string $slug;
    private ?string $sku;
    private int $stock;
    private string $status;
    private int $is_featured;
    private int $category_id;
    private ?string $brand;
    private ?float $weight;
    private ?string $dimensions;
    private ?string $meta_title;
    private ?string $meta_description;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        string $name,
        string $description,
        ?string $short_description = null,
        float $price = 0.0,
        ?float $sale_price = null,
        string $image = '',
        ?string $slug = null,
        ?string $sku = null,
        int $stock = 0,
        string $status = 'active',
        int $is_featured = 0,
        int $category_id = 0,
        ?string $brand = null,
        ?float $weight = null,
        ?string $dimensions = null,
        ?string $meta_title = null,
        ?string $meta_description = null,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->short_description = $short_description;
        $this->price = $price;
        $this->sale_price = $sale_price;
        $this->image = $image;
        $this->slug = $slug;
        $this->sku = $sku;
        $this->stock = $stock;
        $this->status = $status;
        $this->is_featured = $is_featured;
        $this->category_id = $category_id;
        $this->brand = $brand;
        $this->weight = $weight;
        $this->dimensions = $dimensions;
        $this->meta_title = $meta_title;
        $this->meta_description = $meta_description;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSalePrice(): ?float
    {
        return $this->sale_price;
    }

    public function getCurrentPrice(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 2);
    }

    public function getFormattedSalePrice(): string
    {
        return $this->sale_price ? number_format($this->sale_price, 2) : '';
    }
    
    public function hasDiscount(): bool
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function getDiscountPercentage(): int
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        return (int)(($this->price - $this->sale_price) / $this->price * 100);
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isFeatured(): bool
    {
        return $this->is_featured === 1;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    public function getMetaTitle(): ?string
    {
        return $this->meta_title;
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
} 