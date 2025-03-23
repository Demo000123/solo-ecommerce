<?php
declare(strict_types=1);

namespace App\Models;

class Setting
{
    private int $id;
    private string $setting_key;
    private ?string $setting_value;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        string $setting_key,
        ?string $setting_value = null,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->setting_key = $setting_key;
        $this->setting_value = $setting_value;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->setting_key;
    }

    public function getValue(): ?string
    {
        return $this->setting_value;
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