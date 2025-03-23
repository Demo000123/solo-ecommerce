<?php
$settingService = new \App\Services\SettingService();
$siteName = $settingService->getSetting('site_name', 'Solo E-commerce');
$categories = (new \App\Services\CategoryService())->getAllCategories();

// Get cart count
$cartCount = 0;
$sessionId = session_id();
$userId = $_SESSION['user_id'] ?? null;

if ($sessionId || $userId) {
    $cartService = new \App\Services\CartService();
    $cartCount = $cartService->getCartItemCount($userId, $sessionId);
}
?>

<header class="bg-dark text-white">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/"><?= htmlspecialchars($siteName) ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php foreach ($categories as $category): ?>
                                <li><a class="dropdown-item" href="/category/<?= $category['slug'] ?>"><?= htmlspecialchars($category['name']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/products">All Products</a>
                        </li>
                    </ul>
                    <form class="d-flex me-3" action="/search" method="get">
                        <input class="form-control me-2" type="search" name="q" placeholder="Search products..." aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="/cart">
                                <i class="fas fa-shopping-cart"></i> Cart
                                <?php if ($cartCount > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $cartCount ?>
                                </span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user"></i> Account
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/account/profile">My Profile</a></li>
                                <li><a class="dropdown-item" href="/account/orders">My Orders</a></li>
                                <li><a class="dropdown-item" href="/account/addresses">My Addresses</a></li>
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/admin/dashboard">Admin Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                            </ul>
                        </li>
                        <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Register</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header> 