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
        <form id="checkoutForm" action="/checkout/process" method="POST">
            <div class="row">
                <!-- Customer Information Section -->
                <div class="col-lg-8">
                    <!-- Progress Indicator -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="checkout-progress">
                                <div class="progress mb-3">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 33.33%" aria-valuenow="33.33" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="progress-step active">
                                        <span class="progress-step-number">1</span>
                                        <span class="progress-step-label">Your Information</span>
                                    </div>
                                    <div class="progress-step">
                                        <span class="progress-step-number">2</span>
                                        <span class="progress-step-label">Shipping & Payment</span>
                                    </div>
                                    <div class="progress-step">
                                        <span class="progress-step-number">3</span>
                                        <span class="progress-step-label">Confirmation</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Display validation errors if any -->
                    <?php if (!empty($validationErrors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <h5 class="alert-heading">Please correct the following errors:</h5>
                            <ul class="mb-0">
                                <?php foreach ($validationErrors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Shipping Address Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['user_id']) && !empty($userAddresses)): ?>
                                <!-- For logged in users with saved addresses -->
                                <div class="mb-3">
                                    <label for="addressSelect" class="form-label">Select Address</label>
                                    <select class="form-select" id="addressSelect" name="address_id">
                                        <option value="">-- Select a saved address --</option>
                                        <?php foreach ($userAddresses as $address): ?>
                                            <option value="<?= $address['id'] ?>" <?= $selectedAddressId == $address['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($address['address_name'] ?? 'Address ' . $address['id']) ?> - 
                                                <?= htmlspecialchars($address['address_line1']) ?>, 
                                                <?= htmlspecialchars($address['city']) ?>, 
                                                <?= htmlspecialchars($address['state']) ?>, 
                                                <?= htmlspecialchars($address['country']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="new">Use a new address</option>
                                    </select>
                                </div>
                                
                                <div id="newAddressForm" class="<?= $selectedAddressId === 'new' ? '' : 'd-none' ?>">
                            <?php endif; ?>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="first_name" required
                                           value="<?= htmlspecialchars($user['first_name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="last_name" required
                                           value="<?= htmlspecialchars($user['last_name'] ?? '') ?>">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="addressLine1" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="addressLine1" name="address_line1" required
                                       placeholder="Street address, P.O. box, company name">
                            </div>
                            
                            <div class="mb-3">
                                <label for="addressLine2" class="form-label">Address Line 2 (Optional)</label>
                                <input type="text" class="form-control" id="addressLine2" name="address_line2"
                                       placeholder="Apartment, suite, unit, building, floor, etc.">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select" id="country" name="country" required>
                                        <option value="">Select Country</option>
                                        <?php foreach ($countries as $code => $name): ?>
                                            <option value="<?= $code ?>"><?= htmlspecialchars($name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="state" class="form-label">State/Province</label>
                                    <select class="form-select" id="state" name="state" required>
                                        <option value="">Select State/Province</option>
                                        <?php foreach ($states as $code => $name): ?>
                                            <option value="<?= $code ?>"><?= htmlspecialchars($name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="zipCode" class="form-label">ZIP/Postal Code</label>
                                    <input type="text" class="form-control" id="zipCode" name="zip_code" required>
                                </div>
                            </div>
                            
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="saveAddress" name="save_address" value="1">
                                    <label class="form-check-label" for="saveAddress">
                                        Save this address to my account
                                    </label>
                                </div>
                                <?php if (isset($userAddresses) && !empty($userAddresses)): ?>
                                    </div> <!-- End of newAddressForm -->
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Shipping Method Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Shipping Method</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($shippingMethods)): ?>
                                <div class="alert alert-info">No shipping methods available for your location.</div>
                            <?php else: ?>
                                <?php foreach ($shippingMethods as $method): ?>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input shipping-method" type="radio" name="shipping_method_id" 
                                               id="shipping<?= $method['id'] ?>" value="<?= $method['id'] ?>"
                                               data-fee="<?= $method['price'] ?>"
                                               <?= $selectedShippingMethod == $method['id'] ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="shipping<?= $method['id'] ?>">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div>
                                                    <span class="fw-bold"><?= htmlspecialchars($method['name']) ?></span>
                                                    <?php if (!empty($method['description'])): ?>
                                                        <p class="text-muted small mb-0"><?= htmlspecialchars($method['description']) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="ms-3 fw-bold"><?= number_format($method['price'], 0) ?></span>
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Payment Method Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($paymentMethods as $method): ?>
                                <div class="form-check mb-3">
                                    <input class="form-check-input payment-method" type="radio" name="payment_method" 
                                           id="payment<?= $method['id'] ?>" value="<?= $method['code'] ?>" required>
                                    <label class="form-check-label" for="payment<?= $method['id'] ?>">
                                        <?php if (!empty($method['icon'])): ?>
                                            <img src="<?= $method['icon'] ?>" alt="<?= htmlspecialchars($method['name']) ?>" height="24" class="me-2">
                                        <?php endif; ?>
                                        <span class="fw-bold"><?= htmlspecialchars($method['name']) ?></span>
                                        <?php if (!empty($method['description'])): ?>
                                            <p class="text-muted small mb-0"><?= htmlspecialchars($method['description']) ?></p>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                
                                <?php if ($method['code'] === 'credit_card'): ?>
                                    <div id="creditCardFields" class="payment-fields card-payment-fields mb-3 ps-4 d-none">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="cardNumber" class="form-label">Card Number</label>
                                                <input type="text" class="form-control" id="cardNumber" name="card_number" placeholder="1234 5678 9012 3456">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="cardExpiry" class="form-label">Expiration Date</label>
                                                <input type="text" class="form-control" id="cardExpiry" name="card_expiry" placeholder="MM/YY">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="cardCvv" class="form-label">CVV</label>
                                                <input type="text" class="form-control" id="cardCvv" name="card_cvv" placeholder="123">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="cardName" class="form-label">Name on Card</label>
                                                <input type="text" class="form-control" id="cardName" name="card_name" placeholder="John Doe">
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($method['code'] === 'bank_transfer'): ?>
                                    <div id="bankTransferFields" class="payment-fields bank-transfer-fields mb-3 ps-4 d-none">
                                        <div class="alert alert-info">
                                            <h6>Bank Transfer Information</h6>
                                            <p class="mb-0">Please transfer the total amount to our bank account. Your order will be processed once the payment is confirmed.</p>
                                            <hr>
                                            <p class="mb-0">Bank: National Bank</p>
                                            <p class="mb-0">Account Name: Solo E-commerce</p>
                                            <p class="mb-0">Account Number: 1234567890</p>
                                            <p class="mb-0">Reference: Your Order Number (will be provided after checkout)</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Additional Notes Section -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Additional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="orderNotes" class="form-label">Order Notes (Optional)</label>
                                <textarea class="form-control" id="orderNotes" name="order_notes" rows="3" 
                                          placeholder="Notes about your order, e.g. special delivery instructions"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary Section -->
                <div class="col-lg-4">
                    <div class="card mb-4 sticky-top" style="top: 20px; z-index: 1000;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="order-summary">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Items (<?= $totalItems ?>):</span>
                                    <span class="fw-bold"><?= number_format($subtotal, 0) ?></span>
                                </div>
                                
                                <?php if ($couponDiscount > 0): ?>
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Discount (<?= htmlspecialchars($couponCode) ?>):</span>
                                        <span>-<?= number_format($couponDiscount, 0) ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span id="shippingFeeDisplay"><?= $shippingFee > 0 ? number_format($shippingFee, 0) : 'Free' ?></span>
                                </div>
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fw-bold">Total:</span>
                                    <span class="fw-bold h5" id="totalDisplay"><?= number_format($total, 0) ?></span>
                                </div>
                                
                                <div class="order-items mb-3">
                                    <h6 class="mb-3">Items in Your Order</h6>
                                    <div class="order-items-list">
                                        <?php foreach ($cartItems as $item): ?>
                                            <div class="d-flex mb-2">
                                                <div class="flex-shrink-0">
                                                    <img src="<?= !empty($item['image']) ? '/uploads/products/' . $item['image'] : '/assets/images/products/placeholder.jpg' ?>" 
                                                        alt="<?= htmlspecialchars($item['name']) ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                </div>
                                                <div class="ms-3 flex-grow-1">
                                                    <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                                    <div class="d-flex justify-content-between">
                                                        <small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                                                        <span class="fw-bold">
                                                            <?php
                                                                $itemPrice = ($item['sale_price'] && $item['sale_price'] < $item['price']) ? $item['sale_price'] : $item['price'];
                                                                echo number_format($itemPrice * $item['quantity'], 0);
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-success btn-lg w-100" id="placeOrderBtn">
                                    <i class="fas fa-lock me-2"></i>Place Order
                                </button>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        By placing your order, you agree to our
                                        <a href="/page/terms" target="_blank">Terms of Service</a> and
                                        <a href="/page/privacy" target="_blank">Privacy Policy</a>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Address selection for logged in users
        const addressSelect = document.getElementById('addressSelect');
        const newAddressForm = document.getElementById('newAddressForm');
        
        if (addressSelect) {
            addressSelect.addEventListener('change', function() {
                if (this.value === 'new') {
                    newAddressForm.classList.remove('d-none');
                } else {
                    newAddressForm.classList.add('d-none');
                }
            });
        }
        
        // Show/hide payment method fields
        const paymentMethods = document.querySelectorAll('.payment-method');
        const paymentFields = document.querySelectorAll('.payment-fields');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                // Hide all payment fields
                paymentFields.forEach(field => {
                    field.classList.add('d-none');
                });
                
                // Show relevant fields based on selection
                if (this.value === 'credit_card') {
                    document.getElementById('creditCardFields').classList.remove('d-none');
                } else if (this.value === 'bank_transfer') {
                    document.getElementById('bankTransferFields').classList.remove('d-none');
                }
            });
        });
        
        // Update order total when shipping method changes
        const shippingMethods = document.querySelectorAll('.shipping-method');
        const shippingFeeDisplay = document.getElementById('shippingFeeDisplay');
        const totalDisplay = document.getElementById('totalDisplay');
        const subtotal = <?= $subtotal ?>;
        const couponDiscount = <?= $couponDiscount ?>;
        
        shippingMethods.forEach(method => {
            method.addEventListener('change', function() {
                const shippingFee = parseFloat(this.dataset.fee);
                
                // Update shipping fee display
                shippingFeeDisplay.textContent = shippingFee > 0 ? shippingFee.toLocaleString() : 'Free';
                
                // Calculate and update total
                const total = subtotal - couponDiscount + shippingFee;
                totalDisplay.textContent = total.toLocaleString();
            });
        });
        
        // Form validation
        const checkoutForm = document.getElementById('checkoutForm');
        
        checkoutForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate shipping method selection
            const selectedShipping = document.querySelector('input[name="shipping_method_id"]:checked');
            if (!selectedShipping) {
                isValid = false;
                alert('Please select a shipping method');
            }
            
            // Validate payment method selection
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
            if (!selectedPayment) {
                isValid = false;
                alert('Please select a payment method');
            }
            
            // Validate credit card fields if credit card payment is selected
            if (selectedPayment && selectedPayment.value === 'credit_card') {
                const cardNumber = document.getElementById('cardNumber').value.trim();
                const cardExpiry = document.getElementById('cardExpiry').value.trim();
                const cardCvv = document.getElementById('cardCvv').value.trim();
                const cardName = document.getElementById('cardName').value.trim();
                
                if (!cardNumber || !cardExpiry || !cardCvv || !cardName) {
                    isValid = false;
                    alert('Please fill in all credit card details');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
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