<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Link to our custom CSS file -->
<link rel="stylesheet" href="/public/css/home.css">
<!-- FontAwesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- VanillaTilt.js for 3D card effects -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>

<!-- Hero Section with 3D Animation -->
<section class="hero-container">
    <div class="hero-content">
        <h1 class="hero-title">Discover <span class="gradient-text">Next-Gen</span> Shopping Experience</h1>
        <p class="hero-subtitle">Shop the latest trends in fashion, electronics, home decor and more with immersive 3D experiences and personalized recommendations.</p>
        <div class="hero-cta">
            <a href="/products" class="btn btn-primary btn-large">Shop Now</a>
            <a href="/categories" class="btn btn-outline btn-large">Browse Categories</a>
        </div>
    </div>
    <div class="hero-image-container">
        <div class="blob-shape"></div>
        <div class="floating-products">
            <?php
            // Display 3 random featured products as floating images
            $featuredProducts = array_slice($featuredProducts ?? [], 0, 3);
            foreach ($featuredProducts as $index => $product): ?>
                <div class="floating-product">
                    <img src="/public/images/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['name'] ?? 'Product') ?>">
                </div>
            <?php endforeach; ?>
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

<!-- Newsletter Section with Glassmorphism -->
<section class="newsletter-section">
    <div class="glassmorphism-card">
        <h2>Join Our Newsletter</h2>
        <p>Subscribe to get special offers, free giveaways, and product launches info.</p>
        <form class="newsletter-form" onsubmit="subscribeNewsletter(event)">
            <input type="email" placeholder="Your email address" required>
            <button type="submit" class="btn">Subscribe</button>
        </form>
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