<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Link to our custom CSS file -->
<link rel="stylesheet" href="/public/css/home.css">
<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- VanillaTilt.js for 3D card effects -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

<?php
/**
 * Home page template
 * 
 * Variables available:
 * $featuredProducts - Array of featured products
 * $newProducts - Array of recently added products
 * $popularProducts - Array of most popular products
 * $featuredCategories - Array of featured categories
 */
?>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner rounded shadow">
        <div class="carousel-item active">
            <img src="/assets/images/hero-banner-1.jpg" class="d-block w-100" alt="Summer Sale" onerror="this.src='/assets/images/placeholder-banner.jpg'">
            <div class="carousel-caption d-none d-md-block">
                <h2>Summer Sale</h2>
                <p>Up to 50% off on selected items</p>
                <a href="/products?sale=1" class="btn btn-primary">Shop Now</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/images/hero-banner-2.jpg" class="d-block w-100" alt="New Arrivals" onerror="this.src='/assets/images/placeholder-banner.jpg'">
            <div class="carousel-caption d-none d-md-block">
                <h2>New Arrivals</h2>
                <p>Check out our latest products</p>
                <a href="/products?sort=newest" class="btn btn-primary">Explore</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="/assets/images/hero-banner-3.jpg" class="d-block w-100" alt="Free Shipping" onerror="this.src='/assets/images/placeholder-banner.jpg'">
            <div class="carousel-caption d-none d-md-block">
                <h2>Free Shipping</h2>
                <p>On orders over $50</p>
                <a href="/shipping" class="btn btn-primary">Learn More</a>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Featured Categories -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Shop by Category</h2>
        <a href="/categories" class="btn btn-outline-primary btn-sm">View All</a>
    </div>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($featuredCategories as $category): ?>
        <div class="col">
            <div class="card category-card h-100">
                <img src="<?= htmlspecialchars($category['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($category['name']) ?>" onerror="this.src='/assets/images/placeholder-category.jpg'">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($category['description']) ?></p>
                    <a href="/category/<?= htmlspecialchars($category['slug']) ?>" class="btn btn-primary">Browse Products</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Featured Products -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Featured Products</h2>
        <a href="/products?featured=1" class="btn btn-outline-primary btn-sm">View All</a>
    </div>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($featuredProducts as $product): ?>
        <div class="col">
            <div class="card product-card h-100">
                <div class="position-relative">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='/assets/images/placeholder-product.jpg'">
                    <?php if ($product['is_sale']): ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                    <?php endif; ?>
                    <?php if ($product['is_new']): ?>
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                    <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text text-muted small"><?= htmlspecialchars($product['category_name']) ?></p>
                    <div class="mt-auto">
                        <?php if ($product['discount_price']): ?>
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-danger fw-bold me-2">$<?= number_format($product['discount_price'], 2) ?></span>
                            <s class="text-muted small">$<?= number_format($product['price'], 2) ?></s>
                        </div>
                        <?php else: ?>
                        <p class="fw-bold mb-2">$<?= number_format($product['price'], 2) ?></p>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <a href="/product/<?= htmlspecialchars($product['slug']) ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                            <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- New Arrivals -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">New Arrivals</h2>
        <a href="/products?sort=newest" class="btn btn-outline-primary btn-sm">View All</a>
    </div>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($newProducts as $product): ?>
        <div class="col">
            <div class="card product-card h-100">
                <div class="position-relative">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='/assets/images/placeholder-product.jpg'">
                    <?php if ($product['is_sale']): ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                    <?php endif; ?>
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text text-muted small"><?= htmlspecialchars($product['category_name']) ?></p>
                    <div class="mt-auto">
                        <?php if ($product['discount_price']): ?>
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-danger fw-bold me-2">$<?= number_format($product['discount_price'], 2) ?></span>
                            <s class="text-muted small">$<?= number_format($product['price'], 2) ?></s>
                        </div>
                        <?php else: ?>
                        <p class="fw-bold mb-2">$<?= number_format($product['price'], 2) ?></p>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <a href="/product/<?= htmlspecialchars($product['slug']) ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                            <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Popular Products -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Most Popular</h2>
        <a href="/products?sort=popular" class="btn btn-outline-primary btn-sm">View All</a>
    </div>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <?php foreach ($popularProducts as $product): ?>
        <div class="col">
            <div class="card product-card h-100">
                <div class="position-relative">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='/assets/images/placeholder-product.jpg'">
                    <?php if ($product['is_sale']): ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                    <?php endif; ?>
                    <?php if ($product['is_new']): ?>
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                    <?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text text-muted small"><?= htmlspecialchars($product['category_name']) ?></p>
                    <div class="mt-auto">
                        <?php if ($product['discount_price']): ?>
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-danger fw-bold me-2">$<?= number_format($product['discount_price'], 2) ?></span>
                            <s class="text-muted small">$<?= number_format($product['price'], 2) ?></s>
                        </div>
                        <?php else: ?>
                        <p class="fw-bold mb-2">$<?= number_format($product['price'], 2) ?></p>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <a href="/product/<?= htmlspecialchars($product['slug']) ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                            <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Promotion Banners -->
