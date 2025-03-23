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

<?php
// Get any error messages or old input values
$errors = $_SESSION['validation_errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];
$successMessage = $_SESSION['success_message'] ?? null;
$errorMessage = $_SESSION['error_message'] ?? null;

// Clear session data
unset($_SESSION['validation_errors'], $_SESSION['old_input'], $_SESSION['success_message'], $_SESSION['error_message']);

// Get user data
$user = $user ?? [];

// Set active tab
$activeTab = $_GET['tab'] ?? 'info';
?>

<div class="container my-5">
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
                    <a href="/account/profile" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i> My Profile
                    </a>
                    <a href="/account/orders" class="list-group-item list-group-item-action">
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
        
        <!-- Profile Content -->
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
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link <?= $activeTab === 'info' ? 'active' : '' ?>" 
                               href="/account/profile?tab=info">Personal Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $activeTab === 'password' ? 'active' : '' ?>" 
                               href="/account/profile?tab=password">Change Password</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <!-- Personal Information Form -->
                    <?php if ($activeTab === 'info'): ?>
                        <h4 class="mb-4">Personal Information</h4>
                        <form action="/account/profile/update" method="post">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" 
                                           id="first_name" name="first_name" 
                                           value="<?= htmlspecialchars($oldInput['first_name'] ?? $user['first_name'] ?? '') ?>" required>
                                    <?php if (isset($errors['first_name'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['first_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" 
                                           id="last_name" name="last_name" 
                                           value="<?= htmlspecialchars($oldInput['last_name'] ?? $user['last_name'] ?? '') ?>" required>
                                    <?php if (isset($errors['last_name'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['last_name']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" 
                                       value="<?= htmlspecialchars($oldInput['email'] ?? $user['email'] ?? '') ?>" required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                       id="phone" name="phone" 
                                       value="<?= htmlspecialchars($oldInput['phone'] ?? $user['phone'] ?? '') ?>">
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['phone']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control <?= isset($errors['birth_date']) ? 'is-invalid' : '' ?>" 
                                       id="birth_date" name="birth_date" 
                                       value="<?= htmlspecialchars($oldInput['birth_date'] ?? $user['birth_date'] ?? '') ?>">
                                <?php if (isset($errors['birth_date'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['birth_date']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Gender</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male"
                                               <?= (($oldInput['gender'] ?? $user['gender'] ?? '') === 'male') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="gender_male">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female"
                                               <?= (($oldInput['gender'] ?? $user['gender'] ?? '') === 'female') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="gender_female">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="gender_other" value="other"
                                               <?= (($oldInput['gender'] ?? $user['gender'] ?? '') === 'other') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="gender_other">Other</label>
                                    </div>
                                </div>
                                <?php if (isset($errors['gender'])): ?>
                                    <div class="text-danger mt-1">
                                        <?= htmlspecialchars($errors['gender']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" value="1"
                                       <?= (($oldInput['newsletter'] ?? $user['newsletter'] ?? '') == 1) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="newsletter">
                                    Subscribe to our newsletter to receive updates on new products and special offers
                                </label>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </form>
                    
                    <!-- Change Password Form -->
                    <?php elseif ($activeTab === 'password'): ?>
                        <h4 class="mb-4">Change Password</h4>
                        <form action="/account/profile/password" method="post">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?= isset($errors['current_password']) ? 'is-invalid' : '' ?>" 
                                           id="current_password" name="current_password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if (isset($errors['current_password'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['current_password']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?= isset($errors['new_password']) ? 'is-invalid' : '' ?>" 
                                           id="new_password" name="new_password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if (isset($errors['new_password'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['new_password']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-text">Password must be at least 8 characters long with at least one uppercase letter, one lowercase letter, one number, and one special character.</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="new_password_confirm" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control <?= isset($errors['new_password_confirm']) ? 'is-invalid' : '' ?>" 
                                           id="new_password_confirm" name="new_password_confirm" required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if (isset($errors['new_password_confirm'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['new_password_confirm']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle current password visibility
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    if (toggleCurrentPassword) {
        const currentPassword = document.getElementById('current_password');
        toggleCurrentPassword.addEventListener('click', function() {
            const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            currentPassword.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Toggle new password visibility
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    if (toggleNewPassword) {
        const newPassword = document.getElementById('new_password');
        toggleNewPassword.addEventListener('click', function() {
            const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            newPassword.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Toggle confirm new password visibility
    const toggleNewPasswordConfirm = document.getElementById('toggleNewPasswordConfirm');
    if (toggleNewPasswordConfirm) {
        const newPasswordConfirm = document.getElementById('new_password_confirm');
        toggleNewPasswordConfirm.addEventListener('click', function() {
            const type = newPasswordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            newPasswordConfirm.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?> 