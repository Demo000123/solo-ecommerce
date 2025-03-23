<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/public/css/account.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Tài khoản của tôi</h1>
        <div class="breadcrumbs">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <span class="current">Tài khoản</span>
        </div>
    </div>
</div>

<div class="account-container container">
    <?php if (!isset($user) || empty($user)): ?>
        <div class="login-register">
            <div class="auth-container">
                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="login">Đăng nhập</button>
                    <button class="auth-tab" data-tab="register">Đăng ký</button>
                </div>
                
                <div class="auth-content">
                    <div class="auth-form-container" id="login-form" style="display: block;">
                        <form action="/account/login" method="POST" class="auth-form">
                            <?php if (isset($loginError)): ?>
                                <div class="auth-error"><?= $loginError ?></div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="login-email">Email <span class="required">*</span></label>
                                <input type="email" id="login-email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="login-password">Mật khẩu <span class="required">*</span></label>
                                <div class="password-field">
                                    <input type="password" id="login-password" name="password" class="form-control" required>
                                    <button type="button" class="toggle-password">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group remember-forgot">
                                <label class="checkbox-container">
                                    <input type="checkbox" name="remember">
                                    <span class="checkbox-custom"></span>
                                    Ghi nhớ đăng nhập
                                </label>
                                <a href="/account/forgot-password" class="forgot-password">Quên mật khẩu?</a>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                            
                            <div class="social-login">
                                <p>Hoặc đăng nhập với</p>
                                <div class="social-buttons">
                                    <a href="/account/login/facebook" class="social-button facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="/account/login/google" class="social-button google">
                                        <i class="fab fa-google"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="auth-form-container" id="register-form" style="display: none;">
                        <form action="/account/register" method="POST" class="auth-form">
                            <?php if (isset($registerError)): ?>
                                <div class="auth-error"><?= $registerError ?></div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="register-fullname">Họ và tên <span class="required">*</span></label>
                                <input type="text" id="register-fullname" name="fullname" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="register-email">Email <span class="required">*</span></label>
                                <input type="email" id="register-email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="register-phone">Số điện thoại <span class="required">*</span></label>
                                <input type="tel" id="register-phone" name="phone" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="register-password">Mật khẩu <span class="required">*</span></label>
                                <div class="password-field">
                                    <input type="password" id="register-password" name="password" class="form-control" required>
                                    <button type="button" class="toggle-password">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="register-confirm-password">Xác nhận mật khẩu <span class="required">*</span></label>
                                <div class="password-field">
                                    <input type="password" id="register-confirm-password" name="confirm_password" class="form-control" required>
                                    <button type="button" class="toggle-password">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="checkbox-container">
                                    <input type="checkbox" name="terms_agreed" required>
                                    <span class="checkbox-custom"></span>
                                    Tôi đồng ý với <a href="/terms" target="_blank">Điều khoản dịch vụ</a> và <a href="/privacy-policy" target="_blank">Chính sách bảo mật</a>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>
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
                    <li class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">
                        <a href="/account">
                            <i class="fas fa-tachometer-alt"></i> Tổng quan
                        </a>
                    </li>
                    <li class="<?= $activePage === 'orders' ? 'active' : '' ?>">
                        <a href="/account/orders">
                            <i class="fas fa-shopping-bag"></i> Đơn hàng của tôi
                        </a>
                    </li>
                    <li class="<?= $activePage === 'wishlist' ? 'active' : '' ?>">
                        <a href="/wishlist">
                            <i class="fas fa-heart"></i> Danh sách yêu thích
                        </a>
                    </li>
                    <li class="<?= $activePage === 'addresses' ? 'active' : '' ?>">
                        <a href="/account/addresses">
                            <i class="fas fa-map-marker-alt"></i> Sổ địa chỉ
                        </a>
                    </li>
                    <li class="<?= $activePage === 'profile' ? 'active' : '' ?>">
                        <a href="/account/profile">
                            <i class="fas fa-user"></i> Thông tin tài khoản
                        </a>
                    </li>
                    <li class="<?= $activePage === 'password' ? 'active' : '' ?>">
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
                <div class="account-section dashboard-section">
                    <h2 class="section-title">Tổng quan tài khoản</h2>
                    
                    <div class="dashboard-cards">
                        <div class="dashboard-card">
                            <div class="card-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title">Đơn hàng</h3>
                                <p class="card-value"><?= $orderCount ?? 0 ?></p>
                            </div>
                            <a href="/account/orders" class="card-link">Xem tất cả</a>
                        </div>
                        
                        <div class="dashboard-card">
                            <div class="card-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title">Yêu thích</h3>
                                <p class="card-value"><?= $wishlistCount ?? 0 ?></p>
                            </div>
                            <a href="/wishlist" class="card-link">Xem tất cả</a>
                        </div>
                        
                        <div class="dashboard-card">
                            <div class="card-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title">Mã giảm giá</h3>
                                <p class="card-value"><?= $couponCount ?? 0 ?></p>
                            </div>
                            <a href="/account/coupons" class="card-link">Xem tất cả</a>
                        </div>
                    </div>
                    
                    <div class="recent-orders">
                        <div class="section-header">
                            <h3>Đơn hàng gần đây</h3>
                            <a href="/account/orders" class="view-all">Xem tất cả</a>
                        </div>
                        
                        <?php if (empty($recentOrders)): ?>
                            <div class="empty-list">
                                <p>Bạn chưa có đơn hàng nào.</p>
                                <a href="/products" class="btn btn-outline">Mua sắm ngay</a>
                            </div>
                        <?php else: ?>
                            <div class="orders-table">
                                <div class="table-header">
                                    <div class="table-cell order-id">Mã đơn hàng</div>
                                    <div class="table-cell order-date">Ngày đặt</div>
                                    <div class="table-cell order-status">Trạng thái</div>
                                    <div class="table-cell order-total">Tổng tiền</div>
                                    <div class="table-cell order-actions">Thao tác</div>
                                </div>
                                
                                <?php foreach ($recentOrders as $order): ?>
                                    <div class="table-row">
                                        <div class="table-cell order-id">
                                            <span class="mobile-label">Mã đơn hàng:</span>
                                            #<?= $order['id'] ?>
                                        </div>
                                        <div class="table-cell order-date">
                                            <span class="mobile-label">Ngày đặt:</span>
                                            <?= date('d/m/Y', strtotime($order['created_at'])) ?>
                                        </div>
                                        <div class="table-cell order-status">
                                            <span class="mobile-label">Trạng thái:</span>
                                            <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                                <?= getOrderStatusText($order['status']) ?>
                                            </span>
                                        </div>
                                        <div class="table-cell order-total">
                                            <span class="mobile-label">Tổng tiền:</span>
                                            <?= number_format($order['total'], 0, ',', '.') ?> ₫
                                        </div>
                                        <div class="table-cell order-actions">
                                            <a href="/account/orders/<?= $order['id'] ?>" class="btn btn-sm">Chi tiết</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// Helper function to get order status text
