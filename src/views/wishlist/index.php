<?php
// Wishlist page
require_once __DIR__ . '/../layouts/header.php';

// Check if user is logged in
$userLoggedIn = isset($user) && !empty($user);

// Get wishlist items if user is logged in
$wishlistItems = [];
if ($userLoggedIn) {
    // In a real application, this would fetch from the database
    // Example mock data
    $wishlistItems = [
        [
            'id' => 1,
            'product_id' => 101,
            'name' => 'Áo thun unisex basic',
            'image' => '/public/images/products/tshirt.jpg',
            'price' => 250000,
            'sale_price' => 199000,
            'in_stock' => true,
            'date_added' => '2023-05-15',
        ],
        [
            'id' => 2,
            'product_id' => 102,
            'name' => 'Quần jeans nam slim fit',
            'image' => '/public/images/products/jeans.jpg',
            'price' => 450000,
            'sale_price' => null,
            'in_stock' => true,
            'date_added' => '2023-05-18',
        ],
        [
            'id' => 3,
            'product_id' => 103,
            'name' => 'Giày sneaker',
            'image' => '/public/images/products/shoes.jpg',
            'price' => 850000,
            'sale_price' => 680000,
            'in_stock' => false,
            'date_added' => '2023-05-20',
        ],
    ];
}
?>

<link rel="stylesheet" href="/public/css/wishlist.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Danh sách yêu thích</h1>
        <div class="breadcrumbs">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <span class="current">Danh sách yêu thích</span>
        </div>
    </div>
</div>

<div class="wishlist-container container">
    <?php if (!$userLoggedIn): ?>
        <div class="wishlist-not-logged-in">
            <div class="wishlist-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h2>Đăng nhập để xem danh sách yêu thích</h2>
            <p>Bạn cần đăng nhập để lưu và xem các sản phẩm yêu thích của mình.</p>
            <div class="wishlist-actions">
                <a href="/account" class="btn btn-primary">Đăng nhập ngay</a>
                <a href="/products" class="btn btn-outline">Tiếp tục mua sắm</a>
            </div>
        </div>
    <?php else: ?>
        <?php if (empty($wishlistItems)): ?>
            <div class="wishlist-empty">
                <div class="wishlist-icon">
                    <i class="far fa-heart"></i>
                </div>
                <h2>Danh sách yêu thích trống</h2>
                <p>Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                <div class="wishlist-actions">
                    <a href="/products" class="btn btn-primary">Khám phá sản phẩm</a>
                </div>
            </div>
        <?php else: ?>
            <div class="wishlist-content">
                <div class="wishlist-header">
                    <div class="wishlist-count">
                        <span><?= count($wishlistItems) ?> sản phẩm</span>
                    </div>
                    <div class="wishlist-actions-header">
                        <button type="button" class="btn-link clear-wishlist">
                            <i class="fas fa-trash-alt"></i> Xóa tất cả
                        </button>
                    </div>
                </div>
                
                <div class="wishlist-items">
                    <?php foreach ($wishlistItems as $item): ?>
                        <div class="wishlist-item" data-id="<?= $item['id'] ?>">
                            <div class="wishlist-item-image">
                                <a href="/products/<?= $item['product_id'] ?>">
                                    <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                </a>
                            </div>
                            
                            <div class="wishlist-item-info">
                                <h3 class="wishlist-item-name">
                                    <a href="/products/<?= $item['product_id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                                </h3>
                                
                                <div class="wishlist-item-price">
                                    <?php if ($item['sale_price']): ?>
                                        <span class="price sale-price"><?= number_format($item['sale_price'], 0, ',', '.') ?>₫</span>
                                        <span class="price old-price"><?= number_format($item['price'], 0, ',', '.') ?>₫</span>
                                    <?php else: ?>
                                        <span class="price"><?= number_format($item['price'], 0, ',', '.') ?>₫</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="wishlist-item-availability">
                                    <?php if ($item['in_stock']): ?>
                                        <span class="in-stock"><i class="fas fa-check-circle"></i> Còn hàng</span>
                                    <?php else: ?>
                                        <span class="out-of-stock"><i class="fas fa-times-circle"></i> Hết hàng</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="wishlist-item-date">
                                    <span>Đã thêm vào: <?= date('d/m/Y', strtotime($item['date_added'])) ?></span>
                                </div>
                            </div>
                            
                            <div class="wishlist-item-actions">
                                <?php if ($item['in_stock']): ?>
                                    <button type="button" class="btn btn-primary add-to-cart-btn" data-product-id="<?= $item['product_id'] ?>">
                                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-disabled" disabled>
                                        <i class="fas fa-shopping-cart"></i> Hết hàng
                                    </button>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-outline remove-wishlist-btn" data-id="<?= $item['id'] ?>">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="wishlist-footer">
                    <a href="/products" class="btn btn-outline btn-lg">
                        <i class="fas fa-chevron-left"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Message toast -->
