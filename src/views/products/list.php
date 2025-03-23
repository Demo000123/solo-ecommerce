<?php
// Get data from the controller
$products = $products ?? [];
$categories = $categories ?? [];
$currentCategory = $currentCategory ?? null;
$totalProducts = $totalProducts ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$pageSize = $pageSize ?? 12;
$minPrice = $minPrice ?? 0;
$maxPrice = $maxPrice ?? 10000;
$sort = $sort ?? 'newest';
$searchQuery = $searchQuery ?? '';
$title = $title ?? 'All Products';
?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar with filters -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form action="" method="GET" id="filterForm">
                        <?php if (!empty($searchQuery)): ?>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($searchQuery) ?>">
                        <?php endif; ?>
                        
                        <!-- Categories -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Categories</h6>
                            <div class="overflow-auto" style="max-height: 200px;">
                                <?php foreach ($categories as $category): ?>
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" name="category[]" 
                                            value="<?= $category['id'] ?>" id="category<?= $category['id'] ?>"
                                            <?= (isset($_GET['category']) && in_array($category['id'], $_GET['category'])) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="category<?= $category['id'] ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Price Range</h6>
                            <div class="d-flex mb-2">
                                <input type="number" name="min_price" id="minPrice" class="form-control form-control-sm me-2" 
                                    placeholder="Min" value="<?= isset($_GET['min_price']) ? (int)$_GET['min_price'] : '' ?>">
                                <input type="number" name="max_price" id="maxPrice" class="form-control form-control-sm" 
                                    placeholder="Max" value="<?= isset($_GET['max_price']) ? (int)$_GET['max_price'] : '' ?>">
                            </div>
                        </div>
                        
                        <!-- Availability -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Availability</h6>
                            <div class="form-check">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="in_stock" value="1" 
                                    id="inStock" <?= isset($_GET['in_stock']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inStock">
                                    In Stock Only
                                </label>
                            </div>
                        </div>
                        
                        <!-- On Sale -->
                        <div class="mb-4">
                            <h6 class="fw-bold">Promotions</h6>
                            <div class="form-check">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="on_sale" value="1" 
                                    id="onSale" <?= isset($_GET['on_sale']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="onSale">
                                    On Sale
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="featured" value="1" 
                                    id="featured" <?= isset($_GET['featured']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="featured">
                                    Featured
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <button type="button" id="resetFilters" class="btn btn-outline-secondary w-100 mt-2">Reset Filters</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Product listing -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2"><?= htmlspecialchars($title) ?></h1>
                
                <!-- Search and Sort -->
                <div class="d-flex">
                    <div class="input-group me-2">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search products..." 
                            value="<?= htmlspecialchars($searchQuery) ?>">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <select class="form-select" id="sortSelect">
                        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Popularity</option>
                        <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Rating</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <p>Showing <?= count($products) ?> of <?= $totalProducts ?> products</p>
            </div>
            
            <?php if (empty($products)): ?>
                <div class="alert alert-info">
                    No products found. Try adjusting your filters or search terms.
                </div>
            <?php else: ?>
                <!-- Products Grid -->
                <div class="row g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100 product-card">
                                <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                    <div class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</div>
                                <?php endif; ?>
                                
                                <img src="<?= !empty($product['image']) ? '/uploads/products/' . $product['image'] : '/assets/images/products/placeholder.jpg' ?>" 
                                    class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                    
                                    <!-- Display rating -->
                                    <div class="mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= round($product['avg_rating'] ?? 0)): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <small class="text-muted">(<?= (int)($product['review_count'] ?? 0) ?>)</small>
                                    </div>
                                    
                                    <p class="card-text"><?= htmlspecialchars(substr($product['short_description'] ?? '', 0, 100)) ?><?= strlen($product['short_description'] ?? '') > 100 ? '...' : '' ?></p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                                                    <span class="text-muted text-decoration-line-through"><?= number_format($product['price'], 0) ?></span>
                                                    <span class="text-danger fw-bold"><?= number_format($product['sale_price'], 0) ?></span>
                                                <?php else: ?>
                                                    <span class="fw-bold"><?= number_format($product['price'], 0) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Stock status -->
                                            <?php if ($product['stock_quantity'] > 0): ?>
                                                <span class="badge bg-success">In Stock</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Out of Stock</span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="d-grid gap-2 mt-3">
                                            <a href="/products/<?= $product['id'] ?>" class="btn btn-primary">View Details</a>
                                            <?php if ($product['stock_quantity'] > 0): ?>
                                                <button class="btn btn-outline-primary add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                                    Add to Cart
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-outline-secondary" disabled>Out of Stock</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?><?= http_build_query(array_merge($_GET, ['page' => $currentPage - 1])) ?>">Previous</a>
                            </li>
                            
                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $currentPage + 1])) ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission
        const filterForm = document.getElementById('filterForm');
        const searchButton = document.getElementById('searchButton');
        const searchInput = document.getElementById('searchInput');
        const sortSelect = document.getElementById('sortSelect');
        const resetButton = document.getElementById('resetFilters');
        
        // Search functionality
        searchButton.addEventListener('click', function() {
            const searchValue = searchInput.value.trim();
            window.location.href = `?search=${encodeURIComponent(searchValue)}`;
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchButton.click();
            }
        });
        
        // Sort functionality
        sortSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('sort', this.value);
            window.location.href = currentUrl.toString();
        });
        
        // Reset filters
        resetButton.addEventListener('click', function() {
            window.location.href = window.location.pathname;
        });
        
        // Add to cart functionality
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                addToCart(productId, 1);
            });
        });
        
        function addToCart(productId, quantity) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Product added to cart!');
                    // Update cart count if available
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cartCount;
                        cartCountElement.style.display = data.cartCount > 0 ? 'inline-block' : 'none';
                    }
                } else {
                    alert(data.message || 'Failed to add product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    });
</script> 