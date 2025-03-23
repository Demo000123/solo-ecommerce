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

<div class="product-container container">
    <div class="product-filters">
        <!-- Category filters -->
        <div class="filter-section">
            <h3 class="filter-title">Danh mục</h3>
            <div class="category-filter">
                <a href="/products" class="category-button <?= empty($_GET['category']) ? 'active' : '' ?>">
                    Tất cả
                </a>
                <?php foreach($categories ?? [] as $category): ?>
                    <a href="/products?category=<?= $category['id'] ?>" 
                       class="category-button <?= isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Price range filter -->
        <div class="filter-section">
            <h3 class="filter-title">Giá</h3>
            <div class="price-filter">
                <form method="GET" action="/products" class="price-range-form">
                    <?php if(!empty($_GET['category'])): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['sort'])): ?>
                        <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>">
                    <?php endif; ?>
                    
                    <div class="price-inputs">
                        <div class="price-input-group">
                            <label for="min_price">Từ</label>
                            <input type="number" id="min_price" name="min_price" 
                                   value="<?= isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : '' ?>" 
                                   placeholder="0đ">
                        </div>
                        <div class="price-input-group">
                            <label for="max_price">Đến</label>
                            <input type="number" id="max_price" name="max_price" 
                                   value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>" 
                                   placeholder="10.000.000đ">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline btn-sm">Áp dụng</button>
                </form>
            </div>
        </div>
        
        <!-- Availability filter -->
        <div class="filter-section">
            <h3 class="filter-title">Tình trạng</h3>
            <div class="availability-filter">
                <form action="/products" method="GET">
                    <?php if(!empty($_GET['category'])): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['sort'])): ?>
                        <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['min_price'])): ?>
                        <input type="hidden" name="min_price" value="<?= htmlspecialchars($_GET['min_price']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['max_price'])): ?>
                        <input type="hidden" name="max_price" value="<?= htmlspecialchars($_GET['max_price']) ?>">
                    <?php endif; ?>
                    
                    <div class="filter-checkbox">
                        <input type="checkbox" id="in_stock" name="in_stock" value="1" 
                               <?= isset($_GET['in_stock']) ? 'checked' : '' ?>>
                        <label for="in_stock">Còn hàng</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="on_sale" name="on_sale" value="1" 
                               <?= isset($_GET['on_sale']) ? 'checked' : '' ?>>
                        <label for="on_sale">Đang giảm giá</label>
                    </div>
                    <button type="submit" class="btn btn-outline btn-sm">Áp dụng</button>
                </form>
            </div>
        </div>
        
        <!-- Clear all filters -->
        <a href="/products" class="clear-filters">Xóa bộ lọc</a>
    </div>
    
    <div class="product-right-column">
        <!-- Search and sort bar -->
        <div class="product-controls">
            <div class="search-container">
                <form action="/products" method="GET" class="search-form">
                    <?php if(!empty($_GET['category'])): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
                    <?php endif; ?>
                    <div class="search-input-container">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." 
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" 
                               class="search-input">
                        <button type="submit" class="search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="sort-options">
                <label class="sort-label">Sắp xếp theo:</label>
                <form id="sort-form" method="GET" action="/products">
                    <?php if(!empty($_GET['category'])): ?>
                        <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['search'])): ?>
                        <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['min_price'])): ?>
                        <input type="hidden" name="min_price" value="<?= htmlspecialchars($_GET['min_price']) ?>">
                    <?php endif; ?>
                    <?php if(!empty($_GET['max_price'])): ?>
                        <input type="hidden" name="max_price" value="<?= htmlspecialchars($_GET['max_price']) ?>">
                    <?php endif; ?>
                    <?php if(isset($_GET['in_stock'])): ?>
                        <input type="hidden" name="in_stock" value="1">
                    <?php endif; ?>
                    <?php if(isset($_GET['on_sale'])): ?>
                        <input type="hidden" name="on_sale" value="1">
                    <?php endif; ?>
                    
                    <select name="sort" id="sort-select" class="sort-select" onchange="document.getElementById('sort-form').submit()">
                        <option value="default" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'default') ? 'selected' : '' ?>>
                            Mặc định
                        </option>
                        <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>
                            Giá: Thấp đến cao
                        </option>
                        <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>
                            Giá: Cao đến thấp
                        </option>
                        <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>
                            Mới nhất
                        </option>
                        <option value="bestselling" <?= (isset($_GET['sort']) && $_GET['sort'] == 'bestselling') ? 'selected' : '' ?>>
                            Bán chạy nhất
                        </option>
                    </select>
                </form>
            </div>
        </div>
        
        <!-- Product results -->
        <?php if (empty($products)): ?>
            <div class="empty-results">
                <div class="empty-icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <h2>Không tìm thấy sản phẩm</h2>
                <p>Chúng tôi không tìm thấy sản phẩm nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                <a href="/products" class="btn btn-primary">Xem tất cả sản phẩm</a>
            </div>
        <?php else: ?>
            <!-- Active filters display -->
            <?php if (!empty($_GET['search']) || !empty($_GET['category']) || !empty($_GET['min_price']) || 
                      !empty($_GET['max_price']) || isset($_GET['in_stock']) || isset($_GET['on_sale'])): ?>
                <div class="active-filters">
                    <span class="filter-label">Bộ lọc hiện tại:</span>
                    <div class="filter-tags">
                        <?php if (!empty($_GET['search'])): ?>
                            <div class="filter-tag">
                                <span>Tìm kiếm: <?= htmlspecialchars($_GET['search']) ?></span>
                                <a href="<?= removeQueryParam('search') ?>" class="remove-tag">×</a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($_GET['category'])): 
                            $categoryName = '';
                            foreach($categories ?? [] as $cat) {
                                if ($cat['id'] == $_GET['category']) {
                                    $categoryName = $cat['name'];
                                    break;
                                }
                            }
                        ?>
                            <div class="filter-tag">
                                <span>Danh mục: <?= htmlspecialchars($categoryName) ?></span>
                                <a href="<?= removeQueryParam('category') ?>" class="remove-tag">×</a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($_GET['min_price']) || !empty($_GET['max_price'])): ?>
                            <div class="filter-tag">
                                <span>Giá: 
                                    <?php 
                                    if (!empty($_GET['min_price']) && !empty($_GET['max_price'])) {
                                        echo number_format($_GET['min_price'], 0, ',', '.') . 'đ - ' . 
                                             number_format($_GET['max_price'], 0, ',', '.') . 'đ';
                                    } elseif (!empty($_GET['min_price'])) {
                                        echo 'Từ ' . number_format($_GET['min_price'], 0, ',', '.') . 'đ';
                                    } else {
                                        echo 'Đến ' . number_format($_GET['max_price'], 0, ',', '.') . 'đ';
                                    }
                                    ?>
                                </span>
                                <a href="<?= removeQueryParams(['min_price', 'max_price']) ?>" class="remove-tag">×</a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['in_stock'])): ?>
                            <div class="filter-tag">
                                <span>Còn hàng</span>
                                <a href="<?= removeQueryParam('in_stock') ?>" class="remove-tag">×</a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['on_sale'])): ?>
                            <div class="filter-tag">
                                <span>Đang giảm giá</span>
                                <a href="<?= removeQueryParam('on_sale') ?>" class="remove-tag">×</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="product-count">
                Hiển thị <?= count($products) ?> sản phẩm <?= isset($totalProducts) ? 'trên ' . $totalProducts : '' ?>
            </div>
            
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card" data-category="<?= $product['category_id'] ?>">
                        <div class="product-img-container">
                            <a href="/products/<?= $product['id'] ?>">
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-img">
                            </a>
                            <?php if ($product['stock'] <= 0): ?>
                                <span class="product-badge out-of-stock">Hết hàng</span>
                            <?php elseif ($product['stock'] <= 5): ?>
                                <span class="product-badge limited-stock">Sắp hết hàng</span>
                            <?php endif; ?>
                            
                            <div class="product-actions-overlay">
                                <a href="/products/<?= $product['id'] ?>" class="quick-view-btn" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($product['stock'] > 0): ?>
                                    <form action="/cart/add" method="POST" class="add-to-cart-form">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="add-to-cart-btn" title="Thêm vào giỏ hàng">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <button type="button" class="wishlist-toggle" data-product-id="<?= $product['id'] ?>" title="Thêm vào danh sách yêu thích">
                                    <i class="<?= isset($product['in_wishlist']) && $product['in_wishlist'] ? 'fas' : 'far' ?> fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-category"><?= htmlspecialchars($product['category_name'] ?? 'Khác') ?></div>
                            <h3 class="product-title">
                                <a href="/products/<?= $product['id'] ?>"><?= htmlspecialchars($product['name']) ?></a>
                            </h3>
                            <div class="product-price-container">
                                <p class="product-price"><?= number_format($product['price'], 0, ',', '.') ?> ₫</p>
                                <?php if (isset($product['original_price']) && $product['original_price'] > $product['price']): ?>
                                    <p class="product-original-price"><?= number_format($product['original_price'], 0, ',', '.') ?> ₫</p>
                                    <p class="product-discount">-<?= round((($product['original_price'] - $product['price']) / $product['original_price']) * 100) ?>%</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="pagination">
                    <?php
                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $queryParams = $_GET;
                    
                    // Previous page link
                    if ($currentPage > 1) {
                        $queryParams['page'] = $currentPage - 1;
                        echo '<a href="?' . http_build_query($queryParams) . '" class="pagination-link">&laquo; Trước</a>';
                    }
                    
                    // Page numbers
                    $start = max(1, $currentPage - 2);
                    $end = min($totalPages, $currentPage + 2);
                    
                    for ($i = $start; $i <= $end; $i++) {
                        $queryParams['page'] = $i;
                        echo '<a href="?' . http_build_query($queryParams) . '" class="pagination-link ' . 
                             ($i == $currentPage ? 'active' : '') . '">' . $i . '</a>';
                    }
                    
                    // Next page link
                    if ($currentPage < $totalPages) {
                        $queryParams['page'] = $currentPage + 1;
                        echo '<a href="?' . http_build_query($queryParams) . '" class="pagination-link">Sau &raquo;</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Helper functions for query string manipulation -->
<?php
function removeQueryParam($param) {
    $params = $_GET;
    unset($params[$param]);
    return '?' . http_build_query($params);
}

function removeQueryParams($paramList) {
    $params = $_GET;
    foreach ($paramList as $param) {
        unset($params[$param]);
    }
    return '?' . http_build_query($params);
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Wishlist toggle functionality
        const wishlistButtons = document.querySelectorAll('.wishlist-toggle');
        
        wishlistButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                
                // Send AJAX request to toggle wishlist status
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const icon = this.querySelector('i');
                        
                        if (data.in_wishlist) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                        }
                        
                        // Update wishlist count in header if it exists
                        const wishlistCountEl = document.querySelector('.wishlist-count');
                        if (wishlistCountEl) {
                            wishlistCountEl.textContent = data.wishlist_count;
                        }
                        
                        // Show message
                        showMessage(data.in_wishlist ? 'Đã thêm vào danh sách yêu thích' : 'Đã xóa khỏi danh sách yêu thích', 'success');
                    } else {
                        // Show error message
                        showMessage('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
                });
            });
        });
        
        // Helper function to show messages
        function showMessage(message, type) {
            const messageEl = document.createElement('div');
            messageEl.className = `message ${type}`;
            messageEl.textContent = message;
            
            document.body.appendChild(messageEl);
            
            // Show the message
            setTimeout(() => {
                messageEl.classList.add('show');
            }, 10);
            
            // Remove the message after 3 seconds
            setTimeout(() => {
                messageEl.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(messageEl);
                }, 300);
            }, 3000);
        }
    });
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