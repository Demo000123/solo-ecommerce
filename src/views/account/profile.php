<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/public/css/account.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Thông tin tài khoản</h1>
        <div class="breadcrumbs">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <a href="/account">Tài khoản</a>
            <span class="separator">/</span>
            <span class="current">Thông tin tài khoản</span>
        </div>
    </div>
</div>

<div class="account-container container">
    <?php if (!isset($user) || empty($user)): ?>
        <div class="not-authenticated">
            <p>Vui lòng <a href="/account">đăng nhập</a> để xem thông tin tài khoản.</p>
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
                    <li>
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
                    <li class="active">
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
                <div class="account-section profile-section">
                    <h2 class="section-title">Thông tin cá nhân</h2>
                    
                    <?php if (isset($updateSuccess)): ?>
                        <div class="alert alert-success">
                            Thông tin tài khoản đã được cập nhật thành công.
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($updateError)): ?>
                        <div class="alert alert-error">
                            <?= $updateError ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/account/profile/update" method="POST" class="profile-form" enctype="multipart/form-data">
                        <div class="avatar-upload">
                            <div class="avatar-preview">
                                <?php if (!empty($user['avatar'])): ?>
                                    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" id="avatar-preview">
                                <?php else: ?>
                                    <div class="avatar-placeholder" id="avatar-placeholder">
                                        <?= substr($user['fullname'] ?? 'U', 0, 1) ?>
                                    </div>
                                    <img src="" alt="Avatar" id="avatar-preview" style="display: none;">
                                <?php endif; ?>
                            </div>
                            <div class="avatar-edit">
                                <label for="avatar-upload" class="btn btn-outline btn-sm">Thay đổi ảnh đại diện</label>
                                <input type="file" id="avatar-upload" name="avatar" accept="image/*" style="display: none;">
                                <?php if (!empty($user['avatar'])): ?>
                                    <button type="button" id="remove-avatar" class="btn btn-outline btn-sm btn-danger">Xóa ảnh</button>
                                    <input type="hidden" name="remove_avatar" id="remove-avatar-input" value="0">
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fullname">Họ và tên <span class="required">*</span></label>
                                <input type="text" id="fullname" name="fullname" class="form-control" required
                                       value="<?= htmlspecialchars($user['fullname'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" required
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>" <?= !empty($user['email']) ? 'readonly' : '' ?>>
                                <?php if (!empty($user['email'])): ?>
                                    <div class="input-note">Email không thể thay đổi.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="phone">Số điện thoại <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control" required
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="birthday">Ngày sinh</label>
                                <input type="date" id="birthday" name="birthday" class="form-control"
                                       value="<?= htmlspecialchars($user['birthday'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Giới tính</label>
                                <div class="radio-group">
                                    <label class="radio-container">
                                        <input type="radio" name="gender" value="male" <?= ($user['gender'] ?? '') === 'male' ? 'checked' : '' ?>>
                                        <div class="radio-custom"></div>
                                        <span>Nam</span>
                                    </label>
                                    <label class="radio-container">
                                        <input type="radio" name="gender" value="female" <?= ($user['gender'] ?? '') === 'female' ? 'checked' : '' ?>>
                                        <div class="radio-custom"></div>
                                        <span>Nữ</span>
                                    </label>
                                    <label class="radio-container">
                                        <input type="radio" name="gender" value="other" <?= ($user['gender'] ?? '') === 'other' ? 'checked' : '' ?>>
                                        <div class="radio-custom"></div>
                                        <span>Khác</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-divider"></div>
                        
                        <h3 class="sub-section-title">Cài đặt tài khoản</h3>
                        
                        <div class="form-group preferences-group">
                            <label class="checkbox-container">
                                <input type="checkbox" name="newsletter" value="1" <?= ($user['newsletter'] ?? 0) ? 'checked' : '' ?>>
                                <span class="checkbox-custom"></span>
                                <span>Nhận thông báo qua email về các sản phẩm mới, khuyến mãi</span>
                            </label>
                            
                            <label class="checkbox-container">
                                <input type="checkbox" name="sms_notification" value="1" <?= ($user['sms_notification'] ?? 0) ? 'checked' : '' ?>>
                                <span class="checkbox-custom"></span>
                                <span>Nhận thông báo qua SMS về đơn hàng và các chương trình khuyến mãi</span>
                            </label>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Avatar upload preview
        const avatarUploadInput = document.getElementById('avatar-upload');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarPlaceholder = document.getElementById('avatar-placeholder');
        
        if (avatarUploadInput && avatarPreview) {
            avatarUploadInput.addEventListener('change', function(event) {
                if (event.target.files && event.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                        avatarPreview.style.display = 'block';
                        
                        if (avatarPlaceholder) {
                            avatarPlaceholder.style.display = 'none';
                        }
                    }
                    
                    reader.readAsDataURL(event.target.files[0]);
                }
            });
        }
        
        // Remove avatar
        const removeAvatarBtn = document.getElementById('remove-avatar');
        const removeAvatarInput = document.getElementById('remove-avatar-input');
        
        if (removeAvatarBtn && removeAvatarInput) {
            removeAvatarBtn.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa ảnh đại diện?')) {
                    removeAvatarInput.value = '1';
                    
                    if (avatarPreview) {
                        avatarPreview.style.display = 'none';
                    }
                    
                    if (avatarPlaceholder) {
                        avatarPlaceholder.style.display = 'flex';
                    } else {
                        // Create placeholder if it doesn't exist
                        const placeholderDiv = document.createElement('div');
                        placeholderDiv.className = 'avatar-placeholder';
                        placeholderDiv.id = 'avatar-placeholder';
                        placeholderDiv.textContent = document.querySelector('.user-name').textContent.charAt(0);
                        
                        avatarPreview.parentNode.insertBefore(placeholderDiv, avatarPreview);
                    }
                }
            });
        }
    });
