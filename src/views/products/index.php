<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/public/css/products.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title"><?= $pageTitle ?? 'Sản phẩm' ?></h1>
        <div class="breadcrumbs">
            <a href="/solo-ecommerce/src/views/home/index.php">Trang chủ</a>
            <span class="separator">/</span>
            <span class="current">Sản phẩm</span>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/products">Products</a></li>
            <?php if ($currentCategory): ?>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($currentCategory['name']) ?></li>
            <?php else: ?>
            <li class="breadcrumb-item active" aria-current="page">All Products</li>
            <?php endif; ?>
        </ol>
    </nav>
    
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2 mb-0"><?= $pageTitle ?></h1>
            <?php if ($currentCategory && !empty($currentCategory['description'])): ?>
            <p class="lead text-muted mt-2"><?= htmlspecialchars($currentCategory['description']) ?></p>
            <?php endif; ?>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-md-end mt-3 mt-md-0">
            <div class="d-flex align-items-center">
                <label for="sort" class="me-2 text-nowrap">Sort by:</label>
                <select id="sort" class="form-select form-select-sm" onchange="applyFilters()">
                    <?php foreach ($sortOptions as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $currentSort === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Search</h6>
                        <div class="input-group">
                            <input type="text" id="searchQuery" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($searchQuery ?? '') ?>">
                            <button class="btn btn-outline-secondary" type="button" onclick="applyFilters()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Categories Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Categories</h6>
                        <div class="list-group">
                            <a href="/products" class="list-group-item list-group-item-action <?= empty($currentCategory) ? 'active' : '' ?>">
                                All Categories
                            </a>
                            <?php foreach ($categories as $category): ?>
                            <a href="/category/<?= htmlspecialchars($category['slug']) ?>" class="list-group-item list-group-item-action <?= isset($currentCategory) && $currentCategory['id'] === $category['id'] ? 'active' : '' ?>">
                                <?= htmlspecialchars($category['name']) ?>
                                <span class="badge bg-secondary float-end"><?= $category['product_count'] ?></span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Price Range</h6>
                        <div class="list-group">
                            <a href="<?= buildFilterUrl(['price' => '']) ?>" class="list-group-item list-group-item-action <?= empty($currentPriceRange) ? 'active' : '' ?>">
                                All Prices
                            </a>
                            <?php foreach ($priceRanges as $key => $range): ?>
                            <a href="<?= buildFilterUrl(['price' => $key]) ?>" class="list-group-item list-group-item-action <?= $currentPriceRange === $key ? 'active' : '' ?>">
                                <?= htmlspecialchars($range['label']) ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Availability Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Availability</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inStockOnly" <?= isset($_GET['in_stock']) && $_GET['in_stock'] === '1' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="inStockOnly">
                                In Stock Only
                            </label>
                        </div>
                    </div>
                    
                    <!-- Special Offers Filter -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Special Offers</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="onSale" <?= isset($_GET['on_sale']) && $_GET['on_sale'] === '1' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="onSale">
                                On Sale
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="newArrivals" <?= isset($_GET['new']) && $_GET['new'] === '1' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="newArrivals">
                                New Arrivals
                            </label>
                        </div>
                    </div>
                    
                    <!-- Apply Filters Button -->
                    <button type="button" class="btn btn-primary w-100" onclick="applyFilters()">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Products Count & View Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted mb-0">Showing <?= count($products) ?> of <?= $totalProducts ?> products</p>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary active" data-view="grid">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
            
            <?php if (empty($products)): ?>
            <div class="alert alert-info">
                <p class="mb-0">No products found. Try adjusting your filters or search query.</p>
            </div>
            <?php else: ?>
            <!-- Grid View (Default) -->
            <div class="product-view grid-view">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($products as $product): ?>
                    <div class="col">
                        <div class="card product-card h-100">
                            <div class="position-relative">
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='/assets/images/placeholders/product.jpg'">
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
            </div>
            
            <!-- List View (Hidden by Default) -->
            <div class="product-view list-view d-none">
                <?php foreach ($products as $product): ?>
                <div class="card product-card mb-3">
                    <div class="row g-0">
                        <div class="col-md-3 position-relative">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" class="img-fluid rounded-start h-100 object-fit-cover" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='/assets/images/placeholders/product.jpg'">
                            <?php if ($product['is_sale']): ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                            <?php endif; ?>
                            <?php if ($product['is_new']): ?>
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text text-muted mb-2"><?= htmlspecialchars($product['category_name']) ?></p>
                                <p class="card-text"><?= htmlspecialchars($product['short_description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if ($product['discount_price']): ?>
                                        <div class="d-flex align-items-center">
                                            <span class="text-danger fw-bold me-2">$<?= number_format($product['discount_price'], 2) ?></span>
                                            <s class="text-muted small">$<?= number_format($product['price'], 2) ?></s>
                                        </div>
                                        <?php else: ?>
                                        <p class="fw-bold mb-0">$<?= number_format($product['price'], 2) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex">
                                        <a href="/product/<?= htmlspecialchars($product['slug']) ?>" class="btn btn-outline-primary btn-sm me-2">View Details</a>
                                        <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav aria-label="Product pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildFilterUrl(['page' => $currentPage - 1]) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $startPage + 4);
                    if ($endPage - $startPage < 4) {
                        $startPage = max(1, $endPage - 4);
                    }
                    
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="<?= buildFilterUrl(['page' => $i]) ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= buildFilterUrl(['page' => $currentPage + 1]) ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
/**
 * Helper function to build filter URLs
 * 
 * @param array $params Parameters to update in the current URL
 * @return string The URL with updated parameters
 */
function buildFilterUrl($params = []) {
    $queryParams = $_GET;
    
    foreach ($params as $key => $value) {
        if ($value === '' || $value === null) {
            unset($queryParams[$key]);
        } else {
            $queryParams[$key] = $value;
        }
    }
    
    $baseUrl = strtok($_SERVER['REQUEST_URI'], '?');
    if (!empty($queryParams)) {
        return $baseUrl . '?' . http_build_query($queryParams);
    }
    
    return $baseUrl;
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle between grid and list view
        const viewButtons = document.querySelectorAll('[data-view]');
        const productViews = document.querySelectorAll('.product-view');
        
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                
                // Update active button
                viewButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Show selected view, hide others
                productViews.forEach(productView => {
                    if (productView.classList.contains(view + '-view')) {
                        productView.classList.remove('d-none');
                    } else {
                        productView.classList.add('d-none');
                    }
                });
                
                // Save preference in localStorage
                localStorage.setItem('product_view_preference', view);
            });
        });
        
        // Load saved view preference
        const savedView = localStorage.getItem('product_view_preference');
        if (savedView) {
            const button = document.querySelector(`[data-view="${savedView}"]`);
            if (button) {
                button.click();
            }
        }
    });
    
    function applyFilters() {
        const searchQuery = document.getElementById('searchQuery').value;
        const sortBy = document.getElementById('sort').value;
        const inStockOnly = document.getElementById('inStockOnly').checked ? '1' : '';
        const onSale = document.getElementById('onSale').checked ? '1' : '';
        const newArrivals = document.getElementById('newArrivals').checked ? '1' : '';
        
        const params = {
            'q': searchQuery,
            'sort': sortBy,
            'in_stock': inStockOnly,
            'on_sale': onSale,
            'new': newArrivals,
            'page': 1 // Reset to first page when applying new filters
        };
        
        window.location.href = buildFilterUrl(params);
    }
