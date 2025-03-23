<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/public/css/checkout.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Thanh toán</h1>
        <div class="breadcrumbs">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <a href="/cart">Giỏ hàng</a>
            <span class="separator">/</span>
            <span class="current">Thanh toán</span>
        </div>
    </div>
</div>

<div class="checkout-container container">
    <?php if (empty($cartItems)): ?>
        <div class="empty-checkout">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Giỏ hàng của bạn đang trống</h2>
            <p>Bạn cần thêm sản phẩm vào giỏ hàng trước khi thanh toán</p>
            <a href="/products" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <form action="/checkout/process" method="POST" id="checkout-form">
            <div class="checkout-content">
                <div class="checkout-details">
                    <!-- Customer Information -->
                    <div class="checkout-section">
                        <h2 class="section-title">Thông tin khách hàng</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fullname">Họ và tên <span class="required">*</span></label>
                                <input type="text" id="fullname" name="fullname" class="form-control" required
                                       value="<?= $user['fullname'] ?? '' ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" required
                                       value="<?= $user['email'] ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Số điện thoại <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control" required
                                       value="<?= $user['phone'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div class="checkout-section">
                        <h2 class="section-title">Địa chỉ giao hàng</h2>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="address">Địa chỉ <span class="required">*</span></label>
                                <input type="text" id="address" name="address" class="form-control" required
                                       value="<?= $user['address'] ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">Tỉnh/Thành phố <span class="required">*</span></label>
                                <select id="city" name="city" class="form-control" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    <?php
                                    $cities = [
                                        'Hà Nội', 'TP. Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
                                        'An Giang', 'Bà Rịa - Vũng Tàu', 'Bắc Giang', 'Bắc Kạn', 'Bạc Liêu',
                                        'Bắc Ninh', 'Bến Tre', 'Bình Định', 'Bình Dương', 'Bình Phước',
                                        'Bình Thuận', 'Cà Mau', 'Cao Bằng', 'Đắk Lắk', 'Đắk Nông',
                                        'Điện Biên', 'Đồng Nai', 'Đồng Tháp', 'Gia Lai', 'Hà Giang',
                                        'Hà Nam', 'Hà Tĩnh', 'Hải Dương', 'Hậu Giang', 'Hòa Bình',
                                        'Hưng Yên', 'Khánh Hòa', 'Kiên Giang', 'Kon Tum', 'Lai Châu',
                                        'Lâm Đồng', 'Lạng Sơn', 'Lào Cai', 'Long An', 'Nam Định',
                                        'Nghệ An', 'Ninh Bình', 'Ninh Thuận', 'Phú Thọ', 'Phú Yên',
                                        'Quảng Bình', 'Quảng Nam', 'Quảng Ngãi', 'Quảng Ninh', 'Quảng Trị',
                                        'Sóc Trăng', 'Sơn La', 'Tây Ninh', 'Thái Bình', 'Thái Nguyên',
                                        'Thanh Hóa', 'Thừa Thiên Huế', 'Tiền Giang', 'Trà Vinh', 'Tuyên Quang',
                                        'Vĩnh Long', 'Vĩnh Phúc', 'Yên Bái'
                                    ];
                                    
                                    foreach ($cities as $city) {
                                        $selected = (isset($user['city']) && $user['city'] == $city) ? 'selected' : '';
                                        echo "<option value=\"$city\" $selected>$city</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="district">Quận/Huyện <span class="required">*</span></label>
                                <input type="text" id="district" name="district" class="form-control" required
                                       value="<?= $user['district'] ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ward">Phường/Xã <span class="required">*</span></label>
                                <input type="text" id="ward" name="ward" class="form-control" required
                                       value="<?= $user['ward'] ?? '' ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="postal_code">Mã bưu điện</label>
                                <input type="text" id="postal_code" name="postal_code" class="form-control"
                                       value="<?= $user['postal_code'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Method -->
                    <div class="checkout-section">
                        <h2 class="section-title">Phương thức vận chuyển</h2>
                        
                        <div class="shipping-methods">
                            <label class="radio-container">
                                <input type="radio" name="shipping_method" value="standard" checked>
                                <div class="radio-content">
                                    <div class="radio-custom"></div>
                                    <div class="radio-label">
                                        <span class="method-name">Giao hàng tiêu chuẩn (2-3 ngày)</span>
                                        <span class="method-price"><?= $shippingFee > 0 ? number_format($shippingFee, 0, ',', '.') . ' ₫' : 'Miễn phí' ?></span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="radio-container">
                                <input type="radio" name="shipping_method" value="express">
                                <div class="radio-content">
                                    <div class="radio-custom"></div>
                                    <div class="radio-label">
                                        <span class="method-name">Giao hàng nhanh (1-2 ngày)</span>
                                        <span class="method-price"><?= number_format($shippingFee + 30000, 0, ',', '.') ?> ₫</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="checkout-section">
                        <h2 class="section-title">Phương thức thanh toán</h2>
                        
                        <div class="payment-methods">
                            <label class="radio-container">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <div class="radio-content">
                                    <div class="radio-custom"></div>
                                    <div class="radio-label">
                                        <span class="method-name">Thanh toán khi nhận hàng (COD)</span>
                                        <span class="method-desc">Thanh toán bằng tiền mặt khi nhận hàng</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="radio-container">
                                <input type="radio" name="payment_method" value="bank_transfer">
                                <div class="radio-content">
                                    <div class="radio-custom"></div>
                                    <div class="radio-label">
                                        <span class="method-name">Chuyển khoản ngân hàng</span>
                                        <span class="method-desc">Chuyển khoản đến tài khoản của chúng tôi</span>
                                    </div>
                                </div>
                            </label>
                            
                            <div class="bank-details" style="display: none;">
                                <div class="bank-info">
                                    <p><strong>Tên tài khoản:</strong> CÔNG TY TNHH SOLO E-COMMERCE</p>
                                    <p><strong>Số tài khoản:</strong> 0123456789</p>
                                    <p><strong>Ngân hàng:</strong> Vietcombank - Chi nhánh Hà Nội</p>
                                    <p><strong>Nội dung:</strong> [Mã đơn hàng] - [Họ tên]</p>
                                </div>
                                <p class="bank-note">Lưu ý: Đơn hàng của bạn sẽ được xử lý sau khi chúng tôi nhận được tiền.</p>
                            </div>
                            
                            <label class="radio-container">
                                <input type="radio" name="payment_method" value="momo">
                                <div class="radio-content">
                                    <div class="radio-custom"></div>
                                    <div class="radio-label">
                                        <span class="method-name">Thanh toán qua MoMo</span>
                                        <span class="method-desc">Thanh toán qua ứng dụng MoMo</span>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="radio-container">
                                <input type="radio" name="payment_method" value="vnpay">
                                <div class="radio-content">
                                    <div class="radio-custom"></div>
                                    <div class="radio-label">
                                        <span class="method-name">Thanh toán qua VNPay</span>
                                        <span class="method-desc">Thanh toán qua cổng thanh toán VNPay</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Order Notes -->
                    <div class="checkout-section">
                        <h2 class="section-title">Ghi chú đơn hàng</h2>
                        
                        <div class="form-group">
                            <textarea id="order_notes" name="order_notes" class="form-control" rows="4" 
                                      placeholder="Nhập ghi chú đặc biệt cho đơn hàng của bạn hoặc hướng dẫn giao hàng"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="checkout-sidebar">
                    <div class="order-summary">
                        <h2 class="section-title">Đơn hàng của bạn</h2>
                        
                        <div class="order-products">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="order-product">
                                    <div class="order-product-info">
                                        <div class="order-product-image">
                                            <img src="<?= htmlspecialchars($item['image'] ?? '/public/images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                        </div>
                                        <div class="order-product-details">
                                            <h3 class="order-product-name"><?= htmlspecialchars($item['name']) ?></h3>
                                            <div class="order-product-meta">
                                                <span class="order-product-quantity">SL: <?= $item['quantity'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-product-price">
                                        <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> ₫
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-totals">
                            <div class="order-subtotal order-total-row">
                                <span class="order-total-label">Tạm tính</span>
                                <span class="order-total-value"><?= number_format($cartTotal, 0, ',', '.') ?> ₫</span>
                            </div>
                            
                            <?php if (!empty($discount)): ?>
                                <div class="order-discount order-total-row">
                                    <span class="order-total-label">Giảm giá</span>
                                    <span class="order-total-value discount-value">-<?= number_format($discount, 0, ',', '.') ?> ₫</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="order-shipping order-total-row">
                                <span class="order-total-label">Phí vận chuyển</span>
                                <span class="order-total-value shipping-value">
                                    <?= $shippingFee > 0 ? number_format($shippingFee, 0, ',', '.') . ' ₫' : 'Miễn phí' ?>
                                </span>
                            </div>
                            
                            <div class="order-total order-total-row">
                                <span class="order-total-label">Tổng cộng</span>
                                <span class="order-total-value total-value">
                                    <?= number_format($cartTotal - ($discount ?? 0) + $shippingFee, 0, ',', '.') ?> ₫
                                </span>
                            </div>
                        </div>
                        
                        <div class="checkout-agreement">
                            <label class="checkbox-container">
                                <input type="checkbox" name="terms_agreed" required>
                                <div class="checkbox-custom"></div>
                                <div class="checkbox-label">
                                    Tôi đã đọc và đồng ý với <a href="/terms" target="_blank">điều khoản và điều kiện</a> của website
                                </div>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-place-order">
                            Đặt hàng
                        </button>
                        
                        <div class="secure-checkout">
                            <div class="secure-checkout-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <p>Thanh toán an toàn và bảo mật</p>
                        </div>
                        
                        <div class="payment-icons">
                            <img src="/public/images/payment/visa.png" alt="Visa">
                            <img src="/public/images/payment/mastercard.png" alt="MasterCard">
                            <img src="/public/images/payment/paypal.png" alt="PayPal">
                            <img src="/public/images/payment/momo.png" alt="MoMo">
                            <img src="/public/images/payment/vnpay.png" alt="VNPay">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide bank transfer details
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const bankDetails = document.querySelector('.bank-details');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                if (this.value === 'bank_transfer') {
                    bankDetails.style.display = 'block';
                } else {
                    bankDetails.style.display = 'none';
                }
            });
        });
        
        // Update shipping cost based on selected method
        const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
        const shippingValueEl = document.querySelector('.shipping-value');
        const totalValueEl = document.querySelector('.total-value');
        const standardShippingFee = <?= $shippingFee ?>;
        const expressShippingFee = standardShippingFee + 30000;
        const subtotal = <?= $cartTotal ?>;
        const discount = <?= isset($discount) ? $discount : 0 ?>;
        
        shippingMethods.forEach(method => {
            method.addEventListener('change', function() {
                let shippingFee = this.value === 'express' ? expressShippingFee : standardShippingFee;
                let total = subtotal - discount + shippingFee;
                
                // Format numbers
                let formattedShipping = shippingFee > 0 ? 
                    new Intl.NumberFormat('vi-VN').format(shippingFee) + ' ₫' : 
                    'Miễn phí';
                let formattedTotal = new Intl.NumberFormat('vi-VN').format(total) + ' ₫';
                
                // Update display
                shippingValueEl.textContent = formattedShipping;
                totalValueEl.textContent = formattedTotal;
            });
        });
        
        // Form validation
        const checkoutForm = document.getElementById('checkout-form');
        
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = checkoutForm.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('error');
                    } else {
                        field.classList.remove('error');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Vui lòng điền đầy đủ thông tin trong các trường bắt buộc.');
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });
        }
    });