<div id="message-toast" class="message-toast">
    <div class="message-content">
        <i class="icon"></i>
        <span class="message-text"></span>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
        const removeWishlistButtons = document.querySelectorAll('.remove-wishlist-btn');
        const clearWishlistButton = document.querySelector('.clear-wishlist');
        const messageToast = document.getElementById('message-toast');
        const messageText = messageToast.querySelector('.message-text');
        const messageIcon = messageToast.querySelector('.icon');
        
        // Show toast message
        function showMessage(message, type = 'success') {
            messageText.textContent = message;
            messageToast.classList.add('show', type);
            
            if (type === 'success') {
                messageIcon.className = 'icon fas fa-check-circle';
            } else if (type === 'error') {
                messageIcon.className = 'icon fas fa-exclamation-circle';
            } else if (type === 'info') {
                messageIcon.className = 'icon fas fa-info-circle';
            }
            
            setTimeout(() => {
                messageToast.classList.remove('show', type);
            }, 3000);
        }
        
        // Add to cart
        if (addToCartButtons) {
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    
                    // In a real app, make an AJAX call to add to cart
                    // For now, just show a success message
                    showMessage('Sản phẩm đã được thêm vào giỏ hàng!', 'success');
                    
                    // Example AJAX call would be:
                    /*
                    fetch('/api/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage('Sản phẩm đã được thêm vào giỏ hàng!', 'success');
                            // Update cart count if needed
                        } else {
                            showMessage(data.message || 'Có lỗi xảy ra!', 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Có lỗi xảy ra!', 'error');
                    });
                    */
                });
            });
        }
        
        // Remove from wishlist
        if (removeWishlistButtons) {
            removeWishlistButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const wishlistId = this.getAttribute('data-id');
                    const wishlistItem = document.querySelector(`.wishlist-item[data-id="${wishlistId}"]`);
                    
                    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
                        // In a real app, make an AJAX call to remove from wishlist
                        // For demo, just remove the item from DOM
                        if (wishlistItem) {
                            wishlistItem.remove();
                            showMessage('Đã xóa sản phẩm khỏi danh sách yêu thích!', 'info');
                            
                            // Update item count
                            const remainingItems = document.querySelectorAll('.wishlist-item').length;
                            const wishlistCount = document.querySelector('.wishlist-count span');
                            
                            if (wishlistCount) {
                                wishlistCount.textContent = `${remainingItems} sản phẩm`;
                            }
                            
                            // If no items left, show empty state
                            if (remainingItems === 0) {
                                const wishlistContent = document.querySelector('.wishlist-content');
                                if (wishlistContent) {
                                    // Create empty wishlist element
                                    const emptyWishlist = document.createElement('div');
                                    emptyWishlist.className = 'wishlist-empty';
                                    emptyWishlist.innerHTML = `
                                        <div class="wishlist-icon">
                                            <i class="far fa-heart"></i>
                                        </div>
                                        <h2>Danh sách yêu thích trống</h2>
                                        <p>Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                                        <div class="wishlist-actions">
                                            <a href="/products" class="btn btn-primary">Khám phá sản phẩm</a>
                                        </div>
                                    `;
                                    
                                    // Replace content with empty state
                                    const container = document.querySelector('.wishlist-container');
                                    container.innerHTML = '';
                                    container.appendChild(emptyWishlist);
                                }
                            }
                        }
                        
                        // Example AJAX call would be:
                        /*
                        fetch('/api/wishlist/remove', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                wishlist_id: wishlistId
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (wishlistItem) {
                                    wishlistItem.remove();
                                }
                                showMessage('Đã xóa sản phẩm khỏi danh sách yêu thích!', 'info');
                                
                                // Update item count
                                const remainingItems = data.count || document.querySelectorAll('.wishlist-item').length;
                                const wishlistCount = document.querySelector('.wishlist-count span');
                                
                                if (wishlistCount) {
                                    wishlistCount.textContent = `${remainingItems} sản phẩm`;
                                }
                                
                                // If no items left, show empty state
                                if (remainingItems === 0) {
                                    window.location.reload();
                                }
                            } else {
                                showMessage(data.message || 'Có lỗi xảy ra!', 'error');
                            }
                        })
                        .catch(error => {
                            showMessage('Có lỗi xảy ra!', 'error');
                        });
                        */
                    }
                });
            });
        }
        
        // Clear all wishlist items
        if (clearWishlistButton) {
            clearWishlistButton.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi danh sách yêu thích?')) {
                    // In a real app, make an AJAX call to clear wishlist
                    // For demo, just remove all items from DOM
                    const container = document.querySelector('.wishlist-container');
                    
                    // Create empty wishlist element
                    const emptyWishlist = document.createElement('div');
                    emptyWishlist.className = 'wishlist-empty';
                    emptyWishlist.innerHTML = `
                        <div class="wishlist-icon">
                            <i class="far fa-heart"></i>
                        </div>
                        <h2>Danh sách yêu thích trống</h2>
                        <p>Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                        <div class="wishlist-actions">
                            <a href="/products" class="btn btn-primary">Khám phá sản phẩm</a>
                        </div>
                    `;
                    
                    // Replace content with empty state
                    container.innerHTML = '';
                    container.appendChild(emptyWishlist);
                    
                    showMessage('Đã xóa tất cả sản phẩm khỏi danh sách yêu thích!', 'info');
                    
                    // Example AJAX call would be:
                    /*
                    fetch('/api/wishlist/clear', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            showMessage(data.message || 'Có lỗi xảy ra!', 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('Có lỗi xảy ra!', 'error');
                    });
                    */
                }
            });
        }
    });
