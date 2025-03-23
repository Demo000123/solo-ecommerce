<?php
declare(strict_types=1);

namespace App\Models;

class Wishlist
{
    private int $id;
    private int $user_id;
    private string $created_at;

    public function __construct(
        int $id,
        int $user_id,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->created_at = $created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
} 