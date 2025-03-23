<link rel="stylesheet" href="/public/css/products.css">

<div class="product-detail-container">
    <nav class="breadcrumb" style="margin-bottom: 30px; font-size: 14px; color: #666;">
        <a href="/" style="color: #666; text-decoration: none;">Home</a> &raquo;
        <a href="/products" style="color: #666; text-decoration: none;">Products</a> &raquo;
        <a href="/products?category=<?= $product->getCategory() ?>" style="color: #666; text-decoration: none;"><?= ucfirst($product->getCategory()) ?></a> &raquo;
        <span style="color: #333;"><?= $product->getName() ?></span>
    </nav>

    <div class="product-detail">
        <div class="product-images">
            <div class="main-image-container" style="position: relative; border-radius: var(--border-radius); overflow: hidden; box-shadow: var(--box-shadow);">
                <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>" class="product-img" style="width: 100%;">
                <?php if (!$product->isInStock()): ?>
                    <div style="position: absolute; top: 20px; right: 20px; background-color: var(--secondary-color); color: white; padding: 8px 16px; border-radius: 20px; font-weight: bold;">
                        Out of Stock
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="product-info">
            <h1 class="product-title"><?= $product->getName() ?></h1>
            <p class="product-price"><?= $product->getFormattedPrice() ?></p>
            
            <div class="product-meta" style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                <p style="margin-bottom: 10px;">
                    <strong>Category:</strong> <a href="/products?category=<?= $product->getCategory() ?>" style="color: var(--primary-color); text-decoration: none;"><?= ucfirst($product->getCategory()) ?></a>
                </p>
                
                <p>
                    <strong>Availability:</strong> 
                    <?php if ($product->isInStock()): ?>
                        <span style="color: #28a745; font-weight: 500;">In Stock (<?= $product->getStock() ?> available)</span>
                    <?php else: ?>
                        <span style="color: #dc3545; font-weight: 500;">Out of Stock</span>
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="product-description" style="line-height: 1.8; margin-bottom: 30px;">
                <h3 style="margin-bottom: 15px; font-size: 18px;">Description</h3>
                <?= $product->getDescription() ?>
            </div>
            
            <?php if ($product->isInStock()): ?>
                <form action="/cart/add" method="POST" style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; background-color: #f8f9fa; padding: 20px; border-radius: 8px;">
                    <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                    <div>
                        <label for="quantity" style="display: block; margin-bottom: 5px; font-weight: 500;">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product->getStock() ?>" style="width: 80px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <button type="submit" class="btn btn-secondary" style="flex-grow: 1; height: 45px; font-size: 16px;">Add to Cart</button>
                </form>
            <?php else: ?>
                <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; color: #dc3545;">
                    <p style="margin-bottom: 10px; font-weight: 500;">This product is currently out of stock.</p>
                    <p>Please check back later or browse similar products below.</p>
                </div>
            <?php endif; ?>
            
            <div class="product-actions" style="display: flex; gap: 15px; margin-top: 30px;">
                <a href="/products" class="btn" style="flex: 1; text-align: center;">Back to Products</a>
                <?php if (count($relatedProducts) > 0): ?>
                    <a href="/products?category=<?= $product->getCategory() ?>" class="btn" style="flex: 1; text-align: center;">View Similar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (count($relatedProducts) > 0): ?>
        <div class="related-products" style="margin-top: 60px;">
            <h2 style="margin-bottom: 20px; font-size: 24px; padding-bottom: 10px; border-bottom: 1px solid #eee;">Related Products</h2>
            
            <div class="product-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <div class="product-card">
                        <div class="product-img-container">
                            <img src="<?= $relatedProduct->getImage() ?>" alt="<?= $relatedProduct->getName() ?>" class="product-img">
                            <?php if (!$relatedProduct->isInStock()): ?>
                                <span class="product-badge">Out of Stock</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-body">
                            <div class="product-category"><?= ucfirst($relatedProduct->getCategory()) ?></div>
                            <h3 class="product-title"><?= $relatedProduct->getName() ?></h3>
                            <p class="product-price"><?= $relatedProduct->getFormattedPrice() ?></p>
                            <p class="product-description"><?= substr($relatedProduct->getDescription(), 0, 100) ?>...</p>
                            
                            <div class="product-actions">
                                <a href="/product?id=<?= $relatedProduct->getId() ?>" class="btn">View Details</a>
                                
                                <?php if ($relatedProduct->isInStock()): ?>
                                    <span class="product-stock">In Stock (<?= $relatedProduct->getStock() ?>)</span>
                                <?php else: ?>
                                    <span class="product-stock out-of-stock">Out of Stock</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity input validation
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            quantityInput.addEventListener('change', function() {
                const max = parseInt(this.getAttribute('max'));
                const value = parseInt(this.value);
                
                if (value > max) {
                    this.value = max;
                    alert('Sorry, we only have ' + max + ' items in stock.');
                }
                
                if (value < 1) {
                    this.value = 1;
                }
            });
        }
    });
</script> 