</script>

<style>
    .account-container {
        margin-top: 2rem;
        margin-bottom: 4rem;
    }
    
    .not-authenticated {
        text-align: center;
        padding: 3rem;
        background-color: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-sm);
    }
    
    .not-authenticated a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }
    
    .not-authenticated a:hover {
        text-decoration: underline;
    }
    
    .alert {
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background-color: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    
    .alert-error {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .avatar-upload {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 1.5rem;
    }
    
    .avatar-preview img {
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
        font-size: 2.5rem;
        font-weight: 600;
    }
    
    .avatar-edit {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
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
    
    .input-note {
        font-size: 0.85rem;
        color: var(--light-text);
        margin-top: 0.5rem;
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--body-color);
        font-weight: 500;
    }
    
    .required {
        color: #ef4444;
    }
    
    .radio-group {
        display: flex;
        gap: 1.5rem;
    }
    
    .radio-container,
    .checkbox-container {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .radio-container input[type="radio"],
    .checkbox-container input[type="checkbox"] {
        display: none;
    }
    
    .radio-custom {
        width: 18px;
        height: 18px;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        margin-right: 0.5rem;
        position: relative;
        flex-shrink: 0;
    }
    
    .checkbox-custom {
        width: 18px;
        height: 18px;
        border: 2px solid var(--border-color);
        border-radius: 3px;
        margin-right: 0.5rem;
        position: relative;
        flex-shrink: 0;
    }
    
    .radio-container input[type="radio"]:checked + .radio-custom::after {
        content: '';
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: var(--primary-color);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .checkbox-container input[type="checkbox"]:checked + .checkbox-custom::after {
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
    
    .form-divider {
        height: 1px;
        background-color: var(--border-color);
        margin: 2rem 0;
    }
    
    .sub-section-title {
        font-size: 1.15rem;
        margin-bottom: 1.25rem;
        color: var(--heading-color);
    }
    
    .preferences-group {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .form-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
    }
    
    .btn {
        display: inline-block;
        padding: 0.8rem 1.5rem;
        border-radius: var(--border-radius);
        font-size: 0.95rem;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition-normal);
        border: none;
        font-family: inherit;
    }
    
    .btn-sm {
        padding: 0.6rem 1rem;
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
    
    .btn-danger {
        color: #ef4444;
        border-color: #ef4444;
    }
    
    .btn-danger:hover {
        background-color: #ef4444;
        color: white;
    }
    
    @media (max-width: 992px) {
        .account-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .radio-group {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .avatar-upload {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .avatar-preview {
            margin-bottom: 1rem;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 