<?php
declare(strict_types=1);

namespace App\Models;

class WishlistItem
{
    private int $id;
    private int $wishlist_id;
    private int $product_id;
    private string $added_at;

    public function __construct(
        int $id,
        int $wishlist_id,
        int $product_id,
        string $added_at = ''
    ) {
        $this->id = $id;
        $this->wishlist_id = $wishlist_id;
        $this->product_id = $product_id;
        $this->added_at = $added_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWishlistId(): int
    {
        return $this->wishlist_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAddedAt(): string
    {
        return $this->added_at;
    }
} 