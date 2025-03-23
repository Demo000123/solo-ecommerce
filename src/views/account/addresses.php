<?php
// Address management page
require_once __DIR__ . '/../layouts/header.php';

// Get any error messages or success messages
$errors = $_SESSION['validation_errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];
$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? null;

// Clear session data
unset($_SESSION['validation_errors'], $_SESSION['old_input'], $_SESSION['success_message'], $_SESSION['error_message']);

// Get addresses and countries data
$addresses = $addresses ?? [];
$countries = $countries ?? [];
$states = $states ?? [];
$editAddress = $editAddress ?? null;
?>

<link rel="stylesheet" href="/public/css/account.css">

<!-- Page title and breadcrumbs -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title">Sổ địa chỉ</h1>
        <div class="breadcrumbs">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <a href="/account">Tài khoản</a>
            <span class="separator">/</span>
            <span class="current">Sổ địa chỉ</span>
        </div>
    </div>
</div>

<div class="account-container container">
    <?php if (!isset($user) || empty($user)): ?>
        <div class="not-authenticated">
            <p>Vui lòng <a href="/account">đăng nhập</a> để xem sổ địa chỉ.</p>
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
                    <li class="active">
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
                <div class="account-section addresses-section">
                    <div class="section-header">
                        <h2 class="section-title">Sổ địa chỉ</h2>
                        <button type="button" class="btn btn-primary btn-sm" id="add-address-btn">
                            <i class="fas fa-plus"></i> Thêm địa chỉ mới
                        </button>
                    </div>
                    
                    <?php if ($successMessage): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($successMessage) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($errorMessage): ?>
                        <div class="alert alert-error">
                            <?= htmlspecialchars($errorMessage) ?>
                            <?= $updateError ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($addresses)): ?>
                        <div class="empty-addresses">
                            <div class="empty-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <p>Bạn chưa có địa chỉ nào trong sổ địa chỉ.</p>
                            <button type="button" class="btn btn-outline" id="empty-add-address-btn">
                                Thêm địa chỉ mới
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="addresses-list">
                            <?php foreach ($addresses as $address): ?>
                                <div class="address-card <?= $address['is_default'] ? 'default' : '' ?>">
                                    <?php if ($address['is_default']): ?>
                                        <div class="default-badge">Mặc định</div>
                                    <?php endif; ?>
                                    
                                    <div class="address-content">
                                        <h3 class="address-name"><?= htmlspecialchars($address['name']) ?></h3>
                                        <div class="address-details">
                                            <p class="address-line">
                                                <i class="fas fa-user"></i>
                                                <?= htmlspecialchars($address['fullname']) ?>
                                            </p>
                                            <p class="address-line">
                                                <i class="fas fa-phone"></i>
                                                <?= htmlspecialchars($address['phone']) ?>
                                            </p>
                                            <p class="address-line">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <?= htmlspecialchars($address['address_line']) ?>, 
                                                <?= htmlspecialchars($address['ward']) ?>, 
                                                <?= htmlspecialchars($address['district']) ?>, 
                                                <?= htmlspecialchars($address['province']) ?>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="address-actions">
                                        <button type="button" class="btn btn-sm btn-outline edit-address-btn" 
                                                data-id="<?= $address['id'] ?>"
                                                data-name="<?= htmlspecialchars($address['name']) ?>"
                                                data-fullname="<?= htmlspecialchars($address['fullname']) ?>"
                                                data-phone="<?= htmlspecialchars($address['phone']) ?>"
                                                data-province="<?= htmlspecialchars($address['province']) ?>"
                                                data-district="<?= htmlspecialchars($address['district']) ?>"
                                                data-ward="<?= htmlspecialchars($address['ward']) ?>"
                                                data-address-line="<?= htmlspecialchars($address['address_line']) ?>"
                                                data-is-default="<?= $address['is_default'] ? 1 : 0 ?>">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                        
                                        <?php if (!$address['is_default']): ?>
                                            <form action="/account/addresses/set-default" method="POST" class="set-default-form">
                                                <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-text">
                                                    <i class="far fa-check-circle"></i> Đặt làm mặc định
                                                </button>
                                            </form>
                                            
                                            <form action="/account/addresses/delete" method="POST" class="delete-address-form"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                                                <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-text btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Xóa
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

