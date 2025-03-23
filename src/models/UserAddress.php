<?php
declare(strict_types=1);

namespace App\Models;

class UserAddress
{
    private int $id;
    private int $user_id;
    private ?string $address_name;
    private string $fullname;
    private string $phone;
    private string $address;
    private string $province;
    private string $district;
    private string $ward;
    private int $is_default;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        int $user_id,
        string $fullname,
        string $phone,
        string $address,
        string $province,
        string $district,
        string $ward,
        ?string $address_name = null,
        int $is_default = 0,
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->address_name = $address_name;
        $this->fullname = $fullname;
        $this->phone = $phone;
        $this->address = $address;
        $this->province = $province;
        $this->district = $district;
        $this->ward = $ward;
        $this->is_default = $is_default;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getAddressName(): ?string
    {
        return $this->address_name;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getProvince(): string
    {
        return $this->province;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function getWard(): string
    {
        return $this->ward;
    }

    public function isDefault(): bool
    {
        return $this->is_default === 1;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function getFullAddress(): string
    {
        return "$this->address, $this->ward, $this->district, $this->province";
    }
} 