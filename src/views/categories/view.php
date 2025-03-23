<?php
// Get data from the controller
$category = $category ?? null;
$products = $products ?? [];
$subcategories = $subcategories ?? [];
$totalProducts = $totalProducts ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$pageSize = $pageSize ?? 12;
$breadcrumbs = $breadcrumbs ?? [];

// If category not found
if (!$category) {
    echo '<div class="container my-5"><div class="alert alert-danger">Category not found.</div></div>';
    return;
}
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/categories">Categories</a></li>
            <?php foreach ($breadcrumbs as $crumb): ?>
                <?php if ($crumb['id'] == $category['id']): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($crumb['name']) ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="/category/<?= $crumb['id'] ?>"><?= htmlspecialchars($crumb['name']) ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>

    <div class="row">
        <!-- Category Info -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <?php if (!empty($category['image'])): ?>
                            <div class="col-md-3">
                                <img src="/uploads/categories/<?= $category['image'] ?>" class="img-fluid" alt="<?= htmlspecialchars($category['name']) ?>">
                            </div>
                        <?php endif; ?>
                        <div class="<?= !empty($category['image']) ? 'col-md-9' : 'col-12' ?>">
                            <h1 class="card-title"><?= htmlspecialchars($category['name']) ?></h1>
                            <?php if (!empty($category['description'])): ?>
                                <p class="card-text"><?= nl2br(htmlspecialchars($category['description'])) ?></p>
                            <?php endif; ?>
                            <p class="text-muted"><?= $totalProducts ?> products in this category</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Subcategories -->
        <?php if (!empty($subcategories)): ?>
            <div class="col-12 mb-4">
                <h3>Browse Subcategories</h3>
                <div class="row g-4">
                    <?php foreach ($subcategories as $subcat): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100">
                                <?php if (!empty($subcat['image'])): ?>
                                    <img src="/uploads/categories/<?= $subcat['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($subcat['name']) ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($subcat['name']) ?></h5>
                                    <?php if (!empty($subcat['description'])): ?>
                                        <p class="card-text small"><?= htmlspecialchars(substr($subcat['description'], 0, 100)) ?><?= strlen($subcat['description']) > 100 ? '...' : '' ?></p>
                                    <?php endif; ?>
                                    <p class="card-text text-muted small"><?= $subcat['product_count'] ?? 0 ?> products</p>
                                    <a href="/category/<?= $subcat['id'] ?>" class="btn btn-outline-primary">View Products</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Products in this category -->
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Products in <?= htmlspecialchars($category['name']) ?></h3>
                
                <!-- Sort Options -->
                <div class="d-flex">
                    <select class="form-select" id="sortSelect">
                        <option value="newest" <?= isset($_GET['sort']) && $_GET['sort'] === 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="price_asc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= isset($_GET['sort']) && $_GET['sort'] === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="popular" <?= isset($_GET['sort']) && $_GET['sort'] === 'popular' ? 'selected' : '' ?>>Popularity</option>
                        <option value="rating" <?= isset($_GET['sort']) && $_GET['sort'] === 'rating' ? 'selected' : '' ?>>Rating</option>
                    </select>
                </div>
            </div>
            
            <?php if (empty($products)): ?>
                <div class="alert alert-info">
                    No products found in this category.
                </div>
            <?php else: ?>
                <!-- Products Grid -->
                <div class="row g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
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
                                    
                                    <p class="card-text"><?= htmlspecialchars(substr($product['short_description'] ?? '', 0, 80)) ?><?= strlen($product['short_description'] ?? '') > 80 ? '...' : '' ?></p>
                                    
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
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?><?= isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) : '' ?>">Previous</a>
                            </li>
                            
                            <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?><?= isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) : '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $currentPage + 1 ?><?= isset($_GET['sort']) ? '&sort=' . htmlspecialchars($_GET['sort']) : '' ?>">Next</a>
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
        // Sort functionality
        const sortSelect = document.getElementById('sortSelect');
        sortSelect.addEventListener('change', function() {
            window.location.href = `?sort=${this.value}<?= isset($_GET['page']) ? '&page=' . (int)$_GET['page'] : '' ?>`;
        });
        
        // Add to cart functionality
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                
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
            });
        });
    });
</script> 