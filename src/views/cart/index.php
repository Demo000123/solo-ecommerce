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

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
        </ol>
    </nav>
    
    <h1 class="h2 mb-4">Shopping Cart</h1>
    
    <?php if (empty($cartItems)): ?>
    <!-- Empty Cart -->
    <div class="card border-0 shadow-sm p-4 text-center">
        <div class="empty-cart-container py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
            <h2 class="h4">Your cart is empty</h2>
            <p class="text-muted mb-4">Looks like you haven't added any products to your cart yet.</p>
            <a href="/products" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <!-- Cart Items -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Cart Items (<?= $totalItems ?>)</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item mb-4 pb-4 border-bottom">
                        <div class="row">
                            <!-- Product Image -->
                            <div class="col-md-2 col-4 mb-3 mb-md-0">
                                <a href="/product/<?= htmlspecialchars($item['product_slug']) ?>">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($item['name']) ?>" onerror="this.src='/assets/images/placeholders/product.jpg'">
                                </a>
                            </div>
                            
                            <!-- Product Details -->
                            <div class="col-md-5 col-8 mb-3 mb-md-0">
                                <h5 class="mb-1">
                                    <a href="/product/<?= htmlspecialchars($item['product_slug']) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($item['name']) ?></a>
                                </h5>
                                <p class="text-muted mb-1 small">SKU: <?= htmlspecialchars($item['sku']) ?></p>
                                
                                <?php if (!empty($item['options'])): ?>
                                <div class="product-options small text-muted mb-2">
                                    <?php foreach ($item['options'] as $optionName => $optionValue): ?>
                                    <div><?= htmlspecialchars($optionName) ?>: <?= htmlspecialchars($optionValue) ?></div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                
                                <div class="d-md-none d-flex align-items-center mt-2">
                                    <span class="me-2">Price:</span>
                                    <?php if ($item['discount_price']): ?>
                                    <span class="text-danger me-2">$<?= number_format($item['discount_price'], 2) ?></span>
                                    <s class="text-muted small">$<?= number_format($item['price'], 2) ?></s>
                                    <?php else: ?>
                                    <span>$<?= number_format($item['price'], 2) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Quantity -->
                            <div class="col-md-2 col-6 mb-3 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <div class="input-group input-group-sm" style="width: 110px;">
                                        <button type="button" class="btn btn-outline-secondary quantity-minus" data-item-id="<?= $item['id'] ?>"><i class="fas fa-minus"></i></button>
                                        <input type="number" class="form-control text-center quantity-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock_quantity'] ?>" data-cart-item="<?= $item['id'] ?>">
                                        <button type="button" class="btn btn-outline-secondary quantity-plus" data-item-id="<?= $item['id'] ?>"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                
                                <div class="d-block mt-2">
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 remove-item" data-item-id="<?= $item['id'] ?>">
                                        <i class="fas fa-trash-alt me-1"></i> Remove
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Price -->
                            <div class="col-md-3 col-6 text-end">
                                <div class="d-none d-md-block mb-2">
                                    <?php if ($item['discount_price']): ?>
                                    <div class="text-danger">$<?= number_format($item['discount_price'], 2) ?></div>
                                    <s class="text-muted small">$<?= number_format($item['price'], 2) ?></s>
                                    <?php else: ?>
                                    <div>$<?= number_format($item['price'], 2) ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="fw-bold">
                                    $<span id="item-total-<?= $item['id'] ?>"><?= number_format($item['total'], 2) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="d-flex justify-content-between">
                        <a href="/products" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <button type="button" class="btn btn-outline-danger" onclick="clearCart()">
                            <i class="fas fa-trash me-2"></i>Clear Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal (<?= $totalItems ?> items)</span>
                        <span>$<span id="cart-subtotal"><?= number_format($subtotal, 2) ?></span></span>
                    </div>
                    
                    <!-- Coupon Code Form -->
                    <div class="mb-3">
                        <div class="input-group mb-2">
                            <input type="text" id="couponCode" class="form-control" placeholder="Coupon code" value="<?= htmlspecialchars($couponCode ?? '') ?>">
                            <button class="btn btn-outline-secondary" type="button" id="applyCouponBtn" onclick="applyCoupon()">Apply</button>
                        </div>
                        <div id="couponMessage" class="form-text <?= isset($couponCode) ? 'text-success' : '' ?>">
                            <?= isset($couponCode) ? 'Coupon applied successfully!' : 'Enter a valid coupon code if you have one' ?>
                        </div>
                    </div>
                    
                    <?php if (isset($couponDiscount) && $couponDiscount > 0): ?>
                    <div class="d-flex justify-content-between mb-3 text-success">
                        <span>Discount</span>
                        <span>-$<?= number_format($couponDiscount, 2) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping</span>
                        <span id="shipping-fee">
                            <?= $shippingFee > 0 ? '$' . number_format($shippingFee, 2) : 'Calculated at checkout' ?>
                        </span>
                    </div>
                    
                    <hr class="my-3">
                    
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong class="text-primary">$<span id="cart-total"><?= number_format($total, 2) ?></span></strong>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="/checkout" class="btn btn-primary btn-lg">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods & Security -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="mb-3">We Accept</h6>
                    <div class="payment-methods d-flex flex-wrap mb-3">
                        <img src="/assets/images/payment/visa.png" alt="Visa" class="me-2 mb-2" onerror="this.src='/assets/images/placeholders/payment.jpg'" width="40">
                        <img src="/assets/images/payment/mastercard.png" alt="Mastercard" class="me-2 mb-2" onerror="this.src='/assets/images/placeholders/payment.jpg'" width="40">
                        <img src="/assets/images/payment/amex.png" alt="American Express" class="me-2 mb-2" onerror="this.src='/assets/images/placeholders/payment.jpg'" width="40">
                        <img src="/assets/images/payment/paypal.png" alt="PayPal" class="me-2 mb-2" onerror="this.src='/assets/images/placeholders/payment.jpg'" width="40">
                    </div>
                    
                    <div class="secure-checkout d-flex align-items-center">
                        <i class="fas fa-lock text-success me-2"></i>
                        <span class="small">Secure Checkout - 100% Protected</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteItemModalLabel">Confirm Removal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this item from your cart?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Remove Item</button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Cart Confirmation Modal -->
