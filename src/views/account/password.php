<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<link rel="stylesheet" href="/public/css/account.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Đổi mật khẩu</h1>
        <div class="breadcrumbs">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <a href="/account">Tài khoản</a>
            <span class="separator">/</span>
            <span class="current">Đổi mật khẩu</span>
        </div>
    </div>
</div>

<div class="account-container container">
    <?php if (!isset($user) || empty($user)): ?>
        <div class="not-authenticated">
            <p>Vui lòng <a href="/account">đăng nhập</a> để thay đổi mật khẩu.</p>
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
                    <li>
                        <a href="/account/profile">
                            <i class="fas fa-user"></i> Thông tin tài khoản
                        </a>
                    </li>
                    <li class="active">
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
                <div class="account-section password-section">
                    <h2 class="section-title">Đổi mật khẩu</h2>
                    
                    <?php if (isset($updateSuccess)): ?>
                        <div class="alert alert-success">
                            Mật khẩu đã được cập nhật thành công.
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($updateError)): ?>
                        <div class="alert alert-error">
                            <?= $updateError ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/account/password/update" method="POST" class="password-form">
                        <div class="form-group">
                            <label for="current_password">Mật khẩu hiện tại <span class="required">*</span></label>
                            <div class="password-field">
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                                <button type="button" class="toggle-password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">Mật khẩu mới <span class="required">*</span></label>
                            <div class="password-field">
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                                <button type="button" class="toggle-password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-meter">
                                    <div class="strength-meter-fill" data-strength="0"></div>
                                </div>
                                <div class="strength-text" data-strength="0">Độ mạnh: Chưa nhập</div>
                            </div>
                            <ul class="password-requirements">
                                <li data-requirement="length">
                                    <i class="fas fa-times-circle"></i> Ít nhất 8 ký tự
                                </li>
                                <li data-requirement="uppercase">
                                    <i class="fas fa-times-circle"></i> Ít nhất 1 chữ hoa
                                </li>
                                <li data-requirement="lowercase">
                                    <i class="fas fa-times-circle"></i> Ít nhất 1 chữ thường
                                </li>
                                <li data-requirement="number">
                                    <i class="fas fa-times-circle"></i> Ít nhất 1 số
                                </li>
                                <li data-requirement="special">
                                    <i class="fas fa-times-circle"></i> Ít nhất 1 ký tự đặc biệt
                                </li>
                            </ul>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Xác nhận mật khẩu mới <span class="required">*</span></label>
                            <div class="password-field">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                <button type="button" class="toggle-password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <div class="password-match-message" id="password-match-message"></div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="submit-btn">Cập nhật mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const passwordInput = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Password strength meter
        const newPasswordInput = document.getElementById('new_password');
        const strengthMeterFill = document.querySelector('.strength-meter-fill');
        const strengthText = document.querySelector('.strength-text');
        const requirementItems = document.querySelectorAll('.password-requirements li');
        
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[^A-Za-z0-9]/.test(password)
                };
                
                // Update requirement items
                requirementItems.forEach(item => {
                    const requirement = item.getAttribute('data-requirement');
                    const icon = item.querySelector('i');
                    
                    if (requirements[requirement]) {
                        icon.classList.remove('fa-times-circle');
                        icon.classList.add('fa-check-circle');
                        item.classList.add('met');
                    } else {
                        icon.classList.remove('fa-check-circle');
                        icon.classList.add('fa-times-circle');
                        item.classList.remove('met');
                    }
                });
                
                // Calculate strength
                let strength = 0;
                const metRequirements = Object.values(requirements).filter(Boolean).length;
                
                if (password.length > 0) {
                    // Base strength on number of requirements met
                    strength = Math.min(100, metRequirements * 20);
                }
                
                // Update strength meter
                strengthMeterFill.style.width = `${strength}%`;
                strengthMeterFill.setAttribute('data-strength', Math.ceil(strength / 25));
                
                // Update strength text
                let strengthLabel = 'Chưa nhập';
                if (password.length > 0) {
                    if (strength <= 25) strengthLabel = 'Rất yếu';
                    else if (strength <= 50) strengthLabel = 'Yếu';
                    else if (strength <= 75) strengthLabel = 'Trung bình';
                    else strengthLabel = 'Mạnh';
                }
                
                strengthText.textContent = `Độ mạnh: ${strengthLabel}`;
                strengthText.setAttribute('data-strength', Math.ceil(strength / 25));
            });
        }
        
        // Password match validation
        const confirmPasswordInput = document.getElementById('confirm_password');
        const matchMessage = document.getElementById('password-match-message');
        const submitButton = document.getElementById('submit-btn');
        
        if (confirmPasswordInput && newPasswordInput) {
            const validatePasswordMatch = function() {
                const password = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword.length === 0) {
                    matchMessage.textContent = '';
                    matchMessage.className = '';
                } else if (password === confirmPassword) {
                    matchMessage.textContent = 'Mật khẩu khớp';
                    matchMessage.className = 'match-success';
                    return true;
                } else {
                    matchMessage.textContent = 'Mật khẩu không khớp';
                    matchMessage.className = 'match-error';
                    return false;
                }
            };
            
            confirmPasswordInput.addEventListener('input', validatePasswordMatch);
            newPasswordInput.addEventListener('input', function() {
                if (confirmPasswordInput.value.length > 0) {
                    validatePasswordMatch();
                }
            });
            
            // Form validation before submit
            document.querySelector('.password-form').addEventListener('submit', function(e) {
                const isMatchValid = validatePasswordMatch();
                const currentPassword = document.getElementById('current_password').value;
                
                if (!isMatchValid) {
                    e.preventDefault();
                    matchMessage.textContent = 'Mật khẩu không khớp';
                    matchMessage.className = 'match-error';
                }
                
                if (currentPassword.length === 0) {
                    e.preventDefault();
                    document.getElementById('current_password').classList.add('error');
                }
                
                // Check password strength
                const strengthValue = parseInt(strengthMeterFill.getAttribute('data-strength'));
                if (strengthValue < 3 && newPasswordInput.value.length > 0) {
                    e.preventDefault();
                    alert('Vui lòng chọn mật khẩu mạnh hơn để bảo vệ tài khoản của bạn.');
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
    
    .form-group {
        margin-bottom: 1.5rem;
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
    
    .form-control.error {
        border-color: #dc2626;
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
    
    .password-field {
        position: relative;
    }
    
    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--light-text);
        cursor: pointer;
    }
    
    .password-strength {
        margin-top: 0.75rem;
    }
    
    .strength-meter {
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .strength-meter-fill {
        height: 100%;
        width: 0;
        transition: width 0.3s ease;
    }
    
    .strength-meter-fill[data-strength="0"] {
        width: 0;
    }
    
    .strength-meter-fill[data-strength="1"] {
        width: 25%;
        background-color: #ef4444;
    }
    
    .strength-meter-fill[data-strength="2"] {
        width: 50%;
        background-color: #eab308;
    }
    
    .strength-meter-fill[data-strength="3"] {
        width: 75%;
        background-color: #3b82f6;
    }
    
    .strength-meter-fill[data-strength="4"] {
        width: 100%;
        background-color: #16a34a;
    }
    
    .strength-text {
        font-size: 0.85rem;
        color: var(--light-text);
    }
    
    .strength-text[data-strength="1"] {
        color: #ef4444;
    }
    
    .strength-text[data-strength="2"] {
        color: #eab308;
    }
    
    .strength-text[data-strength="3"] {
        color: #3b82f6;
    }
    
    .strength-text[data-strength="4"] {
        color: #16a34a;
    }
    
    .password-requirements {
        list-style: none;
        padding: 0;
        margin: 1rem 0 0;
    }
    
    .password-requirements li {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        color: var(--light-text);
    }
    
    .password-requirements li i {
        margin-right: 0.5rem;
        color: #ef4444;
    }
    
    .password-requirements li.met i {
        color: #16a34a;
    }
    
    .password-match-message {
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
    
    .match-success {
        color: #16a34a;
    }
    
    .match-error {
        color: #ef4444;
    }
    
    .form-actions {
        margin-top: 2rem;
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
    
    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-hover);
    }
    
    @media (max-width: 992px) {
        .account-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 