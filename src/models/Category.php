<?php
declare(strict_types=1);

namespace App\Models;

class Category
{
    private int $id;
    private string $name;
    private ?string $description;
    private ?string $slug;
    private ?int $parent_id;
    private ?string $image;
    private int $status;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        string $name,
        ?string $description = null,
        ?string $slug = null,
        ?int $parent_id = null,
        ?string $image = null,
        int $status = 1,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->slug = $slug;
        $this->parent_id = $parent_id;
        $this->image = $image;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function getImage(): ?string
    {
        return $this->image;
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