<div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearCartModalLabel">Confirm Clear Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove all items from your cart?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmClearCart">Clear Cart</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap modals
        const deleteItemModal = new bootstrap.Modal(document.getElementById('deleteItemModal'));
        const clearCartModal = new bootstrap.Modal(document.getElementById('clearCartModal'));
        
        // Quantity buttons
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        quantityInputs.forEach(input => {
            const minusBtn = input.parentElement.querySelector('.quantity-minus');
            const plusBtn = input.parentElement.querySelector('.quantity-plus');
            
            if (minusBtn) {
                minusBtn.addEventListener('click', function() {
                    let value = parseInt(input.value);
                    const itemId = this.getAttribute('data-item-id');
                    
                    if (value > 1) {
                        input.value = value - 1;
                        updateCartItem(itemId, input.value);
                    }
                });
            }
            
            if (plusBtn) {
                plusBtn.addEventListener('click', function() {
                    let value = parseInt(input.value);
                    const itemId = this.getAttribute('data-item-id');
                    let max = parseInt(input.getAttribute('max') || 100);
                    
                    if (value < max) {
                        input.value = value + 1;
                        updateCartItem(itemId, input.value);
                    }
                });
            }
            
            // Update when input changes (manual entry)
            input.addEventListener('change', function() {
                let value = parseInt(input.value);
                const itemId = input.getAttribute('data-cart-item');
                let max = parseInt(input.getAttribute('max') || 100);
                
                // Ensure value is at least 1
                if (value < 1) {
                    value = 1;
                    input.value = 1;
                }
                
                // Ensure value doesn't exceed max
                if (value > max) {
                    value = max;
                    input.value = max;
                }
                
                updateCartItem(itemId, value);
            });
        });
        
        // Remove item buttons
        const removeButtons = document.querySelectorAll('.remove-item');
        let itemIdToRemove = null;
        
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                itemIdToRemove = this.getAttribute('data-item-id');
                deleteItemModal.show();
            });
        });
        
        // Confirm delete button in modal
        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (itemIdToRemove) {
                removeCartItem(itemIdToRemove);
                deleteItemModal.hide();
            }
        });
        
        // Confirm clear cart button in modal
        document.getElementById('confirmClearCart').addEventListener('click', function() {
            fetch('/cart/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    showNotification(data.message || 'Failed to clear cart', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'danger');
            });
            
            clearCartModal.hide();
        });
    });
    
    function updateCartItem(itemId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `item_id=${itemId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update item total
                if (data.itemTotal) {
                    document.getElementById('item-total-' + itemId).textContent = data.itemTotal;
                }
                
                // Update cart subtotal
                if (data.subtotal) {
                    document.getElementById('cart-subtotal').textContent = data.subtotal;
                }
                
                // Update cart total
                if (data.total) {
                    document.getElementById('cart-total').textContent = data.total;
                }
            } else {
                showNotification(data.message || 'Failed to update cart', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function removeCartItem(itemId) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `item_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                showNotification(data.message || 'Failed to remove item', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function clearCart() {
        const clearCartModal = new bootstrap.Modal(document.getElementById('clearCartModal'));
        clearCartModal.show();
    }
    
    function applyCoupon() {
        const couponCode = document.getElementById('couponCode').value.trim();
        const couponMessage = document.getElementById('couponMessage');
        
        if (!couponCode) {
            couponMessage.className = 'form-text text-danger';
            couponMessage.textContent = 'Please enter a coupon code';
            return;
        }
        
        fetch('/cart/apply-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `coupon_code=${couponCode}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                couponMessage.className = 'form-text text-success';
                couponMessage.textContent = data.message || 'Coupon applied successfully!';
                
                // Update cart totals
                document.getElementById('cart-subtotal').textContent = data.subtotal;
                document.getElementById('cart-total').textContent = data.total;
                
                // Reload to reflect discount
                window.location.reload();
            } else {
                couponMessage.className = 'form-text text-danger';
                couponMessage.textContent = data.message || 'Invalid coupon code';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            couponMessage.className = 'form-text text-danger';
            couponMessage.textContent = 'An error occurred. Please try again.';
        });
    }
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