<section class="mb-5">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="/assets/images/promo-banner-1.jpg" class="img-fluid rounded-start h-100" alt="Free Shipping" onerror="this.src='/assets/images/placeholder-banner.jpg'">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <h3 class="card-title">Free Shipping</h3>
                            <p class="card-text">On all orders over $50. Limited time offer.</p>
                            <a href="/shipping" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="/assets/images/promo-banner-2.jpg" class="img-fluid rounded-start h-100" alt="Reward Points" onerror="this.src='/assets/images/placeholder-banner.jpg'">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <h3 class="card-title">Reward Points</h3>
                            <p class="card-text">Earn points with every purchase. Redeem for discounts.</p>
                            <a href="/rewards" class="btn btn-outline-primary">Join Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="mb-5">
    <div class="card bg-light">
        <div class="card-body text-center py-5">
            <h3 class="card-title">Subscribe to Our Newsletter</h3>
            <p class="card-text">Stay updated with our latest products and offers</p>
            <form action="/newsletter/subscribe" method="post" class="row g-3 justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="email" class="form-control" name="email" placeholder="Your email address" required>
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="section-header">
        <h2 class="section-title">Browse <span class="gradient-text">Categories</span></h2>
        <p class="section-subtitle">Explore our wide range of product categories tailored to your needs</p>
    </div>
    
    <div class="categories-grid">
        <?php foreach($categories ?? [] as $category): ?>
            <a href="/products?category=<?= htmlspecialchars($category['id']) ?>" class="category-card">
                <div class="category-icon">
                    <i class="far <?= getCategoryIcon($category['name']) ?>"></i>
                </div>
                <h3 class="category-name"><?= htmlspecialchars($category['name']) ?></h3>
                <p class="category-description"><?= htmlspecialchars($category['description'] ?? "Explore our " . $category['name'] . " collection") ?></p>
                <span>Explore <i class="fas fa-arrow-right explore-arrow"></i></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Featured Products Section with 3D Cards -->
<section class="featured-section">
    <div class="section-header">
        <div>
            <h2 class="section-title">Featured <span class="gradient-text">Products</span></h2>
            <p class="section-subtitle">Handpicked selection of our best products</p>
        </div>
        <a href="/products?sort=newest" class="view-all">
            View All Products <i class="fas fa-arrow-right arrow"></i>
        </a>
    </div>
    
    <div class="featured-slider">
        <?php foreach($featuredProducts ?? [] as $product): ?>
            <div class="product-card-3d">
                <div class="product-card-inner">
                    <div class="product-image">
                        <?php if($product['created_at'] && strtotime($product['created_at']) > strtotime('-7 days')): ?>
                            <div class="badge new">New</div>
                        <?php elseif(($product['stock'] ?? 0) <= 0): ?>
                            <div class="badge out-of-stock">Out of Stock</div>
                        <?php endif; ?>
                        <img data-src="/public/images/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>" src="/public/images/placeholder.jpg">
                    </div>
                    <div class="product-details">
                        <span class="product-category"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></span>
                        <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                        <div class="product-price">$<?= number_format($product['price'], 2) ?></div>
                        <div class="product-actions">
                            <a href="/products/<?= $product['id'] ?>" class="btn btn-outline btn-sm">View Details</a>
                            <?php if(($product['stock'] ?? 0) > 0): ?>
                                <button onclick="addToCart(<?= $product['id'] ?>)" class="btn btn-primary btn-sm">Add to Cart</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Category Showcase -->
