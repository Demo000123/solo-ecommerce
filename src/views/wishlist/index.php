<?php
/**
 * Wishlist page template
 * 
 * Variables available:
 * $wishlistItems - Array of items in the wishlist
 * $totalItems - Number of items in the wishlist
 */
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
        </ol>
    </nav>
    
    <h1 class="h2 mb-4">My Wishlist</h1>
    
    <?php if (empty($wishlistItems)): ?>
    <!-- Empty Wishlist -->
    <div class="card border-0 shadow-sm p-4 text-center">
        <div class="empty-wishlist-container py-5">
            <i class="fas fa-heart fa-4x text-muted mb-4"></i>
            <h2 class="h4">Your wishlist is empty</h2>
            <p class="text-muted mb-4">Save items you like to your wishlist and they'll appear here.</p>
            <a href="/products" class="btn btn-primary">Browse Products</a>
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <!-- Wishlist Items -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Saved Items (<?= $totalItems ?>)</h5>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearWishlist()">
                            <i class="fas fa-trash me-2"></i>Clear Wishlist
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 80px;"></th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Stock Status</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wishlistItems as $item): ?>
                                <tr>
                                    <!-- Product Image -->
                                    <td>
                                        <a href="/product/<?= htmlspecialchars($item['product_slug']) ?>">
                                            <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                                                 class="img-fluid rounded" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                                 style="max-width: 70px; max-height: 70px;"
                                                 onerror="this.src='/assets/images/placeholders/product.jpg'">
                                        </a>
                                    </td>
                                    
                                    <!-- Product Details -->
                                    <td>
                                        <h6 class="mb-1">
                                            <a href="/product/<?= htmlspecialchars($item['product_slug']) ?>" class="text-decoration-none text-dark">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </a>
                                        </h6>
                                        <p class="text-muted mb-0 small">Added on <?= date('M d, Y', strtotime($item['date_added'])) ?></p>
                                    </td>
                                    
                                    <!-- Price -->
                                    <td>
                                        <?php if ($item['discount_price'] && $item['discount_price'] < $item['price']): ?>
                                            <span class="text-danger">$<?= number_format($item['discount_price'], 2) ?></span>
                                            <br><small><s class="text-muted">$<?= number_format($item['price'], 2) ?></s></small>
                                        <?php else: ?>
                                            <span>$<?= number_format($item['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- Stock Status -->
                                    <td>
                                        <?php if ($item['stock_quantity'] > 0): ?>
                                            <span class="badge bg-success">In Stock</span>
                                        <?php elseif ($item['stock_status'] === 'backorder'): ?>
                                            <span class="badge bg-warning text-dark">Backorder</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Out of Stock</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <?php if ($item['stock_quantity'] > 0 || $item['stock_status'] === 'backorder'): ?>
                                                <button type="button" class="btn btn-outline-primary add-to-cart" 
                                                        data-product-id="<?= $item['product_id'] ?>"
                                                        data-bs-toggle="tooltip" title="Add to Cart">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-outline-primary" disabled
                                                        data-bs-toggle="tooltip" title="Out of Stock">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-outline-danger remove-from-wishlist" 
                                                    data-item-id="<?= $item['id'] ?>"
                                                    data-bs-toggle="tooltip" title="Remove">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="/products" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <?php if (count(array_filter($wishlistItems, function($item) { return $item['stock_quantity'] > 0; })) > 0): ?>
                        <button type="button" class="btn btn-primary" onclick="addAllToCart()">
                            <i class="fas fa-shopping-cart me-2"></i>Add All Available to Cart
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="removeItemModal" tabindex="-1" aria-labelledby="removeItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeItemModalLabel">Confirm Removal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this item from your wishlist?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRemove">Remove</button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Wishlist Confirmation Modal -->
<div class="modal fade" id="clearWishlistModal" tabindex="-1" aria-labelledby="clearWishlistModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearWishlistModalLabel">Confirm Clear Wishlist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to remove all items from your wishlist?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmClearWishlist">Clear Wishlist</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Initialize modals
        const removeItemModal = new bootstrap.Modal(document.getElementById('removeItemModal'));
        const clearWishlistModal = new bootstrap.Modal(document.getElementById('clearWishlistModal'));
        
        // Add to cart buttons
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                addToCart(productId);
            });
        });
        
        // Remove from wishlist buttons
        const removeButtons = document.querySelectorAll('.remove-from-wishlist');
        let itemIdToRemove = null;
        
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                itemIdToRemove = this.getAttribute('data-item-id');
                removeItemModal.show();
            });
        });
        
        // Confirm remove button in modal
        document.getElementById('confirmRemove').addEventListener('click', function() {
            if (itemIdToRemove) {
                removeFromWishlist(itemIdToRemove);
                removeItemModal.hide();
            }
        });
        
        // Confirm clear wishlist button in modal
        document.getElementById('confirmClearWishlist').addEventListener('click', function() {
            clearWishlistAction();
            clearWishlistModal.hide();
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
    
    function removeFromWishlist(itemId) {
        fetch('/wishlist/remove', {
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
                showNotification(data.message || 'Failed to remove item from wishlist', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function clearWishlist() {
        const clearWishlistModal = new bootstrap.Modal(document.getElementById('clearWishlistModal'));
        clearWishlistModal.show();
    }
    
    function clearWishlistAction() {
        fetch('/wishlist/clear', {
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
                showNotification(data.message || 'Failed to clear wishlist', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function addAllToCart() {
        fetch('/wishlist/add-all-to-cart', {
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