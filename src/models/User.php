<?php
declare(strict_types=1);

namespace App\Models;

class User
{
    private int $id;
    private string $fullname;
    private string $email;
    private string $password;
    private ?string $phone;
    private ?string $address;
    private ?string $province;
    private ?string $district;
    private ?string $ward;
    private string $role;
    private string $avatar;
    private string $created_at;
    private string $updated_at;
    private ?string $last_login;

    public function __construct(
        int $id,
        string $fullname,
        string $email,
        string $password,
        ?string $phone = null,
        ?string $address = null,
        ?string $province = null,
        ?string $district = null,
        ?string $ward = null,
        string $role = 'customer',
        string $avatar = '/public/images/avatar-default.png',
        string $created_at = '',
        string $updated_at = '',
        ?string $last_login = null
    ) {
        $this->id = $id;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->address = $address;
        $this->province = $province;
        $this->district = $district;
        $this->ward = $ward;
        $this->role = $role;
        $this->avatar = $avatar;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->last_login = $last_login;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullname(): string
    {
        return $this->fullname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function getWard(): ?string
    {
        return $this->ward;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function getLastLogin(): ?string
    {
        return $this->last_login;
    }
} 