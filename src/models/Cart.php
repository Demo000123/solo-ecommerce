<?php
declare(strict_types=1);

namespace App\Models;

class Cart
{
    private int $id;
    private ?int $user_id;
    private string $session_id;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        ?int $user_id,
        string $session_id,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->session_id = $session_id;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getSessionId(): string
    {
        return $this->session_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function isUserCart(): bool
    {
        return $this->user_id !== null;
    }
} 