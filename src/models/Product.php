<?php
declare(strict_types=1);

namespace App\Models;

class Product
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private string $image;
    private int $stock;
    private string $category;

    public function __construct(
        int $id,
        string $name,
        string $description,
        float $price,
        string $image,
        int $stock,
        string $category
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
        $this->stock = $stock;
        $this->category = $category;
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getFormattedPrice(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
} 