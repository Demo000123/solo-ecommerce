<?php
declare(strict_types=1);

namespace App\Models;

class Order
{
    private int $id;
    private string $order_number;
    private ?int $user_id;
    private string $status;
    private float $total_amount;
    private float $tax_amount;
    private float $shipping_amount;
    private float $discount_amount;
    private string $payment_method;
    private string $payment_status;
    private ?string $notes;
    private string $shipping_fullname;
    private string $shipping_phone;
    private string $shipping_address;
    private string $shipping_province;
    private string $shipping_district;
    private string $shipping_ward;
    private string $shipping_method;
    private string $created_at;
    private string $updated_at;

    public function __construct(
        int $id,
        string $order_number,
        ?int $user_id,
        string $status = 'pending',
        float $total_amount = 0.0,
        float $tax_amount = 0.0,
        float $shipping_amount = 0.0,
        float $discount_amount = 0.0,
        string $payment_method = 'cod',
        string $payment_status = 'pending',
        ?string $notes = null,
        string $shipping_fullname = '',
        string $shipping_phone = '',
        string $shipping_address = '',
        string $shipping_province = '',
        string $shipping_district = '',
        string $shipping_ward = '',
        string $shipping_method = 'standard',
        string $created_at = '',
        string $updated_at = ''
    ) {
        $this->id = $id;
        $this->order_number = $order_number;
        $this->user_id = $user_id;
        $this->status = $status;
        $this->total_amount = $total_amount;
        $this->tax_amount = $tax_amount;
        $this->shipping_amount = $shipping_amount;
        $this->discount_amount = $discount_amount;
        $this->payment_method = $payment_method;
        $this->payment_status = $payment_status;
        $this->notes = $notes;
        $this->shipping_fullname = $shipping_fullname;
        $this->shipping_phone = $shipping_phone;
        $this->shipping_address = $shipping_address;
        $this->shipping_province = $shipping_province;
        $this->shipping_district = $shipping_district;
        $this->shipping_ward = $shipping_ward;
        $this->shipping_method = $shipping_method;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderNumber(): string
    {
        return $this->order_number;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getTotalAmount(): float
    {
        return $this->total_amount;
    }

    public function getFormattedTotalAmount(): string
    {
        return number_format($this->total_amount, 2);
    }

    public function getTaxAmount(): float
    {
        return $this->tax_amount;
    }

    public function getShippingAmount(): float
    {
        return $this->shipping_amount;
    }

    public function getDiscountAmount(): float
    {
        return $this->discount_amount;
    }

    public function getPaymentMethod(): string
    {
        return $this->payment_method;
    }

    public function getPaymentStatus(): string
    {
        return $this->payment_status;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function getShippingFullname(): string
    {
        return $this->shipping_fullname;
    }

    public function getShippingPhone(): string
    {
        return $this->shipping_phone;
    }

    public function getShippingAddress(): string
    {
        return $this->shipping_address;
    }

    public function getShippingProvince(): string
    {
        return $this->shipping_province;
    }

    public function getShippingDistrict(): string
    {
        return $this->shipping_district;
    }

    public function getShippingWard(): string
    {
        return $this->shipping_ward;
    }

    public function getShippingMethod(): string
    {
        return $this->shipping_method;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
} 