</script>

<style>
    .product-container {
        display: flex;
        gap: 30px;
        margin-top: 2rem;
    }
    
    .product-filters {
        width: 280px;
        flex-shrink: 0;
    }
    
    .product-right-column {
        flex: 1;
    }
    
    .filter-section {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .filter-title {
        font-size: 1.1rem;
        margin-bottom: 15px;
        color: var(--heading-color);
        position: relative;
    }
    
    .category-filter {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .category-button {
        display: block;
        text-decoration: none;
        color: var(--body-color);
        padding: 8px 12px;
        border-radius: var(--border-radius);
        transition: var(--transition-normal);
    }
    
    .category-button:hover {
        background-color: rgba(163, 168, 116, 0.1);
        color: var(--primary-color);
    }
    
    .category-button.active {
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    .price-inputs {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .price-input-group {
        flex: 1;
    }
    
    .price-input-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 0.9rem;
        color: var(--body-color);
    }
    
    .price-input-group input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
    }
    
    .filter-checkbox {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .filter-checkbox input {
        margin-right: 10px;
    }
    
    .clear-filters {
        display: block;
        text-align: center;
        color: var(--primary-color);
        text-decoration: none;
        margin-top: 10px;
        font-size: 0.9rem;
    }
    
    .clear-filters:hover {
        text-decoration: underline;
    }
    
    .product-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        background-color: var(--white);
        padding: 15px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
    }
    
    .search-input-container {
        position: relative;
        width: 300px;
    }
    
    .search-input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
    }
    
    .search-button {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--body-color);
        cursor: pointer;
        padding: 5px 10px;
    }
    
    .sort-options {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .sort-select {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        font-size: 0.9rem;
        color: var(--body-color);
        background-color: var(--white);
    }
    
    .active-filters {
        background-color: var(--white);
        padding: 15px;
        border-radius: var(--border-radius);
        margin-bottom: 20px;
        box-shadow: var(--box-shadow-sm);
    }
    
    .filter-label {
        font-weight: 500;
        margin-right: 10px;
    }
    
    .filter-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }
    
    .filter-tag {
        display: inline-flex;
        align-items: center;
        background-color: rgba(163, 168, 116, 0.1);
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    
    .remove-tag {
        margin-left: 5px;
        color: var(--body-color);
        text-decoration: none;
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .product-count {
        margin-bottom: 20px;
        font-size: 0.9rem;
        color: var(--light-text);
    }
    
    .empty-results {
        text-align: center;
        padding: 50px 0;
    }
    
    .empty-icon {
        font-size: 4rem;
        color: var(--light-text);
        margin-bottom: 20px;
    }
    
    .btn-sm {
        padding: 8px 15px;
        font-size: 0.9rem;
    }
    
    .btn-outline {
        background-color: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
    }
    
    .btn-outline:hover {
        background-color: var(--primary-color);
        color: var(--white);
    }
    
    @media (max-width: 992px) {
        .product-container {
            flex-direction: column;
        }
        
        .product-filters {
            width: 100%;
        }
        
        .search-input-container {
            width: 250px;
        }
    }
    
    @media (max-width: 768px) {
        .product-controls {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .search-input-container {
            width: 100%;
        }
        
        .sort-options {
            width: 100%;
        }
        
        .sort-select {
            flex-grow: 1;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 