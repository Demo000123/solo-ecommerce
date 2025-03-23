<link rel="stylesheet" href="/public/css/products.css">

<div class="product-container">
    <div class="product-filters">
        <div class="search-container">
            <form action="/products" method="GET" class="search-form">
                <?php if (isset($currentCategory)): ?>
                    <input type="hidden" name="category" value="<?= $currentCategory ?>">
                <?php endif; ?>
                <?php if (isset($currentSort) && $currentSort !== 'name'): ?>
                    <input type="hidden" name="sort" value="<?= $currentSort ?>">
                <?php endif; ?>
                <?php if (isset($currentDirection) && $currentDirection !== 'asc'): ?>
                    <input type="hidden" name="direction" value="<?= $currentDirection ?>">
                <?php endif; ?>
                <input type="text" name="search" placeholder="Search products..." class="search-input" value="<?= $currentSearch ?? '' ?>">
                <button type="submit" class="btn search-button">Search</button>
            </form>
        </div>
        
        <div class="category-filter">
            <a href="/products" class="btn category-button <?= !isset($currentCategory) ? 'active' : '' ?>">All</a>
            <?php foreach ($categories as $category): ?>
                <a href="/products?category=<?= $category ?>" class="btn category-button <?= ($currentCategory ?? '') === $category ? 'active' : '' ?>">
                    <?= ucfirst($category) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="sort-options">
        <span class="sort-label">Sort by:</span>
        <select id="sort-select" class="sort-select" onchange="window.location.href=this.value">
            <option value="<?= $baseUrl ?>sort=name&direction=asc&page=1" <?= $currentSort === 'name' && $currentDirection === 'asc' ? 'selected' : '' ?>>
                Name (A-Z)
            </option>
            <option value="<?= $baseUrl ?>sort=name&direction=desc&page=1" <?= $currentSort === 'name' && $currentDirection === 'desc' ? 'selected' : '' ?>>
                Name (Z-A)
            </option>
            <option value="<?= $baseUrl ?>sort=price&direction=asc&page=1" <?= $currentSort === 'price' && $currentDirection === 'asc' ? 'selected' : '' ?>>
                Price (Low to High)
            </option>
            <option value="<?= $baseUrl ?>sort=price&direction=desc&page=1" <?= $currentSort === 'price' && $currentDirection === 'desc' ? 'selected' : '' ?>>
                Price (High to Low)
            </option>
            <option value="<?= $baseUrl ?>sort=category&direction=asc&page=1" <?= $currentSort === 'category' && $currentDirection === 'asc' ? 'selected' : '' ?>>
                Category
            </option>
        </select>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-results">
            <h2>No products found</h2>
            <p>Try a different search term or category.</p>
            <a href="/products" class="btn">View All Products</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-img-container">
                        <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>" class="product-img">
                        <?php if (!$product->isInStock()): ?>
                            <span class="product-badge">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-body">
                        <div class="product-category"><?= ucfirst($product->getCategory()) ?></div>
                        <h3 class="product-title"><?= $product->getName() ?></h3>
                        <p class="product-price"><?= $product->getFormattedPrice() ?></p>
                        <p class="product-description"><?= substr($product->getDescription(), 0, 100) ?>...</p>
                        
                        <div class="product-actions">
                            <a href="/product?id=<?= $product->getId() ?>" class="btn">View Details</a>
                            
                            <?php if ($product->isInStock()): ?>
                                <span class="product-stock">In Stock (<?= $product->getStock() ?>)</span>
                            <?php else: ?>
                                <span class="product-stock out-of-stock">Out of Stock</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= $baseUrl ?>page=<?= $currentPage - 1 ?>" class="pagination-link">&laquo; Previous</a>
                <?php endif; ?>
                
                <?php
                // Show up to 5 page numbers (current, 2 before, 2 after when possible)
                $startPage = max(1, $currentPage - 2);
                $endPage = min($totalPages, $currentPage + 2);
                
                // Ensure we always show 5 links if possible
                if ($endPage - $startPage + 1 < 5 && $totalPages >= 5) {
                    if ($startPage === 1) {
                        $endPage = min($totalPages, 5);
                    } elseif ($endPage === $totalPages) {
                        $startPage = max(1, $totalPages - 4);
                    }
                }
                
                for ($i = $startPage; $i <= $endPage; $i++): 
                ?>
                    <a href="<?= $baseUrl ?>page=<?= $i ?>" class="pagination-link <?= $i === $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= $baseUrl ?>page=<?= $currentPage + 1 ?>" class="pagination-link">Next &raquo;</a>
                <?php endif; ?>
            </div>
            
            <div style="text-align: center; margin-top: 10px; color: #666; font-size: 14px;">
                Showing <?= count($products) ?> of <?= $totalProducts ?> products
            </div>
        <?php endif; ?>
    <?php endif; ?> 