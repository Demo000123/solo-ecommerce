<?php
/**
 * Product Comparison page template
 * 
 * Variables available:
 * $products - Array of products being compared
 * $totalProducts - Number of products being compared
 * $maxProducts - Maximum number of products that can be compared at once
 */
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product Comparison</li>
        </ol>
    </nav>
    
    <h1 class="h2 mb-4">Compare Products</h1>
    
    <?php if (empty($products)): ?>
    <!-- Empty Comparison -->
    <div class="card border-0 shadow-sm p-4 text-center">
        <div class="empty-compare-container py-5">
            <i class="fas fa-exchange-alt fa-4x text-muted mb-4"></i>
            <h2 class="h4">No products to compare</h2>
            <p class="text-muted mb-4">You haven't added any products to your comparison list yet.</p>
            <a href="/products" class="btn btn-primary">Browse Products</a>
        </div>
    </div>
    <?php else: ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="mb-0 text-muted">Comparing <?= $totalProducts ?> product<?= $totalProducts > 1 ? 's' : '' ?> (maximum of <?= $maxProducts ?>)</p>
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearCompare()">
            <i class="fas fa-trash me-2"></i>Clear All
        </button>
    </div>
    
    <div class="comparison-table-wrapper">
        <div class="table-responsive">
            <table class="table table-bordered comparison-table">
                <thead>
                    <tr class="bg-light">
                        <th style="width: 15%; min-width: 150px;">Product</th>
                        <?php foreach ($products as $product): ?>
                        <th class="text-center position-relative" style="width: <?= 85 / count($products) ?>%;">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-product" 
                                    data-product-id="<?= $product['id'] ?>" 
                                    aria-label="Remove"></button>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                 class="img-fluid rounded mx-auto d-block mb-2" 
                                 style="max-height: 120px; max-width: 100%;"
                                 alt="<?= htmlspecialchars($product['name']) ?>"
                                 onerror="this.src='/assets/images/placeholders/product.jpg'">
                            <h5 class="h6 mb-2">
                                <a href="/product/<?= htmlspecialchars($product['slug']) ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($product['name']) ?>
                                </a>
                            </h5>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Price -->
                    <tr>
                        <td class="fw-bold bg-light">Price</td>
                        <?php foreach ($products as $product): ?>
                        <td class="text-center">
                            <?php if ($product['discount_price'] && $product['discount_price'] < $product['price']): ?>
                                <div class="text-danger fw-bold">$<?= number_format($product['discount_price'], 2) ?></div>
                                <s class="text-muted small">$<?= number_format($product['price'], 2) ?></s>
                                <div class="badge bg-danger mt-1">
                                    <?= round((($product['price'] - $product['discount_price']) / $product['price']) * 100) ?>% OFF
                                </div>
                            <?php else: ?>
                                <div class="fw-bold">$<?= number_format($product['price'], 2) ?></div>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- Rating -->
                    <tr>
                        <td class="fw-bold bg-light">Rating</td>
                        <?php foreach ($products as $product): ?>
                        <td class="text-center">
                            <div class="d-flex justify-content-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= round($product['rating'])): ?>
                                    <i class="fas fa-star text-warning"></i>
                                    <?php elseif ($i - 0.5 <= $product['rating']): ?>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                    <?php else: ?>
                                    <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <small class="text-muted">(<?= $product['reviews_count'] ?> reviews)</small>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- Availability -->
                    <tr>
                        <td class="fw-bold bg-light">Availability</td>
                        <?php foreach ($products as $product): ?>
                        <td class="text-center">
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <span class="badge bg-success">In Stock</span>
                                <?php if ($product['stock_quantity'] < 10): ?>
                                <div class="small text-danger mt-1">Only <?= $product['stock_quantity'] ?> left</div>
                                <?php endif; ?>
                            <?php elseif ($product['stock_status'] === 'backorder'): ?>
                                <span class="badge bg-warning text-dark">Backorder</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Out of Stock</span>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- Brand -->
                    <tr>
                        <td class="fw-bold bg-light">Brand</td>
                        <?php foreach ($products as $product): ?>
                        <td class="text-center">
                            <?= $product['brand'] ? htmlspecialchars($product['brand']) : '<span class="text-muted">N/A</span>' ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- SKU -->
                    <tr>
                        <td class="fw-bold bg-light">SKU</td>
                        <?php foreach ($products as $product): ?>
                        <td class="text-center">
                            <?= $product['sku'] ? htmlspecialchars($product['sku']) : '<span class="text-muted">N/A</span>' ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- Description -->
                    <tr>
                        <td class="fw-bold bg-light">Description</td>
                        <?php foreach ($products as $product): ?>
                        <td>
                            <div class="description-truncate">
                                <?= $product['short_description'] ? nl2br(htmlspecialchars($product['short_description'])) : '<span class="text-muted">No description available</span>' ?>
                            </div>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <!-- Features/Specifications -->
                    <?php 
                    // Collect all attribute keys across all products
                    $allAttributes = [];
                    foreach ($products as $product) {
                        if (isset($product['attributes']) && is_array($product['attributes'])) {
                            foreach ($product['attributes'] as $attr => $value) {
                                if (!in_array($attr, $allAttributes)) {
                                    $allAttributes[] = $attr;
                                }
                            }
                        }
                    }
                    
                    // Sort attributes alphabetically
                    sort($allAttributes);
                    
                    // Display each attribute row
                    foreach ($allAttributes as $attribute):
                    ?>
                    <tr>
                        <td class="fw-bold bg-light"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $attribute))) ?></td>
                        <?php foreach ($products as $product): ?>
                        <td class="text-center">
                            <?php if (isset($product['attributes'][$attribute])): ?>
                                <?= htmlspecialchars($product['attributes'][$attribute]) ?>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                    
                    <!-- Actions -->
                    <tr>
                        <td class="fw-bold bg-light">Actions</td>
                        <?php foreach ($products as $product): ?>
                        <td class="text-center">
                            <div class="d-grid gap-2">
                                <?php if ($product['stock_quantity'] > 0 || $product['stock_status'] === 'backorder'): ?>
                                <button type="button" class="btn btn-primary btn-sm add-to-cart" data-product-id="<?= $product['id'] ?>">
                                    <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                </button>
                                <?php else: ?>
                                <button type="button" class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-shopping-cart me-1"></i> Out of Stock
                                </button>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-outline-primary btn-sm add-to-wishlist" data-product-id="<?= $product['id'] ?>">
                                    <i class="far fa-heart me-1"></i> Add to Wishlist
                                </button>
                            </div>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="d-flex justify-content-between mt-4">
        <a href="/products" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
        </a>
        <?php if (count(array_filter($products, function($product) { return $product['stock_quantity'] > 0; })) > 0): ?>
        <button type="button" class="btn btn-primary" onclick="addAllToCart()">
            <i class="fas fa-shopping-cart me-2"></i>Add All Available to Cart
        </button>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Clear Comparison Confirmation Modal -->
