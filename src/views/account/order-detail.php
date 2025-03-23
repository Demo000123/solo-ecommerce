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
        <div class="order-detail-content">
            <div class="order-detail-header">
                <div class="order-id-section">
                    <h2 class="detail-title">Đơn hàng #<?= $order['id'] ?></h2>
                    <div class="order-meta">
                        <div class="order-date">
                            <i class="far fa-calendar-alt"></i>
                            Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['date'])) ?>
                        </div>
                        <div class="order-status">
                            <i class="fas fa-circle-notch"></i>
                            Trạng thái: 
                            <span class="status-badge <?= getOrderStatusClass($order['status']) ?>">
                                <?= getOrderStatusText($order['status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="order-actions">
                    <?php if ($order['status'] === 'pending'): ?>
                        <form action="/account/orders/cancel" method="POST" class="cancel-order-form"
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-outline">
                                <i class="fas fa-times"></i> Hủy đơn hàng
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($order['status'] === 'completed'): ?>
                        <form action="/account/orders/reorder" method="POST" class="reorder-form">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-redo"></i> Đặt lại
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <a href="javascript:window.print();" class="btn btn-text">
                        <i class="fas fa-print"></i> In đơn hàng
                    </a>
                </div>
            </div>
            
            <div class="order-detail-grid">
                <div class="order-main">
                    <div class="order-detail-section">
                        <h3 class="section-title">Sản phẩm đã đặt</h3>
                        
                        <div class="order-items">
                            <table class="order-items-table">
                                <thead>
                                    <tr>
                                        <th class="item-image">Hình ảnh</th>
                                        <th class="item-name">Sản phẩm</th>
                                        <th class="item-price">Đơn giá</th>
                                        <th class="item-quantity">SL</th>
                                        <th class="item-total">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <tr>
                                            <td class="item-image">
                                                <img src="<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                            </td>
                                            <td class="item-name">
                                                <a href="/products/<?= $item['product_id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                                                <?php if (!empty($item['variant'])): ?>
                                                    <div class="item-variant"><?= htmlspecialchars($item['variant']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="item-price"><?= number_format($item['price'], 0, ',', '.') ?>₫</td>
                                            <td class="item-quantity"><?= $item['quantity'] ?></td>
                                            <td class="item-total"><?= number_format($item['subtotal'], 0, ',', '.') ?>₫</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="order-detail-section">
                        <h3 class="section-title">Tiến trình đơn hàng</h3>
                        
                        <div class="order-timeline">
                            <div class="timeline-track">
                                <?php foreach ($order['timeline'] as $index => $step): ?>
                                    <div class="timeline-item <?= $index < count($order['timeline']) - 1 ? 'completed' : 'current' ?>">
                                        <div class="timeline-point"></div>
                                        <div class="timeline-content">
                                            <div class="timeline-date">
                                                <?= date('d/m/Y H:i', strtotime($step['date'])) ?>
                                            </div>
                                            <div class="timeline-status">
                                                <?= getOrderStatusText($step['status']) ?>
                                            </div>
                                            <div class="timeline-description">
                                                <?= htmlspecialchars($step['description']) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="order-sidebar">
                    <div class="order-detail-section order-summary">
                        <h3 class="section-title">Tổng quan đơn hàng</h3>
                        
                        <div class="summary-items">
                            <div class="summary-item">
                                <span class="summary-label">Tạm tính</span>
                                <span class="summary-value"><?= number_format($order['subtotal'], 0, ',', '.') ?>₫</span>
                            </div>
                            
                            <?php if ($order['discount'] > 0): ?>
                                <div class="summary-item">
                                    <span class="summary-label">Giảm giá</span>
                                    <span class="summary-value discount">-<?= number_format($order['discount'], 0, ',', '.') ?>₫</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="summary-item">
                                <span class="summary-label">Phí vận chuyển</span>
                                <span class="summary-value"><?= number_format($order['shipping_fee'], 0, ',', '.') ?>₫</span>
                            </div>
                            
                            <div class="summary-item total">
                                <span class="summary-label">Tổng cộng</span>
                                <span class="summary-value"><?= number_format($order['total'], 0, ',', '.') ?>₫</span>
                            </div>
                            
                            <div class="summary-item payment">
                                <span class="summary-label">Phương thức thanh toán</span>
                                <span class="summary-value"><?= $order['payment_method'] ?></span>
                            </div>
                            
                            <div class="summary-item payment-status">
                                <span class="summary-label">Trạng thái thanh toán</span>
                                <span class="summary-value status-badge <?= getPaymentStatusClass($order['payment_status']) ?>">
                                    <?= getPaymentStatusText($order['payment_status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-detail-section shipping-info">
                        <h3 class="section-title">Thông tin giao hàng</h3>
                        
                        <div class="shipping-method">
                            <strong>Phương thức vận chuyển:</strong>
                            <span><?= $order['shipping_method'] ?></span>
                        </div>
                        
                        <div class="shipping-address">
                            <div class="address-name"><?= htmlspecialchars($order['shipping_address']['fullname']) ?></div>
                            <div class="address-phone"><?= htmlspecialchars($order['shipping_address']['phone']) ?></div>
                            <div class="address-line"><?= htmlspecialchars($order['shipping_address']['address']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="order-detail-footer">
                <a href="/account/orders" class="btn btn-outline">
                    <i class="fas fa-chevron-left"></i> Quay lại danh sách đơn hàng
                </a>
                
                <?php if ($order['status'] === 'completed'): ?>
                    <a href="/account/orders/<?= $order['id'] ?>/review" class="btn btn-primary">
                        <i class="fas fa-star"></i> Đánh giá sản phẩm
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

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