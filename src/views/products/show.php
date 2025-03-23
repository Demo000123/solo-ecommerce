<?php
// Product detail page
require_once __DIR__ . '/../layouts/header.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? $_GET['id'] : null;

// Get product details
$product = null;
if ($productId) {
    // In a real application, this would fetch from the database
    // Example mock data
    $product = [
        'id' => 101,
        'name' => 'Áo thun unisex basic',
        'description' => 'Áo thun unisex basic với chất liệu cotton 100%, mềm mại, thoáng mát và thấm hút mồ hôi tốt. Kiểu dáng đơn giản, dễ phối đồ, phù hợp cho cả nam và nữ.',
        'price' => 250000,
        'sale_price' => 199000,
        'discount_percent' => 20,
        'stock' => 50,
        'rating' => 4.5,
        'review_count' => 120,
        'sku' => 'AT101',
        'category_id' => 1,
        'category_name' => 'Áo thun',
        'brand' => 'Brand Name',
        'tags' => ['áo thun', 'unisex', 'basic', 'cotton'],
        'images' => [
            '/public/images/products/tshirt.jpg',
            '/public/images/products/jeans.jpg',
            '/public/images/products/shoes.jpg',
            '/public/images/products/jacket.jpg',
        ],
        'main_image' => '/public/images/products/product-1.jpg',
        'variants' => [
            'color' => ['Trắng', 'Đen', 'Xanh navy', 'Xám'],
            'size' => ['S', 'M', 'L', 'XL']
        ],
        'features' => [
            'Chất liệu cotton 100%',
            'Form regular fit',
            'Cổ tròn, tay ngắn',
            'Màu sắc trơn, dễ phối đồ',
            'Phù hợp cho cả nam và nữ',
        ],
        'related_products' => [
            [
                'id' => 102,
                'name' => 'Quần jeans nam slim fit',
                'image' => '/public/images/products/product-2.jpg',
                'price' => 450000,
                'sale_price' => null,
            ],
            [
                'id' => 103,
                'name' => 'Giày sneaker nữ',
                'image' => '/public/images/products/product-3.jpg',
                'price' => 850000,
                'sale_price' => 680000,
            ],
            [
                'id' => 104,
                'name' => 'Túi xách nữ mini',
                'image' => '/public/images/products/product-4.jpg',
                'price' => 350000,
                'sale_price' => 280000,
            ],
        ],
    ];
}
?>

