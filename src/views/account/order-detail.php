<?php
// Order detail page
require_once __DIR__ . '/../layouts/header.php';

// Check if user is logged in
$userLoggedIn = isset($user) && !empty($user);

// Get order ID from URL
$orderId = isset($_GET['id']) ? $_GET['id'] : null;

// Get order details if user is logged in and order ID is provided
$order = null;
if ($userLoggedIn && $orderId) {
    // In a real application, this would fetch from the database
    // Example mock data
    $order = [
        'id' => 'DH100001',
        'date' => '2023-06-15 14:30:45',
        'status' => 'completed',
        'total' => 1250000,
        'subtotal' => 1200000,
        'shipping_fee' => 50000,
        'discount' => 0,
        'payment_method' => 'COD',
        'payment_status' => 'paid',
        'shipping_method' => 'Standard',
        'shipping_address' => [
            'fullname' => 'Nguyễn Văn A',
            'phone' => '0912345678',
            'address' => '123 Đường ABC, Phường XYZ, Quận 1, TP. Hồ Chí Minh'
        ],
        'items' => [
            [
                'id' => 1,
                'product_id' => 101,
                'name' => 'Áo thun unisex basic',
                'image' => '/public/images/products/tshirt.jpg',
                'price' => 250000,
                'quantity' => 2,
                'variant' => 'Màu trắng / Size M',
                'subtotal' => 500000
            ],
            [
                'id' => 2,
                'product_id' => 102,
                'name' => 'Quần jeans nam slim fit',
                'image' => '/public/images/products/jeans.jpg',
                'price' => 450000,
                'quantity' => 1,
                'variant' => 'Màu xanh đậm / Size 32',
                'subtotal' => 450000
            ],
            [
                'id' => 3,
                'product_id' => 103,
                'name' => 'Giày sneaker',
                'image' => '/public/images/products/shoes.jpg',
                'price' => 250000,
                'quantity' => 1,
                'variant' => 'Màu đỏ / Size 38',
                'subtotal' => 250000
            ]
        ],
        'timeline' => [
            [
                'status' => 'pending',
                'date' => '2023-06-15 14:30:45',
                'description' => 'Đơn hàng đã được tạo'
            ],
            [
                'status' => 'processing',
                'date' => '2023-06-15 15:45:22',
                'description' => 'Đơn hàng đã được xác nhận'
            ],
            [
                'status' => 'shipping',
                'date' => '2023-06-16 09:15:30',
                'description' => 'Đơn hàng đang được giao đến bạn'
            ],
            [
                'status' => 'completed',
                'date' => '2023-06-18 10:22:15',
                'description' => 'Đơn hàng đã được giao thành công'
            ]
        ]
    ];
}

// Helper function to get status text
function getOrderStatusText($status) {
    switch ($status) {
        case 'pending':
            return 'Chờ xác nhận';
        case 'processing':
            return 'Đang xử lý';
        case 'shipping':
            return 'Đang giao hàng';
        case 'completed':
            return 'Đã hoàn thành';
        case 'cancelled':
            return 'Đã hủy';
        default:
            return 'Không xác định';
    }
}

// Helper function to get status class
function getOrderStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'status-pending';
        case 'processing':
            return 'status-processing';
        case 'shipping':
            return 'status-shipping';
        case 'completed':
            return 'status-completed';
        case 'cancelled':
            return 'status-cancelled';
        default:
            return '';
    }
}

// Helper function to get payment status text
function getPaymentStatusText($status) {
    switch ($status) {
        case 'pending':
            return 'Chờ thanh toán';
        case 'paid':
            return 'Đã thanh toán';
        case 'refunded':
            return 'Đã hoàn tiền';
        default:
            return 'Không xác định';
    }
}

// Helper function to get payment status class
function getPaymentStatusClass($status) {
    switch ($status) {
        case 'pending':
            return 'status-pending';
        case 'paid':
            return 'status-completed';
        case 'refunded':
            return 'status-processing';
        default:
            return '';
    }
}

