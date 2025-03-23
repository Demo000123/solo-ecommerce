<?php
// Get the requested URL
$requestedUrl = $_SERVER['REQUEST_URI'] ?? '';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="error-content">
                <div class="error-image mb-4">
                    <img src="/assets/images/404.svg" alt="404 Error" class="img-fluid" style="max-height: 300px;">
                </div>
                <h1 class="display-1 fw-bold text-primary mb-3">404</h1>
                <h2 class="mb-4">Page Not Found</h2>
                <p class="lead text-muted mb-4">
                    We couldn't find the page you were looking for. The page may have been moved, 
                    deleted, or never existed in the first place.
                </p>
                
                <?php if (!empty($requestedUrl)): ?>
                    <div class="alert alert-light border mb-4">
                        <p class="mb-0">
                            <strong>Requested URL:</strong> 
                            <span class="text-danger"><?= htmlspecialchars($requestedUrl) ?></span>
                        </p>
                    </div>
                <?php endif; ?>
                
                <div class="error-actions mb-5">
                    <a href="/" class="btn btn-primary me-3">
                        <i class="fas fa-home me-2"></i> Go to Homepage
                    </a>
                    <a href="/products" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-bag me-2"></i> Browse Products
                    </a>
                </div>
                
                <div class="helpful-links">
                    <h5 class="mb-3">You might be looking for:</h5>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <ul class="list-unstyled text-start">
                                <li><a href="/products" class="text-decoration-none"><i class="fas fa-angle-right me-2"></i> All Products</a></li>
                                <li><a href="/categories" class="text-decoration-none"><i class="fas fa-angle-right me-2"></i> Categories</a></li>
                                <li><a href="/cart" class="text-decoration-none"><i class="fas fa-angle-right me-2"></i> Shopping Cart</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul class="list-unstyled text-start">
                                <li><a href="/account/profile" class="text-decoration-none"><i class="fas fa-angle-right me-2"></i> My Account</a></li>
                                <li><a href="/account/orders" class="text-decoration-none"><i class="fas fa-angle-right me-2"></i> My Orders</a></li>
                                <li><a href="/contact" class="text-decoration-none"><i class="fas fa-angle-right me-2"></i> Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-content {
    padding: 3rem 0;
}

.error-image img {
    opacity: 0.85;
}

.helpful-links {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 0.5rem;
}

.helpful-links ul li {
    margin-bottom: 0.75rem;
}

.helpful-links ul li a {
    color: #495057;
    transition: all 0.2s ease;
}

.helpful-links ul li a:hover {
    color: var(--bs-primary);
    transform: translateX(3px);
}
</style> 