<?php
declare(strict_types=1);

namespace App\Models;

class AttributeValue
{
    private int $id;
    private int $attribute_id;
    private string $value;
    private string $created_at;

    public function __construct(
        int $id,
        int $attribute_id,
        string $value,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->attribute_id = $attribute_id;
        $this->value = $value;
        $this->created_at = $created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAttributeId(): int
    {
        return $this->attribute_id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
} 