// Get data from the controller
$order = $order ?? null;
$orderItems = $orderItems ?? [];
$orderHistory = $orderHistory ?? [];
$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? null;

// Clear session messages
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Check if the order exists
if (!$order) {
    header('Location: /account/orders');
    exit;
}

// Order status colors
$statusColors = [
    'pending' => 'bg-warning',
    'processing' => 'bg-info',
    'shipped' => 'bg-primary',
    'delivered' => 'bg-success',
    'cancelled' => 'bg-danger',
    'refunded' => 'bg-secondary'
];

// Payment status colors
$paymentStatusColors = [
    'pending' => 'bg-warning',
    'paid' => 'bg-success',
    'failed' => 'bg-danger',
    'refunded' => 'bg-info'
];

// Format order number with leading zeros
$orderNumber = str_pad($order['id'], 8, '0', STR_PAD_LEFT);
?>

<link rel="stylesheet" href="/public/css/account.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Chi tiết đơn hàng</h1>
        <div class="breadcrumbs">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <a href="/account">Tài khoản</a>
            <span class="separator">/</span>
            <a href="/account/orders">Đơn hàng</a>
            <span class="separator">/</span>
            <span class="current">Chi tiết</span>
        </div>
    </div>
</div>

<div class="account-container container">
    <?php if (!$userLoggedIn): ?>
        <div class="not-authenticated">
            <p>Vui lòng <a href="/account">đăng nhập</a> để xem chi tiết đơn hàng.</p>
        </div>
    <?php elseif (!$order): ?>
        <div class="order-not-found">
            <div class="not-found-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h2>Không tìm thấy đơn hàng</h2>
            <p>Đơn hàng bạn đang tìm kiếm không tồn tại hoặc bạn không có quyền truy cập.</p>
            <div class="not-found-actions">
                <a href="/account/orders" class="btn btn-primary">Quay lại danh sách đơn hàng</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Account Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">My Account</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="/account/dashboard" class="list-group-item list-group-item-action">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="/account/profile" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i> My Profile
                        </a>
                        <a href="/account/orders" class="list-group-item list-group-item-action active">
                            <i class="fas fa-shopping-bag me-2"></i> My Orders
                        </a>
                        <a href="/account/addresses" class="list-group-item list-group-item-action">
                            <i class="fas fa-map-marker-alt me-2"></i> My Addresses
                        </a>
                        <a href="/account/wishlist" class="list-group-item list-group-item-action">
                            <i class="fas fa-heart me-2"></i> My Wishlist
                        </a>
                        <a href="/account/reviews" class="list-group-item list-group-item-action">
                            <i class="fas fa-star me-2"></i> My Reviews
                        </a>
                        <a href="/auth/logout" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Order Detail Content -->
            <div class="col-lg-9">
                <?php if ($successMessage): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($successMessage) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($errorMessage) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">
                        <a href="/account/orders" class="text-decoration-none me-2">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        Order #<?= $orderNumber ?>
                    </h4>
                    <?php if ($order['status'] === 'pending'): ?>
                        <form action="/account/orders/cancel/<?= $order['id'] ?>" method="post" class="cancel-order-form">
                            <button type="button" class="btn btn-outline-danger btn-sm cancel-order-btn">
                                <i class="fas fa-times me-1"></i> Cancel Order
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                
                <!-- Order Status -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="card-title">Order Status</h5>
                                <p class="mb-1">Placed on: <?= date('F j, Y, g:i a', strtotime($order['created_at'])) ?></p>
                                <div class="d-flex mt-2">
                                    <div class="me-3">
                                        <span class="badge <?= $statusColors[$order['status']] ?? 'bg-secondary' ?> p-2">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="badge <?= $paymentStatusColors[$order['payment_status']] ?? 'bg-secondary' ?> p-2">
                                            Payment: <?= ucfirst($order['payment_status']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <?php if ($order['status'] === 'shipped' && !empty($order['tracking_number'])): ?>
                                    <p class="mb-1">Tracking Number: <strong><?= htmlspecialchars($order['tracking_number']) ?></strong></p>
                                    <?php if (!empty($order['tracking_url'])): ?>
                                        <a href="<?= htmlspecialchars($order['tracking_url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-truck me-1"></i> Track Package
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <?php if ($order['status'] === 'delivered'): ?>
                                    <a href="/account/reviews/order/<?= $order['id'] ?>" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-star me-1"></i> Write a Review
                                    </a>
                                <?php endif; ?>
                                
                                <a href="/account/orders/<?= $order['id'] ?>/invoice" class="btn btn-outline-secondary btn-sm ms-2">
                                    <i class="fas fa-file-invoice me-1"></i> Invoice
                                </a>
                            </div>
                        </div>
                        
                        <?php if ($orderHistory && count($orderHistory) > 0): ?>
                            <hr>
                            <h6>Order History</h6>
                            <div class="order-timeline mt-3">
                                <?php foreach ($orderHistory as $history): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-date">
                                                <?= date('M d, Y g:i a', strtotime($history['created_at'])) ?>
                                            </div>
                                            <div class="timeline-title">
                                                <?= htmlspecialchars($history['status']) ?>
                                            </div>
                                            <?php if (!empty($history['notes'])): ?>
                                                <div class="timeline-text text-muted">
                                                    <?= htmlspecialchars($history['notes']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Order Items -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Order Items</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4">Product</th>
                                                <th class="text-center">Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end pe-4">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orderItems as $item): ?>
                                                <tr>
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <?php if (!empty($item['image'])): ?>
                                                                <a href="/products/<?= $item['product_id'] ?>" class="me-3">
                                                                    <img src="<?= '/uploads/products/' . $item['image'] ?>" 
                                                                        alt="<?= htmlspecialchars($item['name']) ?>" class="img-thumbnail" 
                                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                                </a>
                                                            <?php endif; ?>
                                                            <div>
                                                                <a href="/products/<?= $item['product_id'] ?>" class="text-decoration-none">
                                                                    <h6 class="mb-0"><?= htmlspecialchars($item['name']) ?></h6>
                                                                </a>
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
                                                                
                                                                <?php if ($order['status'] === 'delivered'): ?>
                                                                    <div class="mt-2">
                                                                        <a href="/products/<?= $item['product_id'] ?>#review" class="btn btn-sm btn-outline-info">
                                                                            <i class="fas fa-star me-1"></i> Review
                                                                        </a>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center"><?= number_format($item['price'], 0) ?></td>
                                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                                    <td class="text-end pe-4"><?= number_format($item['price'] * $item['quantity'], 0) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="3" class="text-end">Subtotal:</td>
                                                <td class="text-end pe-4"><?= number_format($order['subtotal'], 0) ?></td>
                                            </tr>
                                            <?php if ($order['discount'] > 0): ?>
                                                <tr>
                                                    <td colspan="3" class="text-end">Discount:</td>
                                                    <td class="text-end text-danger pe-4">-<?= number_format($order['discount'], 0) ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <td colspan="3" class="text-end">Shipping:</td>
                                                <td class="text-end pe-4"><?= number_format($order['shipping_fee'], 0) ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                                <td class="text-end fw-bold pe-4"><?= number_format($order['total'], 0) ?></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Information -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Shipping Information</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2"><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></h6>
                                <address class="mb-3">
                                    <?= htmlspecialchars($order['address_line1']) ?><br>
                                    <?php if (!empty($order['address_line2'])): ?>
                                        <?= htmlspecialchars($order['address_line2']) ?><br>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($order['city']) ?>, 
                                    <?= htmlspecialchars($order['state']) ?> <?= htmlspecialchars($order['zip_code']) ?><br>
                                    <?= htmlspecialchars($order['country']) ?><br>
                                    <strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?>
                                </address>
                                <p class="mb-0"><strong>Shipping Method:</strong> <?= htmlspecialchars($order['shipping_method']) ?></p>
                            </div>
                        </div>
                        
                        <div class="card shadow-sm mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Payment Information</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                                
                                <?php if ($order['payment_method'] === 'credit_card'): ?>
                                    <p class="mb-0">
                                        <strong>Card:</strong> 
                                        <?= !empty($order['card_brand']) ? ucfirst($order['card_brand']) : '' ?> 
                                        •••• <?= htmlspecialchars($order['card_last4'] ?? '****') ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if ($order['payment_method'] === 'bank_transfer' && $order['payment_status'] === 'pending'): ?>
                                    <div class="alert alert-warning mt-3 mb-0">
                                        <h6 class="alert-heading">Payment Instructions</h6>
                                        <p class="mb-2">Please complete your payment using the following information:</p>
                                        <p class="mb-1"><strong>Bank:</strong> National Bank</p>
                                        <p class="mb-1"><strong>Account Name:</strong> Solo E-commerce</p>
                                        <p class="mb-1"><strong>Account Number:</strong> 1234567890</p>
                                        <p class="mb-1"><strong>Reference:</strong> ORDER #<?= $orderNumber ?></p>
                                        <p class="mb-0"><strong>Amount:</strong> <?= number_format($order['total'], 0) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if (!empty($order['notes'])): ?>
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Order Notes</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Cancel Order Confirmation Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Confirm Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this order? This action cannot be undone.</p>
                <div class="mb-3">
                    <label for="cancellation_reason" class="form-label">Reason for Cancellation (Optional)</label>
                    <select class="form-select" id="cancellation_reason" name="cancellation_reason">
                        <option value="Changed my mind">Changed my mind</option>
                        <option value="Found a better price">Found a better price</option>
                        <option value="Ordered by mistake">Ordered by mistake</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3" id="other_reason_container" style="display: none;">
                    <label for="other_reason" class="form-label">Please specify</label>
                    <textarea class="form-control" id="other_reason" name="other_reason" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Order</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel Order</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cancel order confirmation
    const cancelButtons = document.querySelectorAll('.cancel-order-btn');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    let activeForm = null;
    
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            activeForm = this.closest('form');
            const cancelModal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
            cancelModal.show();
        });
    });
    
    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener('click', function() {
            if (activeForm) {
                // Add reason to the form
                const reason = document.getElementById('cancellation_reason').value;
                const otherReason = document.getElementById('other_reason').value;
                
                let reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'reason';
                reasonInput.value = reason === 'Other' && otherReason ? otherReason : reason;
                
                activeForm.appendChild(reasonInput);
                activeForm.submit();
            }
        });
    }
    
    // Show/hide other reason field
    const reasonSelect = document.getElementById('cancellation_reason');
    const otherReasonContainer = document.getElementById('other_reason_container');
    
    if (reasonSelect && otherReasonContainer) {
        reasonSelect.addEventListener('change', function() {
            otherReasonContainer.style.display = this.value === 'Other' ? 'block' : 'none';
        });
    }
});
</script>

