<?php
// Orders page
require_once __DIR__ . '/../layouts/header.php';

// Check if user is logged in
$userLoggedIn = isset($user) && !empty($user);

// Get orders if user is logged in
$orders = [];
if ($userLoggedIn) {
    // In a real application, this would fetch from the database
    // Example mock data
    $orders = [
        [
            'id' => 'DH100001',
            'date' => '2023-06-15',
            'status' => 'completed',
            'total' => 1250000,
            'items_count' => 3,
            'payment_method' => 'COD'
        ],
        [
            'id' => 'DH100002',
            'date' => '2023-07-20',
            'status' => 'processing',
            'total' => 680000,
            'items_count' => 2,
            'payment_method' => 'Bank Transfer'
        ],
        [
            'id' => 'DH100003',
            'date' => '2023-08-05',
            'status' => 'cancelled',
            'total' => 450000,
            'items_count' => 1,
            'payment_method' => 'MoMo'
        ],
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
?>

<link rel="stylesheet" href="/public/css/account.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Đơn hàng của tôi</h1>
        <div class="breadcrumbs">
            <a href="/solo-ecommerce/src/views/home/index.php">Trang chủ</a>
            <span class="separator">/</span>
            <a href="/solo-ecommerce/src/views/account/index.php">Tài khoản</a>
            <span class="separator">/</span>
            <span class="current">Đơn hàng</span>
        </div>
    </div>
</div>

<div class="account-container container">
    <?php if (!$userLoggedIn): ?>
        <div class="not-authenticated">
            <p>Vui lòng <a href="/solo-ecommerce/src/views/account/index.php">đăng nhập</a> để xem đơn hàng của bạn.</p>
        </div>
    <?php else: ?>
        <div class="account-content">
            <div class="account-sidebar">
                <div class="user-info">
                    <div class="user-avatar">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <?= substr($user['fullname'] ?? 'U', 0, 1) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="user-details">
                        <h3 class="user-name"><?= htmlspecialchars($user['fullname'] ?? 'User') ?></h3>
                        <p class="user-email"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                    </div>
                </div>
                
                <ul class="account-menu">
                    <li>
                        <a href="/account">
                            <i class="fas fa-tachometer-alt"></i> Tổng quan
                        </a>
                    </li>
                    <li class="active">
                        <a href="/account/orders">
                            <i class="fas fa-shopping-bag"></i> Đơn hàng của tôi
                        </a>
                    </li>
                    <li>
                        <a href="/wishlist">
                            <i class="fas fa-heart"></i> Danh sách yêu thích
                        </a>
                    </li>
                    <li>
                        <a href="/account/addresses">
                            <i class="fas fa-map-marker-alt"></i> Sổ địa chỉ
                        </a>
                    </li>
                    <li>
                        <a href="/account/profile">
                            <i class="fas fa-user"></i> Thông tin tài khoản
                        </a>
                    </li>
                    <li>
                        <a href="/account/password">
                            <i class="fas fa-lock"></i> Đổi mật khẩu
                        </a>
                    </li>
                    <li>
                        <a href="/account/logout">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="account-main">
                <div class="account-section orders-section">
                    <div class="section-header">
                        <h2 class="section-title">Đơn hàng của tôi</h2>
                    </div>
                    
                    <?php if (empty($orders)): ?>
                        <div class="empty-orders">
                            <div class="empty-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <p>Bạn chưa có đơn hàng nào.</p>
                            <a href="/products" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> Mua sắm ngay
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="orders-filter">
                            <div class="filter-group">
                                <label for="order-status-filter">Lọc theo trạng thái:</label>
                                <select id="order-status-filter" class="form-control">
                                    <option value="all">Tất cả</option>
                                    <option value="pending">Chờ xác nhận</option>
                                    <option value="processing">Đang xử lý</option>
                                    <option value="shipping">Đang giao hàng</option>
                                    <option value="completed">Đã hoàn thành</option>
                                    <option value="cancelled">Đã hủy</option>
                                </select>
                            </div>
                            
                            <div class="filter-group">
                                <label for="order-date-filter">Sắp xếp theo:</label>
                                <select id="order-date-filter" class="form-control">
                                    <option value="newest">Mới nhất</option>
                                    <option value="oldest">Cũ nhất</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="orders-list">
                            <?php foreach ($orders as $order): ?>
                                <div class="order-card" data-status="<?= $order['status'] ?>">
                                    <div class="order-header">
                                        <div class="order-info">
                                            <h3 class="order-id">Đơn hàng #<?= $order['id'] ?></h3>
                                            <span class="order-date">
                                                <i class="far fa-calendar-alt"></i>
                                                <?= date('d/m/Y', strtotime($order['date'])) ?>
                                            </span>
                                        </div>
                                        <div class="order-status">
                                            <span class="status-badge <?= getOrderStatusClass($order['status']) ?>">
                                                <?= getOrderStatusText($order['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="order-body">
                                        <div class="order-detail">
                                            <div class="detail-item">
                                                <span class="detail-label">Số lượng sản phẩm:</span>
                                                <span class="detail-value"><?= $order['items_count'] ?></span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Phương thức thanh toán:</span>
                                                <span class="detail-value"><?= $order['payment_method'] ?></span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Tổng tiền:</span>
                                                <span class="detail-value order-total">
                                                    <?= number_format($order['total'], 0, ',', '.') ?>₫
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="order-footer">
                                        <a href="/account/orders/<?= $order['id'] ?>" class="btn btn-outline btn-sm">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                        
                                        <?php if ($order['status'] === 'pending'): ?>
                                            <form action="/account/orders/cancel" method="POST" class="cancel-order-form"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                <button type="submit" class="btn btn-text btn-danger btn-sm">
                                                    <i class="fas fa-times"></i> Hủy đơn hàng
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($order['status'] === 'completed'): ?>
                                            <a href="/account/orders/<?= $order['id'] ?>/review" class="btn btn-text btn-sm">
                                                <i class="fas fa-star"></i> Đánh giá
                                            </a>
                                            <form action="/account/orders/reorder" method="POST" class="reorder-form">
                                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-redo"></i> Đặt lại
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('order-status-filter');
        const dateFilter = document.getElementById('order-date-filter');
        const ordersList = document.querySelector('.orders-list');
        const orderCards = document.querySelectorAll('.order-card');
        
        if (statusFilter) {
            statusFilter.addEventListener('change', function() {
                const status = this.value;
                
                orderCards.forEach(card => {
                    if (status === 'all' || card.dataset.status === status) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                checkEmptyState();
            });
        }
        
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                const sortMethod = this.value;
                const orders = Array.from(orderCards);
                
                orders.sort((a, b) => {
                    const dateA = new Date(a.querySelector('.order-date').textContent.trim());
                    const dateB = new Date(b.querySelector('.order-date').textContent.trim());
                    
                    return sortMethod === 'newest' 
                        ? dateB - dateA 
                        : dateA - dateB;
                });
                
                // Remove all orders and append in new order
                orders.forEach(order => {
                    ordersList.appendChild(order);
                });
            });
        }
        
        function checkEmptyState() {
            let visibleOrders = 0;
            
            orderCards.forEach(card => {
                if (card.style.display !== 'none') {
                    visibleOrders++;
                }
            });
            
            if (visibleOrders === 0) {
                // Create empty message
                let emptyMessage = document.querySelector('.empty-filtered-orders');
                
                if (!emptyMessage) {
                    emptyMessage = document.createElement('div');
                    emptyMessage.className = 'empty-filtered-orders';
                    emptyMessage.innerHTML = `
                        <p>Không tìm thấy đơn hàng nào phù hợp với bộ lọc.</p>
                    `;
                    ordersList.appendChild(emptyMessage);
                }
                
                emptyMessage.style.display = 'block';
            } else {
                const emptyMessage = document.querySelector('.empty-filtered-orders');
                if (emptyMessage) {
                    emptyMessage.style.display = 'none';
                }
            }
        }
    });
</script>

<style>
    /* Order-specific styles */
    .orders-filter {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 200px;
    }
    
    .orders-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-card {
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    
    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color);
        background-color: #f1f5f9;
    }
    
    .order-id {
        font-size: 1.1rem;
        margin: 0 0 0.25rem 0;
        color: var(--heading-color);
    }
    
    .order-date {
        font-size: 0.9rem;
        color: var(--light-text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-pending {
        background-color: #fef3c7;
        color: #d97706;
    }
    
    .status-processing {
        background-color: #dbeafe;
        color: #2563eb;
    }
    
    .status-shipping {
        background-color: #e0e7ff;
        color: #4f46e5;
    }
    
    .status-completed {
        background-color: #dcfce7;
        color: #16a34a;
    }
    
    .status-cancelled {
        background-color: #fee2e2;
        color: #dc2626;
    }
    
    .order-body {
        padding: 1.25rem;
    }
    
    .order-detail {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .detail-label {
        font-size: 0.9rem;
        color: var(--light-text);
    }
    
    .detail-value {
        font-weight: 500;
        color: var(--body-color);
    }
    
    .order-total {
        font-size: 1.1rem;
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .order-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border-color);
        background-color: #f1f5f9;
    }
    
    .empty-orders {
        text-align: center;
        padding: 3rem 1rem;
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        border: 1px dashed var(--border-color);
    }
    
    .empty-filtered-orders {
        text-align: center;
        padding: 2rem 1rem;
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        border: 1px dashed var(--border-color);
        margin-top: 1rem;
    }
    
    .empty-icon {
        font-size: 3rem;
        color: var(--light-text);
        margin-bottom: 1rem;
    }
    
    .empty-orders p,
    .empty-filtered-orders p {
        color: var(--light-text);
        margin-bottom: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .order-header,
        .order-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .order-footer {
            align-items: stretch;
        }
        
        .order-footer .btn {
            text-align: center;
        }
        
        .order-detail {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 