function getOrderStatusText($status) {
    switch ($status) {
        case 'PENDING':
            return 'Chờ xác nhận';
        case 'PROCESSING':
            return 'Đang xử lý';
        case 'SHIPPING':
            return 'Đang giao hàng';
        case 'COMPLETED':
            return 'Đã giao hàng';
        case 'CANCELLED':
            return 'Đã hủy';
        default:
            return 'Không xác định';
    }
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auth tabs functionality
        const authTabs = document.querySelectorAll('.auth-tab');
        const authForms = document.querySelectorAll('.auth-form-container');
        
        authTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Update active tab
                authTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Show selected form
                authForms.forEach(form => {
                    form.style.display = 'none';
                });
                document.getElementById(tabId + '-form').style.display = 'block';
            });
        });
        
        // Password toggle functionality
        const togglePasswordButtons = document.querySelectorAll('.toggle-password');
        
        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function() {
                const passwordField = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordField.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>

<style>
    .account-container {
        margin-top: 2rem;
        margin-bottom: 4rem;
    }
    
    /* Login and Register Forms */
    .login-register {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .auth-container {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        overflow: hidden;
    }
    
    .auth-tabs {
        display: flex;
        border-bottom: 1px solid var(--border-color);
    }
    
    .auth-tab {
        flex: 1;
        padding: 1rem;
        text-align: center;
        background: none;
        border: none;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition-normal);
    }
    
    .auth-tab.active {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
    }
    
    .auth-content {
        padding: 2rem;
    }
    
    .auth-form {
        margin-bottom: 1rem;
    }
    
    .auth-error {
        background-color: #fef2f2;
        color: #ef4444;
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        font-size: 0.95rem;
        font-family: inherit;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
    }
    
    .password-field {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--light-text);
        cursor: pointer;
    }
    
    .remember-forgot {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .checkbox-container {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .checkbox-container input {
        display: none;
    }
    
    .checkbox-custom {
        width: 18px;
        height: 18px;
        border: 1px solid var(--border-color);
        border-radius: 3px;
        margin-right: 0.5rem;
        position: relative;
    }
    
    .checkbox-container input:checked + .checkbox-custom::after {
        content: '\f00c';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: var(--primary-color);
        font-size: 12px;
    }
    
    .forgot-password {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
    }
    
    .forgot-password:hover {
        text-decoration: underline;
    }
    
    .btn {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        border-radius: var(--border-radius);
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition-normal);
        border: none;
        font-size: 0.95rem;
        font-family: inherit;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
    }
    
    .btn-outline {
        background-color: transparent;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }
    
    .btn-outline:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-block {
        display: block;
        width: 100%;
        text-align: center;
    }
    
    .social-login {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }
    
    .social-login p {
        font-size: 0.9rem;
        color: var(--light-text);
        margin-bottom: 1rem;
    }
    
    .social-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
    
    .social-button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: var(--transition-normal);
    }
    
    .social-button.facebook {
        background-color: #4267B2;
    }
    
    .social-button.google {
        background-color: #DB4437;
    }
    
    .social-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    /* Account Dashboard */
    .account-content {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
    }
    
    .account-sidebar {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        overflow: hidden;
    }
    
    .user-info {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    .user-name {
        font-size: 1.1rem;
        margin: 0 0 0.25rem;
    }
    
    .user-email {
        font-size: 0.9rem;
        color: var(--light-text);
        margin: 0;
    }
    
    .account-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .account-menu li {
        border-bottom: 1px solid var(--border-color);
    }
    
    .account-menu li:last-child {
        border-bottom: none;
    }
    
    .account-menu li a {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        color: var(--body-color);
        text-decoration: none;
        transition: var(--transition-normal);
    }
    
    .account-menu li a i {
        margin-right: 0.75rem;
        width: 20px;
        text-align: center;
    }
    
    .account-menu li a:hover {
        background-color: rgba(163, 168, 116, 0.05);
        color: var(--primary-color);
    }
    
    .account-menu li.active a {
        background-color: rgba(163, 168, 116, 0.1);
        color: var(--primary-color);
        font-weight: 500;
    }
    
    .account-section {
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
        padding: 1.5rem;
    }
    
    .section-title {
        font-size: 1.35rem;
        margin-bottom: 1.5rem;
        color: var(--heading-color);
        position: relative;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .dashboard-card {
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        padding: 1.25rem;
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .card-icon {
        width: 50px;
        height: 50px;
        background-color: rgba(163, 168, 116, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--primary-color);
        margin-right: 1rem;
    }
    
    .card-title {
        font-size: 1rem;
        margin: 0 0 0.25rem;
        font-weight: 500;
    }
    
    .card-value {
        font-size: 1.5rem;
        margin: 0;
        font-weight: 600;
        color: var(--heading-color);
    }
    
    .card-link {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 0.85rem;
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .card-link:hover {
        text-decoration: underline;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .section-header h3 {
        font-size: 1.15rem;
        margin: 0;
        color: var(--heading-color);
    }
    
    .view-all {
        font-size: 0.9rem;
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .view-all:hover {
        text-decoration: underline;
    }
    
    .empty-list {
        text-align: center;
        padding: 2rem 0;
    }
    
    .empty-list p {
        color: var(--light-text);
        margin-bottom: 1rem;
    }
    
    .orders-table {
        border: 1px solid var(--border-color);
        border-radius: var(--border-radius);
        overflow: hidden;
    }
    
    .table-header {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 100px;
        background-color: #f8fafc;
        padding: 1rem;
        font-weight: 500;
    }
    
    .table-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 1fr 100px;
        padding: 1rem;
        border-top: 1px solid var(--border-color);
        align-items: center;
    }
    
    .mobile-label {
        display: none;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
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
        background-color: #e0f2fe;
        color: #0284c7;
    }
    
    .status-completed {
        background-color: #dcfce7;
        color: #16a34a;
    }
    
    .status-cancelled {
        background-color: #fee2e2;
        color: #dc2626;
    }
    
    @media (max-width: 992px) {
        .account-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .dashboard-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .auth-content {
            padding: 1.5rem;
        }
        
        .dashboard-cards {
            grid-template-columns: 1fr;
        }
        
        .table-header {
            display: none;
        }
        
        .table-row {
            grid-template-columns: 1fr;
            gap: 0.5rem;
            padding: 1rem;
        }
        
        .table-cell {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .mobile-label {
            display: inline-block;
            font-weight: 500;
        }
        
        .order-actions {
            justify-content: flex-start !important;
            margin-top: 0.5rem;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 