<style>
    .order-not-found {
        text-align: center;
        padding: 3rem 1rem;
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
    }
    
    .not-found-icon {
        font-size: 4rem;
        color: #ef4444;
        margin-bottom: 1.5rem;
    }
    
    .order-not-found h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: var(--heading-color);
    }
    
    .order-not-found p {
        color: var(--light-text);
        margin-bottom: 1.5rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .not-found-actions {
        display: flex;
        justify-content: center;
    }
    
    .order-detail-content {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        padding: 1.5rem;
    }
    
    .order-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .detail-title {
        font-size: 1.4rem;
        margin: 0 0 0.75rem 0;
        color: var(--heading-color);
    }
    
    .order-meta {
        display: flex;
        gap: 1.5rem;
        color: var(--body-color);
        font-size: 0.95rem;
    }
    
    .order-date,
    .order-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .order-actions {
        display: flex;
        gap: 1rem;
    }
    
    .order-detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    
    .order-detail-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.2rem;
        margin: 0 0 1rem 0;
        color: var(--heading-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .order-items-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .order-items-table th,
    .order-items-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    
    .order-items-table th {
        background-color: #f1f5f9;
        font-weight: 600;
        color: var(--heading-color);
    }
    
    .item-image {
        width: 80px;
    }
    
    .item-image img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: var(--border-radius);
    }
    
    .item-name a {
        color: var(--heading-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .item-name a:hover {
        color: var(--primary-color);
    }
    
    .item-variant {
        font-size: 0.85rem;
        color: var(--light-text);
        margin-top: 0.25rem;
    }
    
    .item-price,
    .item-quantity,
    .item-total {
        text-align: center;
    }
    
    .item-total {
        font-weight: 600;
        color: var(--heading-color);
    }
    
    .order-timeline {
        padding: 1rem 0;
    }
    
    .timeline-track {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-track::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 7px;
        width: 2px;
        background-color: var(--border-color);
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-point {
        position: absolute;
        top: 0;
        left: -2rem;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: var(--white);
        border: 2px solid var(--border-color);
        z-index: 1;
    }
    
    .timeline-item.completed .timeline-point {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .timeline-item.current .timeline-point {
        background-color: var(--white);
        border-color: var(--primary-color);
    }
    
    .timeline-content {
        padding-bottom: 1rem;
    }
    
    .timeline-date {
        font-size: 0.85rem;
        color: var(--light-text);
        margin-bottom: 0.25rem;
    }
    
    .timeline-status {
        font-weight: 600;
        color: var(--heading-color);
        margin-bottom: 0.25rem;
    }
    
    .timeline-description {
        color: var(--body-color);
    }
    
    .order-summary,
    .shipping-info {
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        padding: 1.25rem;
    }
    
    .summary-items {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .summary-label {
        color: var(--body-color);
    }
    
    .summary-value {
        font-weight: 500;
        color: var(--heading-color);
    }
    
    .summary-item.total {
        padding-top: 0.75rem;
        margin-top: 0.5rem;
        border-top: 1px solid var(--border-color);
    }
    
    .summary-item.total .summary-label {
        font-weight: 600;
        color: var(--heading-color);
    }
    
    .summary-item.total .summary-value {
        font-size: 1.1rem;
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .discount {
        color: #ef4444;
    }
    
    .summary-item.payment,
    .summary-item.payment-status {
        padding-top: 0.75rem;
        margin-top: 0.5rem;
        border-top: 1px solid var(--border-color);
    }
    
    .shipping-method {
        margin-bottom: 1rem;
        color: var(--body-color);
    }
    
    .shipping-address {
        padding: 1rem;
        background-color: var(--white);
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
    }
    
    .address-name {
        font-weight: 600;
        color: var(--heading-color);
        margin-bottom: 0.25rem;
    }
    
    .address-phone,
    .address-line {
        color: var(--body-color);
        margin-bottom: 0.25rem;
    }
    
    .order-detail-footer {
        display: flex;
        justify-content: space-between;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }
    
    @media (max-width: 992px) {
        .order-detail-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .order-detail-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .order-actions {
            width: 100%;
            justify-content: flex-start;
        }
        
        .order-meta {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .order-items-table {
            display: block;
            overflow-x: auto;
        }
        
        .order-detail-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }
    }
    
    @media print {
        .page-header, 
        .account-sidebar, 
        .order-actions, 
        .order-detail-footer,
        .btn,
        footer {
            display: none !important;
        }
        
        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
        }
        
        .order-detail-content {
            box-shadow: none;
            padding: 0;
        }
        
        .order-detail-grid {
            grid-template-columns: 1fr;
        }
        
        body {
            background-color: white;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 