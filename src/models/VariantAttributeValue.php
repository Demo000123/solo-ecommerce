<?php
declare(strict_types=1);

namespace App\Models;

class VariantAttributeValue
{
    private int $id;
    private int $variant_id;
    private int $attribute_value_id;
    private string $created_at;

    public function __construct(
        int $id,
        int $variant_id,
        int $attribute_value_id,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->variant_id = $variant_id;
        $this->attribute_value_id = $attribute_value_id;
        $this->created_at = $created_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVariantId(): int
    {
        return $this->variant_id;
    }

    public function getAttributeValueId(): int
    {
        return $this->attribute_value_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
} 