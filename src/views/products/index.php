<div class="search-container" style="margin-bottom: 30px;">
    <form action="/products" method="GET" style="display: flex; max-width: 500px;">
        <input type="text" name="search" placeholder="Search products..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px 0 0 8px; border-right: none;">
        <button type="submit" class="btn" style="border-radius: 0 8px 8px 0;">Search</button>
    </form>
</div>

<div class="category-filter" style="margin-bottom: 30px;">
    <a href="/products" class="btn" style="margin-right: 10px; <?= !isset($_GET['category']) ? 'background-color: var(--accent-color);' : '' ?>">All</a>
    <a href="/products?category=electronics" class="btn" style="margin-right: 10px; <?= ($_GET['category'] ?? '') === 'electronics' ? 'background-color: var(--accent-color);' : '' ?>">Electronics</a>
    <a href="/products?category=clothing" class="btn" style="margin-right: 10px; <?= ($_GET['category'] ?? '') === 'clothing' ? 'background-color: var(--accent-color);' : '' ?>">Clothing</a>
    <a href="/products?category=home" class="btn" style="<?= ($_GET['category'] ?? '') === 'home' ? 'background-color: var(--accent-color);' : '' ?>">Home</a>
</div>

<?php if (empty($products)): ?>
    <div style="text-align: center; padding: 40px 0;">
        <h2>No products found</h2>
        <p>Try a different search term or category.</p>
    </div>
<?php else: ?>
    <div class="grid">
        <?php foreach ($products as $product): ?>
            <div class="card">
                <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>" class="card-img">
                <div class="card-body">
                    <h3 class="card-title"><?= $product->getName() ?></h3>
                    <p class="card-price"><?= $product->getFormattedPrice() ?></p>
                    <p style="margin-bottom: 15px;"><?= substr($product->getDescription(), 0, 100) ?>...</p>
                    <div style="display: flex; justify-content: space-between;">
                        <a href="/product?id=<?= $product->getId() ?>" class="btn">View Details</a>
                        <?php if ($product->isInStock()): ?>
                            <form action="/cart/add" method="POST" style="display: inline;">
                                <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-secondary">Add to Cart</button>
                            </form>
                        <?php else: ?>
                            <button disabled class="btn" style="opacity: 0.5;">Out of Stock</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?> 