<!-- Address Modal -->
<div class="modal" id="address-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Thêm địa chỉ mới</h3>
                <button type="button" class="close-modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="address-form" action="/account/addresses/save" method="POST">
                    <input type="hidden" name="address_id" id="address_id" value="">
                    
                    <div class="form-group">
                        <label for="address_name">Tên địa chỉ <span class="required">*</span></label>
                        <input type="text" id="address_name" name="name" class="form-control" required 
                               placeholder="Ví dụ: Nhà riêng, Cơ quan,...">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address_fullname">Họ và tên <span class="required">*</span></label>
                            <input type="text" id="address_fullname" name="fullname" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address_phone">Số điện thoại <span class="required">*</span></label>
                            <input type="tel" id="address_phone" name="phone" class="form-control" required 
                                   pattern="[0-9]{10}" title="Số điện thoại phải có 10 chữ số">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address_province">Tỉnh/Thành phố <span class="required">*</span></label>
                            <select id="address_province" name="province" class="form-control" required>
                                <option value="">Chọn Tỉnh/Thành phố</option>
                                <!-- Provinces will be loaded with JavaScript -->
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="address_district">Quận/Huyện <span class="required">*</span></label>
                            <select id="address_district" name="district" class="form-control" required disabled>
                                <option value="">Chọn Quận/Huyện</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address_ward">Phường/Xã <span class="required">*</span></label>
                            <select id="address_ward" name="ward" class="form-control" required disabled>
                                <option value="">Chọn Phường/Xã</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="address_line">Địa chỉ cụ thể <span class="required">*</span></label>
                            <input type="text" id="address_line" name="address_line" class="form-control" required 
                                   placeholder="Số nhà, tên đường,...">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-container">
                            <input type="checkbox" id="is_default" name="is_default" value="1">
                            <span class="checkbox-custom"></span>
                            <span>Đặt làm địa chỉ mặc định</span>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-outline cancel-btn">Hủy</button>
                        <button type="submit" class="btn btn-primary save-btn">Lưu địa chỉ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Address modal functionality
        const modal = document.getElementById('address-modal');
        const addBtn = document.getElementById('add-address-btn');
        const emptyAddBtn = document.getElementById('empty-add-address-btn');
        const closeBtn = document.querySelector('.close-modal');
        const cancelBtn = document.querySelector('.cancel-btn');
        const form = document.getElementById('address-form');
        const modalTitle = document.querySelector('.modal-title');
        const saveBtn = document.querySelector('.save-btn');
        
        // Open modal for new address
        const openAddressModal = function(isEdit = false) {
            modal.classList.add('show');
            document.body.classList.add('modal-open');
            
            if (!isEdit) {
                modalTitle.textContent = 'Thêm địa chỉ mới';
                saveBtn.textContent = 'Lưu địa chỉ';
                form.reset();
                document.getElementById('address_id').value = '';
                
                // Pre-fill with user's name and phone if it's a new address
                document.getElementById('address_fullname').value = '<?= $user['fullname'] ?? '' ?>';
                document.getElementById('address_phone').value = '<?= $user['phone'] ?? '' ?>';
            }
        };
        
        // Close modal
        const closeAddressModal = function() {
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
        };
        
        // Event listeners for opening/closing modal
        if (addBtn) addBtn.addEventListener('click', () => openAddressModal(false));
        if (emptyAddBtn) emptyAddBtn.addEventListener('click', () => openAddressModal(false));
        if (closeBtn) closeBtn.addEventListener('click', closeAddressModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeAddressModal);
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeAddressModal();
            }
        });
        
        // Edit address functionality
        const editButtons = document.querySelectorAll('.edit-address-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const fullname = this.getAttribute('data-fullname');
                const phone = this.getAttribute('data-phone');
                const province = this.getAttribute('data-province');
                const district = this.getAttribute('data-district');
                const ward = this.getAttribute('data-ward');
                const addressLine = this.getAttribute('data-address-line');
                const isDefault = this.getAttribute('data-is-default') === '1';
                
                // Fill form with address data
                document.getElementById('address_id').value = id;
                document.getElementById('address_name').value = name;
                document.getElementById('address_fullname').value = fullname;
                document.getElementById('address_phone').value = phone;
                document.getElementById('address_line').value = addressLine;
                document.getElementById('is_default').checked = isDefault;
                
                // Handle province/district/ward selection
                // Note: In a real implementation, you'd need to load the correct 
                // province, district, and ward based on saved values.
                // This would require fetching the location data and setting the selects.
                
                // Update modal title and button text
                modalTitle.textContent = 'Sửa địa chỉ';
                saveBtn.textContent = 'Cập nhật địa chỉ';
                
                // Open modal
                openAddressModal(true);
            });
        });
        
        // Fetch provinces on page load
        // In a real implementation, you would fetch from an API
        const provinceSelect = document.getElementById('address_province');
        const districtSelect = document.getElementById('address_district');
        const wardSelect = document.getElementById('address_ward');
        
        // Example of provinces data (in a real implementation, you would fetch this)
        const provincesData = [
            { id: 1, name: 'Hà Nội' },
            { id: 2, name: 'TP. Hồ Chí Minh' },
            { id: 3, name: 'Đà Nẵng' }
            // Add more provinces as needed
        ];
        
        // Example of districts data (in a real implementation, you would fetch this)
        const districtsData = {
            1: [
                { id: 101, name: 'Quận Ba Đình' },
                { id: 102, name: 'Quận Hoàn Kiếm' },
                { id: 103, name: 'Quận Tây Hồ' }
            ],
            2: [
                { id: 201, name: 'Quận 1' },
                { id: 202, name: 'Quận 2' },
                { id: 203, name: 'Quận 3' }
            ],
            3: [
                { id: 301, name: 'Quận Hải Châu' },
                { id: 302, name: 'Quận Thanh Khê' },
                { id: 303, name: 'Quận Liên Chiểu' }
            ]
        };
        
        // Example of wards data (in a real implementation, you would fetch this)
        const wardsData = {
            101: [
                { id: 10101, name: 'Phường Phúc Xá' },
                { id: 10102, name: 'Phường Trúc Bạch' }
            ],
            102: [
                { id: 10201, name: 'Phường Hàng Bạc' },
                { id: 10202, name: 'Phường Hàng Bồ' }
            ],
            201: [
                { id: 20101, name: 'Phường Bến Nghé' },
                { id: 20102, name: 'Phường Bến Thành' }
            ],
            // Add more wards as needed
        };
        
        // Load provinces
        provincesData.forEach(province => {
            const option = document.createElement('option');
            option.value = province.id;
            option.textContent = province.name;
            provinceSelect.appendChild(option);
        });
        
        // Handle province change
        provinceSelect.addEventListener('change', function() {
            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            
            const provinceId = this.value;
            if (provinceId) {
                districtSelect.disabled = false;
                
                const districts = districtsData[provinceId];
                if (districts) {
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                }
            } else {
                districtSelect.disabled = true;
                wardSelect.disabled = true;
            }
        });
        
        // Handle district change
        districtSelect.addEventListener('change', function() {
            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            
            const districtId = this.value;
            if (districtId) {
                wardSelect.disabled = false;
                
                const wards = wardsData[districtId];
                if (wards) {
                    wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.id;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                }
            } else {
                wardSelect.disabled = true;
            }
        });
        
        // Form validation
        form.addEventListener('submit', function(e) {
            let valid = true;
            
            const inputs = form.querySelectorAll('input[required], select[required]');
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('error');
                    valid = false;
                } else {
                    input.classList.remove('error');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin.');
            }
        });
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
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .section-title {
        font-size: 1.35rem;
        margin: 0;
        color: var(--heading-color);
    }
    
    .empty-addresses {
        text-align: center;
        padding: 3rem 1rem;
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        border: 1px dashed var(--border-color);
    }
    
    .empty-icon {
        font-size: 3rem;
        color: var(--light-text);
        margin-bottom: 1rem;
    }
    
    .empty-addresses p {
        color: var(--light-text);
        margin-bottom: 1.5rem;
    }
    
    .addresses-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .address-card {
        background-color: #f8fafc;
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        padding: 1.25rem;
        position: relative;
    }
    
    .address-card.default {
        border-color: var(--primary-color);
        background-color: rgba(163, 168, 116, 0.05);
    }
    
    .default-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: var(--primary-color);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .address-name {
        font-size: 1.1rem;
        margin: 0 0 0.75rem 0;
        color: var(--heading-color);
    }
    
    .address-details {
        margin-bottom: 1rem;
    }
    
    .address-line {
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
    }
    
    .address-line i {
        width: 20px;
        margin-right: 0.5rem;
        color: var(--light-text);
    }
    
    .address-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
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
    
    .checkbox-container {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .checkbox-container input[type="checkbox"] {
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
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1rem;
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
    
    .btn-text {
        background-color: transparent;
        color: var(--primary-color);
        padding-left: 0;
        padding-right: 0;
    }
    
    .btn-text:hover {
        text-decoration: underline;
    }
    
    .btn-danger {
        color: #ef4444;
    }
    
    .btn-danger:hover {
        color: #dc2626;
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow-y: auto;
        padding: 2rem 1rem;
    }
    
    .modal.show {
        display: block;
    }
    
    .modal-dialog {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .modal-content {
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    
    .modal-title {
        font-size: 1.25rem;
        color: var(--heading-color);
        margin: 0;
    }
    
    .close-modal {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: var(--light-text);
        cursor: pointer;
        padding: 0;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    body.modal-open {
        overflow: hidden;
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
        
        .address-actions {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
