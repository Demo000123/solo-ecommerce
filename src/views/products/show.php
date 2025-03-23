<div class="product-detail">
    <div>
        <img src="<?= $product->getImage() ?>" alt="<?= $product->getName() ?>" class="product-img">
    </div>
    <div class="product-info">
        <h1 class="product-title"><?= $product->getName() ?></h1>
        <p class="product-price"><?= $product->getFormattedPrice() ?></p>
        
        <div class="product-description">
            <?= $product->getDescription() ?>
        </div>
        
        <div>
            <p style="margin-bottom: 15px;">
                <strong>Category:</strong> <?= ucfirst($product->getCategory()) ?>
            </p>
            
            <p style="margin-bottom: 20px;">
                <strong>Availability:</strong> 
                <?php if ($product->isInStock()): ?>
                    <span style="color: green;">In Stock (<?= $product->getStock() ?> available)</span>
                <?php else: ?>
                    <span style="color: red;">Out of Stock</span>
                <?php endif; ?>
            </p>
        </div>
        
        <?php if ($product->isInStock()): ?>
            <form action="/cart/add" method="POST" style="display: flex; align-items: center; gap: 10px; margin-top: 20px;">
                <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                <div>
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product->getStock() ?>" style="width: 60px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <button type="submit" class="btn btn-secondary">Add to Cart</button>
            </form>
        <?php else: ?>
            <button disabled class="btn" style="opacity: 0.5; margin-top: 20px;">Out of Stock</button>
        <?php endif; ?>
        
        <a href="/products" class="btn" style="margin-top: 30px; display: inline-block;">Back to Products</a>
    </div>
</div> 