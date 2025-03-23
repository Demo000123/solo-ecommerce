<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/public/css/cart.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Giỏ hàng của bạn</h1>
        <div class="breadcrumbs">
            <a href="/solo-ecommerce/src/views/home/index.php">Trang chủ</a>
            <span class="separator">/</span>
            <span class="current">Giỏ hàng</span>
        </div>
    </div>
</div>

<div class="cart-container container">
    <?php if (empty($cartItems)): ?>
        <div class="empty-cart">
            <div class="empty-cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h2>Giỏ hàng của bạn đang trống</h2>
            <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
            <a href="/solo-ecommerce/src/views/products/index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="cart-content">
            <div class="cart-items">
                <div class="cart-header">
                    <div class="cart-header-item product-info">Sản phẩm</div>
                    <div class="cart-header-item product-price">Đơn giá</div>
                    <div class="cart-header-item product-quantity">Số lượng</div>
                    <div class="cart-header-item product-subtotal">Thành tiền</div>
                    <div class="cart-header-item product-remove"></div>
                </div>
                
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <div class="product-info">
                            <div class="product-image">
                                <a href="/products/<?= $item['product_id'] ?>">
                                    <img src="<?= htmlspecialchars($item['image'] ?? '/public/images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                </a>
                            </div>
                            <div class="product-details">
                                <h3 class="product-name">
                                    <a href="/products/<?= $item['product_id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                                </h3>
                                <div class="product-meta">
                                    <?php if (!empty($item['category_name'])): ?>
                                        <span class="product-category"><?= htmlspecialchars($item['category_name']) ?></span>
                                    <?php endif; ?>
                                    
                                    <?php if ($item['stock'] <= 5 && $item['stock'] > 0): ?>
                                        <span class="stock-warning">Chỉ còn <?= $item['stock'] ?> sản phẩm</span>
                                    <?php elseif ($item['stock'] <= 0): ?>
                                        <span class="out-of-stock">Hết hàng</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="product-price">
                            <span class="current-price"><?= number_format($item['price'], 0, ',', '.') ?> ₫</span>
                            <?php if (isset($item['original_price']) && $item['original_price'] > $item['price']): ?>
                                <span class="original-price"><?= number_format($item['original_price'], 0, ',', '.') ?> ₫</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-quantity">
                            <form action="/cart/update" method="POST" class="quantity-form">
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <div class="quantity-control">
                                    <button type="button" class="quantity-btn decrease" <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" class="quantity-input" data-product-id="<?= $item['product_id'] ?>">
                                    <button type="button" class="quantity-btn increase" <?= $item['quantity'] >= $item['stock'] ? 'disabled' : '' ?>>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="product-subtotal">
                            <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> ₫
                        </div>
                        
                        <div class="product-remove">
                            <form action="/cart/remove" method="POST">
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <button type="submit" class="remove-btn" title="Xóa sản phẩm">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="cart-actions">
                    <div class="coupon-section">
                        <form action="/cart/apply-coupon" method="POST" class="coupon-form">
                            <input type="text" name="coupon_code" placeholder="Mã giảm giá" class="coupon-input">
                            <button type="submit" class="btn btn-outline">Áp dụng</button>
                        </form>
                    </div>
                    
                    <div class="cart-buttons">
                        <a href="/products" class="btn btn-outline">Tiếp tục mua sắm</a>
                        <form action="/cart/clear" method="POST">
                            <button type="submit" class="btn btn-outline btn-danger">Xóa giỏ hàng</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="cart-summary">
                <h3 class="summary-title">Tóm tắt đơn hàng</h3>
                
                <div class="summary-item">
                    <span class="summary-label">Tạm tính</span>
                    <span class="summary-value"><?= number_format($cartTotal, 0, ',', '.') ?> ₫</span>
                </div>
                
                <?php if (!empty($discount)): ?>
                    <div class="summary-item discount">
                        <span class="summary-label">Giảm giá</span>
                        <span class="summary-value">-<?= number_format($discount, 0, ',', '.') ?> ₫</span>
                    </div>
                <?php endif; ?>
                
                <div class="summary-item shipping">
                    <span class="summary-label">Phí vận chuyển</span>
                    <span class="summary-value"><?= $shippingFee > 0 ? number_format($shippingFee, 0, ',', '.') . ' ₫' : 'Miễn phí' ?></span>
                </div>
                
                <div class="summary-item total">
                    <span class="summary-label">Tổng cộng</span>
                    <span class="summary-value"><?= number_format($cartTotal - ($discount ?? 0) + $shippingFee, 0, ',', '.') ?> ₫</span>
                </div>
                
                <div class="checkout-button">
                    <a href="/checkout" class="btn btn-primary btn-block">Tiến hành thanh toán</a>
                </div>
                
                <div class="secure-checkout">
                    <div class="secure-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <p>Thanh toán an toàn và bảo mật</p>
                </div>
                
                <div class="payment-methods">
                    <p>Chấp nhận thanh toán qua:</p>
                    <div class="payment-icons">
                        <img src="/public/images/payment/visa.png" alt="Visa">
                        <img src="/public/images/payment/mastercard.png" alt="MasterCard">
                        <img src="/public/images/payment/paypal.png" alt="PayPal">
                        <img src="/public/images/payment/momo.png" alt="MoMo">
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity control functionality
        const decreaseButtons = document.querySelectorAll('.quantity-btn.decrease');
        const increaseButtons = document.querySelectorAll('.quantity-btn.increase');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        // Handle decrease button clicks
        decreaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                let value = parseInt(input.value);
                
                if (value > 1) {
                    value--;
                    input.value = value;
                    updateCartItem(input);
                    
                    // Enable/disable buttons based on new value
                    updateQuantityButtonStates(input);
                }
            });
        });
        
        // Handle increase button clicks
        increaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('.quantity-input');
                let value = parseInt(input.value);
                let max = parseInt(input.getAttribute('max'));
                
                if (value < max) {
                    value++;
                    input.value = value;
                    updateCartItem(input);
                    
                    // Enable/disable buttons based on new value
                    updateQuantityButtonStates(input);
                }
            });
        });
        
        // Handle direct input changes
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                let value = parseInt(this.value);
                let min = parseInt(this.getAttribute('min'));
                let max = parseInt(this.getAttribute('max'));
                
                // Ensure value is within valid range
                if (isNaN(value) || value < min) {
                    this.value = min;
                } else if (value > max) {
                    this.value = max;
                }
                
                updateCartItem(this);
                
                // Enable/disable buttons based on new value
                updateQuantityButtonStates(this);
            });
        });
        
        // Helper function to update quantity button states
        function updateQuantityButtonStates(input) {
            const decreaseBtn = input.parentElement.querySelector('.decrease');
            const increaseBtn = input.parentElement.querySelector('.increase');
            const value = parseInt(input.value);
            const min = parseInt(input.getAttribute('min'));
            const max = parseInt(input.getAttribute('max'));
            
            decreaseBtn.disabled = value <= min;
            increaseBtn.disabled = value >= max;
        }
        
        // Helper function to send AJAX request to update cart item
        function updateCartItem(input) {
            const productId = input.dataset.productId;
            const quantity = input.value;
            
            // Create form data
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            
            // Send AJAX request
            fetch('/cart/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update subtotal for this item
                    const cartItem = input.closest('.cart-item');
                    const subtotalElement = cartItem.querySelector('.product-subtotal');
                    subtotalElement.textContent = data.item_subtotal;
                    
                    // Update cart totals
                    const summaryItems = document.querySelectorAll('.summary-value');
                    summaryItems[0].textContent = data.cart_subtotal;  // Subtotal
                    summaryItems[summaryItems.length - 2].textContent = data.shipping_fee;  // Shipping
                    summaryItems[summaryItems.length - 1].textContent = data.cart_total;  // Total
                    
                    // Update cart count in header
                    const cartCountEl = document.querySelector('.cart-count');
                    if (cartCountEl) {
                        cartCountEl.textContent = data.cart_count;
                    }
                } else {
                    // Show error message
                    alert('Có lỗi xảy ra khi cập nhật giỏ hàng. Vui lòng thử lại.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật giỏ hàng. Vui lòng thử lại.');
            });
        }
    });