<section class="category-showcase">
    <?php 
    // Display showcase for a few main categories
    $showcaseCategories = [
        [
            'name' => 'Electronics',
            'description' => 'Cutting-edge technology and gadgets for modern living',
            'class' => 'electronics',
            'image' => 'electronics-showcase.jpg'
        ],
        [
            'name' => 'Clothing',
            'description' => 'Stay stylish with our latest fashion collections',
            'class' => 'clothing',
            'image' => 'clothing-showcase.jpg'
        ],
        [
            'name' => 'Home & Kitchen',
            'description' => 'Transform your living space with our home essentials',
            'class' => 'home',
            'image' => 'home-showcase.jpg'
        ]
    ];
    
    foreach($showcaseCategories as $showcase): 
        // Find matching category ID from actual categories
        $categoryId = null;
        foreach($categories ?? [] as $category) {
            if(stripos($category['name'], $showcase['name']) !== false) {
                $categoryId = $category['id'];
                break;
            }
        }
    ?>
        <div class="showcase-card <?= $showcase['class'] ?>">
            <div class="showcase-content">
                <h2 class="showcase-title"><?= htmlspecialchars($showcase['name']) ?></h2>
                <p class="showcase-description"><?= htmlspecialchars($showcase['description']) ?></p>
                <?php if($categoryId): ?>
                    <a href="/products?category=<?= $categoryId ?>" class="btn btn-light">Shop Now</a>
                <?php else: ?>
                    <a href="/products" class="btn btn-light">Shop Now</a>
                <?php endif; ?>
            </div>
            <div class="showcase-image">
                <img src="/public/images/<?= htmlspecialchars($showcase['image']) ?>" alt="<?= htmlspecialchars($showcase['name']) ?>">
            </div>
        </div>
    <?php endforeach; ?>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="feature-card">
        <div class="feature-icon">
            <i class="fas fa-truck-fast"></i>
        </div>
        <h3>Free Shipping</h3>
        <p>On all orders over $50</p>
    </div>
    
    <div class="feature-card">
        <div class="feature-icon">
            <i class="fas fa-undo"></i>
        </div>
        <h3>Easy Returns</h3>
        <p>30-day return policy</p>
    </div>
    
    <div class="feature-card">
        <div class="feature-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h3>Secure Payments</h3>
        <p>Protected by encryption</p>
    </div>
    
    <div class="feature-card">
        <div class="feature-icon">
            <i class="fas fa-headset"></i>
        </div>
        <h3>24/7 Support</h3>
        <p>We're here to help</p>
    </div>
</section>

<!-- Helper function for category icons -->
<?php
function getCategoryIcon($categoryName) {
    $categoryName = strtolower($categoryName);
    
    if (strpos($categoryName, 'electronics') !== false || strpos($categoryName, 'gadget') !== false) {
        return 'fa-laptop';
    } elseif (strpos($categoryName, 'clothing') !== false || strpos($categoryName, 'fashion') !== false || strpos($categoryName, 'apparel') !== false) {
        return 'fa-tshirt';
    } elseif (strpos($categoryName, 'home') !== false || strpos($categoryName, 'furniture') !== false || strpos($categoryName, 'decor') !== false) {
        return 'fa-couch';
    } elseif (strpos($categoryName, 'beauty') !== false || strpos($categoryName, 'cosmetic') !== false) {
        return 'fa-spa';
    } elseif (strpos($categoryName, 'book') !== false || strpos($categoryName, 'media') !== false) {
        return 'fa-book';
    } elseif (strpos($categoryName, 'sport') !== false || strpos($categoryName, 'fitness') !== false) {
        return 'fa-dumbbell';
    } elseif (strpos($categoryName, 'toy') !== false || strpos($categoryName, 'kid') !== false || strpos($categoryName, 'baby') !== false) {
        return 'fa-gamepad';
    } elseif (strpos($categoryName, 'food') !== false || strpos($categoryName, 'grocery') !== false) {
        return 'fa-utensils';
    } else {
        return 'fa-tag'; // Default icon
    }
}
?>

<!-- Custom JS for Homepage -->
<script src="/public/js/home.js"></script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 