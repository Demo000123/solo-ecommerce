<?php if (empty($cartItems)): ?>
    <div style="text-align: center; padding: 50px 0;">
        <h2>Your cart is empty</h2>
        <p style="margin-bottom: 20px;">Add some products to your cart to begin shopping.</p>
        <a href="/products" class="btn">Browse Products</a>
    </div>
<?php else: ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td style="display: flex; align-items: center; gap: 15px;">
                        <img src="<?= $item['product']->getImage() ?>" alt="<?= $item['product']->getName() ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                        <div>
                            <h3 style="margin: 0;"><?= $item['product']->getName() ?></h3>
                            <p style="margin: 5px 0 0; color: #666;"><?= ucfirst($item['product']->getCategory()) ?></p>
                        </div>
                    </td>
                    <td><?= $item['product']->getFormattedPrice() ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>$<?= number_format($item['subtotal'], 2) ?></td>
                    <td>
                        <form action="/cart/remove" method="POST">
                            <input type="hidden" name="product_id" value="<?= $item['product']->getId() ?>">
                            <button type="submit" class="btn btn-secondary" style="padding: 6px 12px;">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="cart-total">
        <div style="font-size: 1.4rem; margin-bottom: 30px;">
            <strong>Total:</strong> $<?= number_format($totalPrice, 2) ?>
        </div>
        
        <div style="display: flex; justify-content: flex-end; gap: 15px;">
            <a href="/products" class="btn">Continue Shopping</a>
            <a href="/checkout" class="btn btn-secondary">Proceed to Checkout</a>
        </div>
    </div>
<?php endif; ?> 