</script>

<style>
    .cart-container {
        margin-top: 2rem;
        margin-bottom: 4rem;
    }
    
    .empty-cart {
        text-align: center;
        padding: 3rem;
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
    }
    
    .empty-cart-icon {
        font-size: 5rem;
        color: var(--light-text);
        margin-bottom: 1.5rem;
    }
    
    .empty-cart h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: var(--heading-color);
    }
    
    .empty-cart p {
        color: var(--light-text);
        margin-bottom: 1.5rem;
    }
    
    .cart-content {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }
    
    .cart-items {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        overflow: hidden;
    }
    
    .cart-header {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr 1fr 60px;
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        font-weight: 500;
        background-color: #f9fafb;
    }
    
    .cart-header-item {
        padding: 0 0.5rem;
    }
    
    .cart-item {
        display: grid;
        grid-template-columns: 3fr 1fr 1fr 1fr 60px;
        padding: 1.5rem 1rem;
        border-bottom: 1px solid var(--border-color);
        align-items: center;
    }
    
    .product-info {
        display: flex;
        gap: 1rem;
    }
    
    .product-image {
        width: 80px;
        height: 80px;
        border-radius: var(--border-radius);
        overflow: hidden;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-details {
        flex: 1;
    }
    
    .product-name {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .product-name a {
        color: var(--heading-color);
        text-decoration: none;
        transition: var(--transition-normal);
    }
    
    .product-name a:hover {
        color: var(--primary-color);
    }
    
    .product-meta {
        font-size: 0.85rem;
        color: var(--light-text);
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .stock-warning {
        color: #f59e0b;
    }
    
    .out-of-stock {
        color: #ef4444;
    }
    
    .product-price {
        display: flex;
        flex-direction: column;
    }
    
    .current-price {
        font-weight: 500;
        color: var(--primary-color);
    }
    
    .original-price {
        font-size: 0.85rem;
        text-decoration: line-through;
        color: var(--light-text);
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        overflow: hidden;
        width: 120px;
    }
    
    .quantity-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        cursor: pointer;
        transition: var(--transition-normal);
    }
    
    .quantity-btn:disabled {
        color: var(--border-color);
        cursor: not-allowed;
    }
    
    .quantity-btn:not(:disabled):hover {
        background-color: #f9fafb;
    }
    
    .quantity-input {
        width: 50px;
        height: 36px;
        border: none;
        border-left: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        text-align: center;
        font-size: 0.9rem;
    }
    
    .quantity-input:focus {
        outline: none;
    }
    
    .product-subtotal {
        font-weight: 500;
    }
    
    .remove-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        color: var(--light-text);
        cursor: pointer;
        transition: var(--transition-normal);
    }
    
    .remove-btn:hover {
        color: #ef4444;
    }
    
    .cart-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .coupon-form {
        display: flex;
        gap: 0.5rem;
    }
    
    .coupon-input {
        padding: 0.6rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
    }
    
    .coupon-input:focus {
        outline: none;
        border-color: var(--primary-color);
    }
    
    .cart-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn {
        display: inline-block;
        padding: 0.6rem 1.25rem;
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition-normal);
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: var(--white);
        border: none;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
    }
    
    .btn-outline {
        background-color: transparent;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }
    
    .btn-outline:hover {
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    .btn-danger {
        color: #ef4444;
        border-color: #ef4444;
    }
    
    .btn-danger:hover {
        background-color: #ef4444;
        color: var(--white);
    }
    
    .btn-block {
        display: block;
        width: 100%;
        text-align: center;
    }
    
    .cart-summary {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        padding: 1.5rem;
    }
    
    .summary-title {
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--heading-color);
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    
    .summary-label {
        color: var(--body-color);
    }
    
    .summary-value {
        font-weight: 500;
    }
    
    .discount .summary-value {
        color: #10b981;
    }
    
    .total {
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }
    
    .checkout-button {
        margin-bottom: 1.5rem;
    }
    
    .secure-checkout {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background-color: #f8fafc;
        border-radius: var(--border-radius);
    }
    
    .secure-icon {
        color: #10b981;
    }
    
    .secure-checkout p {
        font-size: 0.9rem;
        color: var(--body-color);
        margin: 0;
    }
    
    .payment-methods {
        text-align: center;
    }
    
    .payment-methods p {
        font-size: 0.85rem;
        color: var(--light-text);
        margin-bottom: 0.75rem;
    }
    
    .payment-icons {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
    }
    
    .payment-icons img {
        height: 24px;
        opacity: 0.8;
    }
    
    @media (max-width: 992px) {
        .cart-content {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .cart-header {
            display: none;
        }
        
        .cart-item {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .product-price, .product-quantity, .product-subtotal {
            display: grid;
            grid-template-columns: 100px 1fr;
            align-items: center;
        }
        
        .product-price::before {
            content: 'Đơn giá:';
            color: var(--light-text);
            font-size: 0.9rem;
        }
        
        .product-quantity::before {
            content: 'Số lượng:';
            color: var(--light-text);
            font-size: 0.9rem;
        }
        
        .product-subtotal::before {
            content: 'Thành tiền:';
            color: var(--light-text);
            font-size: 0.9rem;
        }
        
        .product-remove {
            justify-self: end;
        }
        
        .cart-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .cart-buttons {
            flex-direction: column;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 