<link rel="stylesheet" href="/public/css/product-detail.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <?php if ($product): ?>
            <h1 class="page-title"><?= htmlspecialchars($product['name']) ?></h1>
            <div class="breadcrumbs">
                <a href="/">Trang chủ</a>
                <span class="separator">/</span>
                <a href="/products">Sản phẩm</a>
                <span class="separator">/</span>
                <a href="/products?category=<?= $product['category_id'] ?>"><?= htmlspecialchars($product['category_name']) ?></a>
                <span class="separator">/</span>
                <span class="current"><?= htmlspecialchars($product['name']) ?></span>
            </div>
        <?php else: ?>
            <h1 class="page-title">Chi tiết sản phẩm</h1>
            <div class="breadcrumbs">
                <a href="/">Trang chủ</a>
                <span class="separator">/</span>
                <a href="/products">Sản phẩm</a>
                <span class="separator">/</span>
                <span class="current">Chi tiết</span>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="product-container container">
    <?php if (!$product): ?>
        <div class="product-not-found">
            <div class="not-found-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h2>Không tìm thấy sản phẩm</h2>
            <p>Sản phẩm bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
            <div class="not-found-actions">
                <a href="/products" class="btn btn-primary">Xem tất cả sản phẩm</a>
            </div>
        </div>
    <?php else: ?>
        <div class="product-detail">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="<?= $product['main_image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" id="main-product-image">
                    <?php if (isset($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                        <span class="discount-badge">-<?= $product['discount_percent'] ?>%</span>
                    <?php endif; ?>
                </div>
                <div class="thumbnail-images">
                    <?php foreach ($product['images'] as $index => $image): ?>
                        <div class="thumbnail-item <?= $index === 0 ? 'active' : '' ?>" data-image="<?= $image ?>">
                            <img src="<?= $image ?>" alt="Thumbnail <?= $index + 1 ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="product-info">
                <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
                
                <div class="product-rating">
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= floor($product['rating'])): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif ($i - 0.5 <= $product['rating']): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <a href="#reviews" class="review-count"><?= $product['review_count'] ?> đánh giá</a>
                </div>
                
                <div class="product-price">
                    <?php if (isset($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                        <span class="price sale-price"><?= number_format($product['sale_price'], 0, ',', '.') ?>₫</span>
                        <span class="price old-price"><?= number_format($product['price'], 0, ',', '.') ?>₫</span>
                    <?php else: ?>
                        <span class="price"><?= number_format($product['price'], 0, ',', '.') ?>₫</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-meta">
                    <p class="product-sku">
                        <span class="meta-label">Mã sản phẩm:</span>
                        <span class="meta-value"><?= $product['sku'] ?></span>
                    </p>
                    <p class="product-stock">
                        <span class="meta-label">Tình trạng:</span>
                        <?php if ($product['stock'] > 0): ?>
                            <span class="meta-value in-stock">Còn hàng</span>
                        <?php else: ?>
                            <span class="meta-value out-of-stock">Hết hàng</span>
                        <?php endif; ?>
                    </p>
                    <p class="product-brand">
                        <span class="meta-label">Thương hiệu:</span>
                        <span class="meta-value"><?= htmlspecialchars($product['brand']) ?></span>
                    </p>
                </div>
                
                <div class="product-description">
                    <p><?= htmlspecialchars($product['description']) ?></p>
                </div>
                
                <div class="product-features">
                    <h3 class="features-title">Đặc điểm nổi bật</h3>
                    <ul class="features-list">
                        <?php foreach ($product['features'] as $feature): ?>
                            <li><?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <form class="product-form" action="/cart/add" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <?php if (isset($product['variants']['color']) && !empty($product['variants']['color'])): ?>
                        <div class="variant-group">
                            <label>Màu sắc:</label>
                            <div class="variant-options">
                                <?php foreach ($product['variants']['color'] as $index => $color): ?>
                                    <div class="variant-option">
                                        <input type="radio" name="color" id="color-<?= $index ?>" value="<?= htmlspecialchars($color) ?>" <?= $index === 0 ? 'checked' : '' ?>>
                                        <label for="color-<?= $index ?>" class="color-option"><?= htmlspecialchars($color) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($product['variants']['size']) && !empty($product['variants']['size'])): ?>
                        <div class="variant-group">
                            <label>Kích thước:</label>
                            <div class="variant-options">
                                <?php foreach ($product['variants']['size'] as $index => $size): ?>
                                    <div class="variant-option">
                                        <input type="radio" name="size" id="size-<?= $index ?>" value="<?= htmlspecialchars($size) ?>" <?= $index === 0 ? 'checked' : '' ?>>
                                        <label for="size-<?= $index ?>" class="size-option"><?= htmlspecialchars($size) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="quantity-group">
                        <label for="quantity">Số lượng:</label>
                        <div class="quantity-controls">
                            <button type="button" class="quantity-btn decrease">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
                            <button type="button" class="quantity-btn increase">+</button>
                        </div>
                    </div>
                    
                    <div class="product-actions">
                        <button type="submit" class="btn btn-primary btn-add-to-cart" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                            <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                        </button>
                        <button type="button" class="btn btn-outline btn-add-to-wishlist" data-product-id="<?= $product['id'] ?>">
                            <i class="far fa-heart"></i> Thêm vào yêu thích
                        </button>
                    </div>
                </form>
                
                <div class="product-tags">
                    <span class="meta-label">Tags:</span>
                    <?php foreach ($product['tags'] as $tag): ?>
                        <a href="/products?tag=<?= urlencode($tag) ?>" class="tag"><?= htmlspecialchars($tag) ?></a>
                    <?php endforeach; ?>
                </div>
                
                <div class="product-share">
                    <span class="meta-label">Chia sẻ:</span>
                    <div class="social-icons">
                        <a href="#" class="social-icon facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon pinterest"><i class="fab fa-pinterest-p"></i></a>
                        <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="related-products">
            <h2 class="section-title">Sản phẩm liên quan</h2>
            <div class="product-grid">
                <?php foreach ($product['related_products'] as $relatedProduct): ?>
                    <div class="product-card">
                        <div class="product-img-container">
                            <a href="/products/<?= $relatedProduct['id'] ?>">
                                <img src="<?= $relatedProduct['image'] ?>" alt="<?= htmlspecialchars($relatedProduct['name']) ?>" class="product-img">
                            </a>
                            <div class="product-actions">
                                <button type="button" class="action-btn quick-view-btn" title="Xem nhanh" data-product-id="<?= $relatedProduct['id'] ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="action-btn add-to-cart-btn" title="Thêm vào giỏ hàng" data-product-id="<?= $relatedProduct['id'] ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                                <button type="button" class="action-btn add-to-wishlist-btn" title="Thêm vào yêu thích" data-product-id="<?= $relatedProduct['id'] ?>">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">
                                <a href="/products/<?= $relatedProduct['id'] ?>"><?= htmlspecialchars($relatedProduct['name']) ?></a>
                            </h3>
                            <div class="product-price">
                                <?php if (isset($relatedProduct['sale_price']) && $relatedProduct['sale_price'] < $relatedProduct['price']): ?>
                                    <span class="price sale-price"><?= number_format($relatedProduct['sale_price'], 0, ',', '.') ?>₫</span>
                                    <span class="price old-price"><?= number_format($relatedProduct['price'], 0, ',', '.') ?>₫</span>
                                <?php else: ?>
                                    <span class="price"><?= number_format($relatedProduct['price'], 0, ',', '.') ?>₫</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
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
        // Thumbnail image click handler
        const mainImage = document.getElementById('main-product-image');
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const imageUrl = this.getAttribute('data-image');
                mainImage.src = imageUrl;
                
                // Update active thumbnail
                thumbnails.forEach(item => item.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Quantity controls
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.querySelector('.quantity-btn.decrease');
        const increaseBtn = document.querySelector('.quantity-btn.increase');
        
        if (decreaseBtn && increaseBtn && quantityInput) {
            decreaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });
            
            increaseBtn.addEventListener('click', function() {
                const currentValue = parseInt(quantityInput.value);
                const maxValue = parseInt(quantityInput.getAttribute('max'));
                if (currentValue < maxValue) {
                    quantityInput.value = currentValue + 1;
                }
            });
            
            quantityInput.addEventListener('change', function() {
                const currentValue = parseInt(this.value);
                const maxValue = parseInt(this.getAttribute('max'));
                
                if (isNaN(currentValue) || currentValue < 1) {
                    this.value = 1;
                } else if (currentValue > maxValue) {
                    this.value = maxValue;
                }
            });
        }
        
        // Wishlist button functionality
        const wishlistBtn = document.querySelector('.btn-add-to-wishlist');
        const relatedWishlistBtns = document.querySelectorAll('.product-actions .add-to-wishlist-btn');
        
        function showMessage(message, type = 'success') {
            const messageToast = document.getElementById('message-toast');
            const messageText = messageToast.querySelector('.message-text');
            const messageIcon = messageToast.querySelector('.icon');
            
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
        
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                
                // In a real app, make an AJAX call to add to wishlist
                // For now, just show a success message
                showMessage('Sản phẩm đã được thêm vào danh sách yêu thích!', 'success');
                
                // Toggle heart icon
                const heartIcon = this.querySelector('i');
                heartIcon.classList.toggle('far');
                heartIcon.classList.toggle('fas');
                
                // Example AJAX call would be:
                /*
                fetch('/api/wishlist/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('Sản phẩm đã được thêm vào danh sách yêu thích!', 'success');
                        
                        // Toggle heart icon
                        const heartIcon = this.querySelector('i');
                        heartIcon.classList.remove('far');
                        heartIcon.classList.add('fas');
                    } else {
                        showMessage(data.message || 'Có lỗi xảy ra!', 'error');
                    }
                })
                .catch(error => {
                    showMessage('Có lỗi xảy ra!', 'error');
                });
                */
            });
        }
        
        // Related products wishlist buttons
        if (relatedWishlistBtns) {
            relatedWishlistBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    
                    // In a real app, make an AJAX call to add to wishlist
                    // For now, just show a success message
                    showMessage('Sản phẩm đã được thêm vào danh sách yêu thích!', 'success');
                    
                    // Toggle heart icon
                    const heartIcon = this.querySelector('i');
                    heartIcon.classList.toggle('far');
                    heartIcon.classList.toggle('fas');
                });
            });
        }
        
        // Add to cart functionality for related products
        const relatedCartBtns = document.querySelectorAll('.product-actions .add-to-cart-btn');
        
        if (relatedCartBtns) {
            relatedCartBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.getAttribute('data-product-id');
                    
                    // In a real app, make an AJAX call to add to cart
                    // For now, just show a success message
                    showMessage('Sản phẩm đã được thêm vào giỏ hàng!', 'success');
                });
            });
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 