<?php
/**
 * Order Tracking page template
 * 
 * Variables available:
 * $trackingSubmitted - Boolean indicating if a tracking form was submitted
 * $trackingSuccess - Boolean indicating if order was found successfully
 * $trackingError - Error message if order tracking failed
 * $order - Order details if found successfully
 */
?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Track Order</li>
        </ol>
    </nav>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h1 class="h3 mb-0 text-center">Track Your Order</h1>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($trackingError)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($trackingError) ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!$trackingSubmitted || !$trackingSuccess): ?>
                    <p class="text-center mb-4">
                        Enter your order number and email address to track your order.
                    </p>
                    
                    <form action="/orders/track" method="POST" id="order-tracking-form">
                        <div class="mb-3">
                            <label for="order_number" class="form-label">Order Number</label>
                            <input type="text" class="form-control" id="order_number" name="order_number" placeholder="e.g. ORD-12345678" required>
                            <div class="form-text">You can find this in your order confirmation email.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter the email used for the order" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Track Order
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p class="mb-2">Have an account?</p>
                        <a href="/account/orders" class="btn btn-outline-primary">View Your Orders</a>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($trackingSubmitted && $trackingSuccess && isset($order)): ?>
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h2 class="h4 mb-1">Order Found</h2>
                        <p class="text-muted">Order Number: <?= htmlspecialchars($order['order_number']) ?></p>
                    </div>
                    
                    <div class="order-tracking-details mb-4">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h3 class="h6 mb-3">Order Information</h3>
                                        <p class="mb-1"><strong>Date Placed:</strong> <?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                                        <p class="mb-1"><strong>Order Status:</strong> 
                                            <span class="badge <?= getStatusClass($order['status']) ?>">
                                                <?= ucwords(str_replace('_', ' ', $order['status'])) ?>
                                            </span>
                                        </p>
                                        <p class="mb-1"><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                                        <p class="mb-0"><strong>Total:</strong> $<?= number_format($order['total'], 2) ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body">
                                        <h3 class="h6 mb-3">Shipping Information</h3>
                                        <?php if (!empty($order['tracking_number'])): ?>
                                        <p class="mb-1"><strong>Carrier:</strong> <?= htmlspecialchars($order['shipping_carrier']) ?></p>
                                        <p class="mb-1"><strong>Tracking Number:</strong> <span class="text-primary"><?= htmlspecialchars($order['tracking_number']) ?></span></p>
                                        <?php if (!empty($order['carrier_url'])): ?>
                                        <p class="mb-0">
                                            <a href="<?= htmlspecialchars($order['carrier_url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-external-link-alt me-1"></i> Track with Carrier
                                            </a>
                                        </p>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        <p class="mb-0">Tracking information will be available once your order ships.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Timeline -->
                        <div class="order-timeline mb-4">
                            <h3 class="h5 mb-3">Order Status Timeline</h3>
                            <div class="timeline">
                                <?php 
                                $statuses = [
                                    'pending' => [
                                        'icon' => 'fa-clock',
                                        'title' => 'Order Received',
                                        'desc' => 'We\'ve received your order and are processing it.',
                                        'date' => $order['created_at']
                                    ],
                                    'processing' => [
                                        'icon' => 'fa-spinner',
                                        'title' => 'Order Processing',
                                        'desc' => 'Your order is being processed and prepared for shipping.',
                                        'date' => $order['processing_at'] ?? null
                                    ],
                                    'shipped' => [
                                        'icon' => 'fa-shipping-fast',
                                        'title' => 'Order Shipped',
                                        'desc' => 'Your order has been shipped and is on its way to you.',
                                        'date' => $order['shipped_at'] ?? null
                                    ],
                                    'delivered' => [
                                        'icon' => 'fa-box-open',
                                        'title' => 'Order Delivered',
                                        'desc' => 'Your order has been delivered.',
                                        'date' => $order['delivered_at'] ?? null
                                    ],
                                    'cancelled' => [
                                        'icon' => 'fa-times-circle',
                                        'title' => 'Order Cancelled',
                                        'desc' => 'This order has been cancelled.',
                                        'date' => $order['cancelled_at'] ?? null
                                    ]
                                ];
                                
                                $currentStatus = $order['status'];
                                $reachedCurrent = false;
                                
                                foreach ($statuses as $status => $data):
                                    // Skip cancelled status if order isn't cancelled
                                    if ($status === 'cancelled' && $currentStatus !== 'cancelled') continue;
                                    
                                    // Skip other statuses if order is cancelled
                                    if ($currentStatus === 'cancelled' && $status !== 'pending' && $status !== 'cancelled') continue;
                                
                                    $isActive = false;
                                    $isPast = false;
                                    
                                    if ($status === $currentStatus) {
                                        $isActive = true;
                                        $reachedCurrent = true;
                                    } else if (!$reachedCurrent) {
                                        $isPast = true;
                                    }
                                    
                                    $timelineClass = $isActive ? 'timeline-active' : ($isPast ? 'timeline-past' : '');
                                ?>
                                <div class="timeline-item <?= $timelineClass ?>">
                                    <div class="timeline-icon">
                                        <i class="fas <?= $data['icon'] ?>"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h4 class="timeline-title"><?= $data['title'] ?></h4>
                                        <p class="timeline-desc"><?= $data['desc'] ?></p>
                                        <?php if ($data['date'] && ($isPast || $isActive)): ?>
                                        <small class="timeline-date"><?= date('M d, Y h:i A', strtotime($data['date'])) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Order Items -->
                        <h3 class="h5 mb-3">Order Items</h3>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;"></th>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                                                 class="img-fluid rounded" 
                                                 style="max-width: 50px; max-height: 50px;"
                                                 alt="<?= htmlspecialchars($item['name']) ?>"
                                                 onerror="this.src='/assets/images/placeholders/product.jpg'">
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                                            <?php if (!empty($item['options'])): ?>
                                            <small class="text-muted">
                                                <?php foreach ($item['options'] as $option => $value): ?>
                                                <div><?= htmlspecialchars($option) ?>: <?= htmlspecialchars($value) ?></div>
                                                <?php endforeach; ?>
                                            </small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= $item['quantity'] ?></td>
                                        <td class="text-end">$<?= number_format($item['price'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                        <td class="text-end">$<?= number_format($order['subtotal'], 2) ?></td>
                                    </tr>
                                    <?php if ($order['discount'] > 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Discount</strong></td>
                                        <td class="text-end">-$<?= number_format($order['discount'], 2) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Shipping</strong></td>
                                        <td class="text-end">$<?= number_format($order['shipping_fee'], 2) ?></td>
                                    </tr>
                                    <?php if ($order['tax'] > 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax</strong></td>
                                        <td class="text-end">$<?= number_format($order['tax'], 2) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                                        <td class="text-end fw-bold">$<?= number_format($order['total'], 2) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="/orders/track" class="btn btn-outline-primary">
                                <i class="fas fa-search me-2"></i>Track Another Order
                            </a>
                            <?php if ($order['status'] !== 'delivered' && $order['status'] !== 'cancelled'): ?>
                            <a href="/contact?subject=Order%20Inquiry:%20<?= urlencode($order['order_number']) ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-question-circle me-2"></i>Help with Order
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Need Help Section -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h3 class="h5 mb-3">Need Help?</h3>
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-light me-3">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="h6 mb-1">Contact Us</h4>
                                    <p class="mb-0 small">Send us an email if you have any questions about your order.</p>
                                    <a href="/contact" class="btn btn-link p-0 mt-1">Contact Support</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-light me-3">
                                    <i class="fas fa-store text-primary"></i>
                                </div>
                                <div>
                                    <h4 class="h6 mb-1">Store Locations</h4>
                                    <p class="mb-0 small">Find our store locations if you prefer to pick up your order.</p>
                                    <a href="/stores" class="btn btn-link p-0 mt-1">Find Stores</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
        margin-left: 10px;
        border-left: 2px solid #eaeaea;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        color: #6c757d;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-icon {
        position: absolute;
        left: -42px;
        top: 0;
        width: 24px;
        height: 24px;
        background-color: #eaeaea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #888;
    }
    
    .timeline-active .timeline-icon {
        background-color: #0d6efd;
        color: white;
    }
    
    .timeline-past .timeline-icon {
        background-color: #198754;
        color: white;
    }
    
    .timeline-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .timeline-active .timeline-title {
        color: #0d6efd;
    }
    
    .timeline-past .timeline-title {
        color: #198754;
    }
    
    .timeline-desc {
        font-size: 0.875rem;
        margin-bottom: 5px;
    }
    
    .timeline-date {
        display: block;
        font-size: 0.75rem;
        color: #adb5bd;
    }
</style>

<?php
/**
 * Helper function to get the Bootstrap class for order status badges
 */
function getStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'bg-warning text-dark';
        case 'processing':
            return 'bg-info text-dark';
        case 'shipped':
            return 'bg-primary';
        case 'delivered':
            return 'bg-success';
        case 'cancelled':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}
?> 