<div class="modal fade" id="clearCompareModal" tabindex="-1" aria-labelledby="clearCompareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearCompareModalLabel">Confirm Clear</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove all products from your comparison list?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmClearCompare">Clear All</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap modal
        const clearCompareModal = new bootstrap.Modal(document.getElementById('clearCompareModal'));
        
        // Add to cart buttons
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                addToCart(productId);
            });
        });
        
        // Add to wishlist buttons
        const addToWishlistButtons = document.querySelectorAll('.add-to-wishlist');
        addToWishlistButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                addToWishlist(productId);
            });
        });
        
        // Remove product buttons
        const removeButtons = document.querySelectorAll('.remove-product');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                removeFromCompare(productId);
            });
        });
        
        // Confirm clear comparison button in modal
        document.getElementById('confirmClearCompare').addEventListener('click', function() {
            clearCompareAction();
            clearCompareModal.hide();
        });
    });
    
    function addToCart(productId) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `product_id=${productId}&quantity=1`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product added to cart successfully!', 'success');
                // Update cart counter if available
                if (data.cartCount) {
                    updateCartCounter(data.cartCount);
                }
            } else {
                showNotification(data.message || 'Failed to add product to cart', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function addToWishlist(productId) {
        fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product added to wishlist successfully!', 'success');
            } else {
                showNotification(data.message || 'Failed to add product to wishlist', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function removeFromCompare(productId) {
        fetch('/compare/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                showNotification(data.message || 'Failed to remove product from comparison', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function clearCompare() {
        const clearCompareModal = new bootstrap.Modal(document.getElementById('clearCompareModal'));
        clearCompareModal.show();
    }
    
    function clearCompareAction() {
        fetch('/compare/clear', {
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
                showNotification(data.message || 'Failed to clear comparison list', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function addAllToCart() {
        fetch('/compare/add-all-to-cart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('All available products added to cart successfully!', 'success');
                // Update cart counter if available
                if (data.cartCount) {
                    updateCartCounter(data.cartCount);
                }
                
                // Optional: Redirect to cart page
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                showNotification(data.message || 'Failed to add products to cart', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function updateCartCounter(count) {
        const cartCounters = document.querySelectorAll('.cart-counter');
        cartCounters.forEach(counter => {
            counter.textContent = count;
            counter.classList.remove('d-none');
        });
    }
    
    function showNotification(message, type = 'info') {
        // Check if notification container exists, if not create it
        let notificationContainer = document.querySelector('.notification-container');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container position-fixed top-0 end-0 p-3';
            notificationContainer.style.zIndex = "1080";
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `toast align-items-center text-white bg-${type} border-0`;
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'assertive');
        notification.setAttribute('aria-atomic', 'true');
        
        // Create notification content
        notification.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        // Add notification to container
        notificationContainer.appendChild(notification);
        
        // Initialize and show toast
        const toast = new bootstrap.Toast(notification, { delay: 5000 });
        toast.show();
        
        // Remove notification after it's hidden
        notification.addEventListener('hidden.bs.toast', function() {
            notification.remove();
        });
    }
</script>

<style>
    .comparison-table th, 
    .comparison-table td {
        vertical-align: middle;
    }
    
    .description-truncate {
        max-height: 100px;
        overflow-y: auto;
    }
    
    @media (max-width: 768px) {
        .comparison-table th, 
        .comparison-table td {
            min-width: 200px;
        }
    }
</style> 