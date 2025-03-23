<?php
declare(strict_types=1);

namespace App\Models;

class ProductImage
{
    private int $id;
    private int $product_id;
    private string $image_path;
    private int $is_primary;
    private int $sort_order;
    private string $created_at;

    public function __construct(
        int $id,
        int $product_id,
        string $image_path,
        int $is_primary = 0,
        int $sort_order = 0,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->image_path = $image_path;
        $this->is_primary = $is_primary;
        $this->sort_order = $sort_order;
        $this->created_at = $created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getImagePath(): string
    {
        return $this->image_path;
    }

    public function isPrimary(): bool
    {
        return $this->is_primary === 1;
    }

    public function getSortOrder(): int
    {
        return $this->sort_order;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
} 