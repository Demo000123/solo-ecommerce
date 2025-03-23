<?php
// Get data from the controller
$order = $order ?? null;
$orderItems = $orderItems ?? [];

// Redirect if no order data
if (!$order) {
    header('Location: /');
    exit;
}
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
        </div>
        <h1 class="mb-3">Thank You for Your Order!</h1>
        <p class="lead">Your order has been successfully placed and is being processed.</p>
        <p>Order Reference: <strong>#<?= str_pad($order['id'], 8, '0', STR_PAD_LEFT) ?></strong></p>
        <p>A confirmation email has been sent to <strong><?= htmlspecialchars($order['email']) ?></strong></p>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Order Information</h6>
                            <p class="mb-1">Order Number: <strong>#<?= str_pad($order['id'], 8, '0', STR_PAD_LEFT) ?></strong></p>
                            <p class="mb-1">Date: <strong><?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></strong></p>
                            <p class="mb-1">Payment Method: <strong><?= htmlspecialchars($order['payment_method']) ?></strong></p>
                            <p class="mb-0">Order Status: 
                                <span class="badge bg-info"><?= htmlspecialchars($order['status']) ?></span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Customer Information</h6>
                            <p class="mb-1">Name: <strong><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></strong></p>
                            <p class="mb-1">Email: <strong><?= htmlspecialchars($order['email']) ?></strong></p>
                            <p class="mb-0">Phone: <strong><?= htmlspecialchars($order['phone']) ?></strong></p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Shipping Address</h6>
                            <address>
                                <?= htmlspecialchars($order['address_line1']) ?><br>
                                <?php if (!empty($order['address_line2'])): ?>
                                    <?= htmlspecialchars($order['address_line2']) ?><br>
                                <?php endif; ?>
                                <?= htmlspecialchars($order['city']) ?>, 
                                <?= htmlspecialchars($order['state']) ?> <?= htmlspecialchars($order['zip_code']) ?><br>
                                <?= htmlspecialchars($order['country']) ?>
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Shipping Method</h6>
                            <p><?= htmlspecialchars($order['shipping_method']) ?></p>
                            
                            <?php if (!empty($order['tracking_number'])): ?>
                                <p class="mb-0">Tracking Number: <strong><?= htmlspecialchars($order['tracking_number']) ?></strong></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="<?= '/uploads/products/' . $item['image'] ?>" 
                                                         alt="<?= htmlspecialchars($item['name']) ?>" class="img-thumbnail me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                                    <?php if (!empty($item['options'])): ?>
                                                        <small class="text-muted">
                                                            <?php
                                                            $options = json_decode($item['options'], true);
                                                            if (is_array($options)) {
                                                                foreach ($options as $key => $value) {
                                                                    echo htmlspecialchars($key) . ': ' . htmlspecialchars($value) . '<br>';
                                                                }
                                                            }
                                                            ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= number_format($item['price'], 0) ?></td>
                                        <td class="text-center"><?= $item['quantity'] ?></td>
                                        <td class="text-end"><?= number_format($item['price'] * $item['quantity'], 0) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal:</td>
                                    <td class="text-end"><?= number_format($order['subtotal'], 0) ?></td>
                                </tr>
                                <?php if ($order['discount'] > 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-end">Discount:</td>
                                        <td class="text-end text-danger">-<?= number_format($order['discount'], 0) ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="3" class="text-end">Shipping:</td>
                                    <td class="text-end"><?= number_format($order['shipping_fee'], 0) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold"><?= number_format($order['total'], 0) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <?php if (!empty($order['notes'])): ?>
                        <div class="mt-3">
                            <h6 class="fw-bold">Order Notes:</h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($order['payment_method'] === 'bank_transfer' && $order['payment_status'] === 'pending'): ?>
                <div class="card mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">Payment Instructions</h5>
                    </div>
                    <div class="card-body">
                        <p>Please complete your payment by transferring the total amount to our bank account:</p>
                        <div class="mb-3">
                            <table class="table table-striped">
                                <tr>
                                    <td><strong>Bank:</strong></td>
                                    <td>National Bank</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Name:</strong></td>
                                    <td>Solo E-commerce</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Number:</strong></td>
                                    <td>1234567890</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td><?= number_format($order['total'], 0) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Reference:</strong></td>
                                    <td>ORDER #<?= str_pad($order['id'], 8, '0', STR_PAD_LEFT) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Your order will be processed once we receive your payment. Please include your order number in the payment reference.
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="/" class="btn btn-primary me-2">Continue Shopping</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/account/orders/<?= $order['id'] ?>" class="btn btn-outline-primary">View Order Details</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 