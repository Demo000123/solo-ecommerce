<?php
declare(strict_types=1);

namespace App\Models;

class Review
{
    private int $id;
    private int $product_id;
    private ?int $user_id;
    private int $rating;
    private ?string $title;
    private ?string $comment;
    private string $status;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        int $product_id,
        ?int $user_id,
        int $rating,
        ?string $title = null,
        ?string $comment = null,
        string $status = 'pending',
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->product_id = $product_id;
        $this->user_id = $user_id;
        $this->rating = $rating;
        $this->title = $title;
        $this->comment = $comment;
        $this->status = $status;
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

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
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