</script>

<style>
    .checkout-container {
        margin-top: 2rem;
        margin-bottom: 4rem;
    }
    
    .empty-checkout {
        text-align: center;
        padding: 3rem;
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
    }
    
    .empty-icon {
        font-size: 5rem;
        color: var(--light-text);
        margin-bottom: 1.5rem;
    }
    
    .empty-checkout h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: var(--heading-color);
    }
    
    .empty-checkout p {
        color: var(--light-text);
        margin-bottom: 1.5rem;
    }
    
    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }
    
    .checkout-section {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--heading-color);
        font-weight: 600;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        font-size: 0.95rem;
        font-family: inherit;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
    }
    
    .form-control.error {
        border-color: #ef4444;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--body-color);
        font-weight: 500;
    }
    
    .required {
        color: #ef4444;
    }
    
    .shipping-methods,
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .radio-container,
    .checkbox-container {
        display: flex;
        align-items: flex-start;
        cursor: pointer;
    }
    
    .radio-content,
    .checkbox-content {
        display: flex;
        align-items: flex-start;
    }
    
    .radio-container input[type="radio"],
    .checkbox-container input[type="checkbox"] {
        display: none;
    }
    
    .radio-custom {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        margin-right: 10px;
        margin-top: 2px;
        position: relative;
        flex-shrink: 0;
    }
    
    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-color);
        border-radius: 4px;
        margin-right: 10px;
        margin-top: 2px;
        position: relative;
        flex-shrink: 0;
    }
    
    .radio-container input[type="radio"]:checked + .radio-content .radio-custom::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: var(--primary-color);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .checkbox-container input[type="checkbox"]:checked + .checkbox-custom::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--primary-color);
        font-size: 12px;
    }
    
    .radio-label {
        flex: 1;
    }
    
    .method-name {
        display: block;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .method-desc,
    .method-price {
        display: block;
        font-size: 0.85rem;
        color: var(--light-text);
    }
    
    .bank-details {
        margin-top: 1rem;
        margin-left: 2rem;
        padding: 1rem;
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        font-size: 0.9rem;
    }
    
    .bank-info p {
        margin-bottom: 0.5rem;
    }
    
    .bank-note {
        margin-top: 0.75rem;
        font-style: italic;
        color: var(--light-text);
    }
    
    /* Order summary styles */
    .order-summary {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        padding: 1.5rem;
        position: sticky;
        top: 2rem;
    }
    
    .order-products {
        margin-bottom: 1.5rem;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .order-product {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .order-product:last-child {
        border-bottom: none;
    }
    
    .order-product-info {
        display: flex;
        gap: 0.75rem;
        flex: 1;
    }
    
    .order-product-image {
        width: 60px;
        height: 60px;
        border-radius: var(--border-radius);
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .order-product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .order-product-name {
        font-size: 0.95rem;
        margin: 0 0 0.25rem;
        font-weight: 500;
    }
    
    .order-product-meta {
        font-size: 0.85rem;
        color: var(--light-text);
    }
    
    .order-product-price {
        font-weight: 500;
        color: var(--primary-color);
        margin-left: 1rem;
    }
    
    .order-totals {
        margin: 1.5rem 0;
    }
    
    .order-total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    
    .order-total {
        font-size: 1.1rem;
        font-weight: 600;
        padding-top: 0.75rem;
        margin-top: 0.75rem;
        border-top: 1px solid var(--border-color);
    }
    
    .discount-value {
        color: #10b981;
    }
    
    .checkout-agreement {
        margin: 1.5rem 0;
    }
    
    .checkbox-label {
        font-size: 0.9rem;
        line-height: 1.4;
    }
    
    .checkbox-label a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .checkbox-label a:hover {
        text-decoration: underline;
    }
    
    .btn-place-order {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .secure-checkout {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding: 0.75rem;
        background-color: #f8fafc;
        border-radius: var(--border-radius);
    }
    
    .secure-checkout-icon {
        color: #10b981;
    }
    
    .secure-checkout p {
        font-size: 0.85rem;
        margin: 0;
    }
    
    .payment-icons {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .payment-icons img {
        height: 24px;
        opacity: 0.7;
    }
    
    .btn {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        border-radius: var(--border-radius);
        font-size: 0.95rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition-normal);
        border: none;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
    }
    
    @media (max-width: 992px) {
        .checkout-content {
            grid-template-columns: 1fr;
        }
        
        .order-summary {
            position: static;
        }
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .btn-place-order {
            padding: 0.8rem;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 