</script>

<style>
    .wishlist-container {
        margin: 2.5rem auto 4rem;
    }
    
    .wishlist-not-logged-in,
    .wishlist-empty {
        text-align: center;
        padding: 3rem 1rem;
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
    }
    
    .wishlist-icon {
        font-size: 4rem;
        color: var(--light-text);
        margin-bottom: 1.5rem;
    }
    
    .wishlist-not-logged-in h2,
    .wishlist-empty h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: var(--heading-color);
    }
    
    .wishlist-not-logged-in p,
    .wishlist-empty p {
        color: var(--light-text);
        margin-bottom: 1.5rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .wishlist-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
    
    .wishlist-content {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        padding: 1.5rem;
    }
    
    .wishlist-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }
    
    .wishlist-count {
        font-weight: 500;
        color: var(--body-color);
    }
    
    .btn-link {
        background: none;
        border: none;
        color: var(--primary-color);
        cursor: pointer;
        padding: 0;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-link:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }
    
    .wishlist-items {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .wishlist-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .wishlist-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .wishlist-item-image img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: var(--border-radius);
    }
    
    .wishlist-item-name {
        font-size: 1.1rem;
        margin: 0 0 0.5rem 0;
    }
    
    .wishlist-item-name a {
        color: var(--heading-color);
        text-decoration: none;
    }
    
    .wishlist-item-name a:hover {
        color: var(--primary-color);
    }
    
    .wishlist-item-price {
        margin-bottom: 0.5rem;
    }
    
    .price {
        font-weight: 600;
        color: var(--heading-color);
    }
    
    .sale-price {
        color: var(--primary-color);
        margin-right: 0.5rem;
    }
    
    .old-price {
        text-decoration: line-through;
        font-weight: normal;
        color: var(--light-text);
        font-size: 0.9rem;
    }
    
    .wishlist-item-availability {
        margin-bottom: 0.5rem;
    }
    
    .in-stock {
        color: #16a34a;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }
    
    .out-of-stock {
        color: #dc2626;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }
    
    .wishlist-item-date {
        font-size: 0.85rem;
        color: var(--light-text);
    }
    
    .wishlist-item-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        justify-content: center;
    }
    
    .wishlist-footer {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-start;
    }
    
    .btn {
        display: inline-block;
        padding: 0.8rem 1.25rem;
        border-radius: var(--border-radius);
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition-normal);
        border: none;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .btn-lg {
        padding: 1rem 1.5rem;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
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
        color: white;
    }
    
    .btn-disabled {
        background-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }
    
    /* Message toast */
    .message-toast {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        transform: translateY(150%);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
        z-index: 1000;
    }
    
    .message-toast.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .message-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .message-toast .icon {
        font-size: 1.25rem;
    }
    
    .message-toast.success .icon {
        color: #16a34a;
    }
    
    .message-toast.error .icon {
        color: #dc2626;
    }
    
    .message-toast.info .icon {
        color: #2563eb;
    }
    
    .message-text {
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .wishlist-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }
        
        .wishlist-item-actions {
            grid-column: 1 / -1;
            flex-direction: row;
            justify-content: space-between;
            margin-top: 1rem;
        }
        
        .wishlist-item-actions .btn {
            flex: 1;
        }
        
        .wishlist-actions {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .message-toast {
            left: 1.5rem;
            right: 1.5rem;
            bottom: 1rem;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>