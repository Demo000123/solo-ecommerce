<?php
// Get data from the controller
$product = $product ?? null;
$relatedProducts = $relatedProducts ?? [];
$reviews = $reviews ?? [];
$avgRating = $avgRating ?? 0;
$reviewCount = $reviewCount ?? 0;
$userReview = $userReview ?? null;
$isLoggedIn = isset($_SESSION['user_id']);

// If product not found
if (!$product) {
    echo '<div class="container my-5"><div class="alert alert-danger">Product not found.</div></div>';
    return;
}
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/products">Products</a></li>
            <?php if (!empty($product['category_name'])): ?>
            <li class="breadcrumb-item"><a href="/category/<?= $product['category_id'] ?>"><?= htmlspecialchars($product['category_name']) ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <div class="product-images">
                <div class="main-image-container mb-3">
                    <img src="<?= !empty($product['image']) ? '/uploads/products/' . $product['image'] : '/assets/images/products/placeholder.jpg' ?>" 
                         class="img-fluid main-image" alt="<?= htmlspecialchars($product['name']) ?>">
                </div>
                
                <?php if (!empty($product['gallery_images'])): ?>
                <div class="row thumbnail-container">
                    <?php foreach (explode(',', $product['gallery_images']) as $image): ?>
                    <div class="col-3">
                        <img src="<?= '/uploads/products/' . trim($image) ?>" class="img-thumbnail thumbnail-image" 
                             alt="<?= htmlspecialchars($product['name']) ?> thumbnail" 
                             onclick="changeMainImage(this.src)">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="col-lg-6">
            <h1 class="mb-3"><?= htmlspecialchars($product['name']) ?></h1>
            
            <!-- Product Rating -->
            <div class="mb-3">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if ($i <= round($avgRating)): ?>
                        <i class="fas fa-star text-warning"></i>
                    <?php else: ?>
                        <i class="far fa-star text-warning"></i>
                    <?php endif; ?>
                <?php endfor; ?>
                <span class="ms-2"><?= number_format($avgRating, 1) ?> (<?= $reviewCount ?> reviews)</span>
            </div>
            
            <!-- Product Price -->
            <div class="mb-3">
                <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                    <span class="text-muted text-decoration-line-through h5"><?= number_format($product['price'], 0) ?></span>
                    <span class="text-danger fw-bold h4"><?= number_format($product['sale_price'], 0) ?></span>
                    <span class="badge bg-danger ms-2">
                        <?= ceil(($product['price'] - $product['sale_price']) / $product['price'] * 100) ?>% OFF
                    </span>
                <?php else: ?>
                    <span class="fw-bold h4"><?= number_format($product['price'], 0) ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Product Description -->
            <div class="mb-4">
                <h5>Description</h5>
                <p><?= nl2br(htmlspecialchars($product['short_description'])) ?></p>
            </div>
            
            <!-- Stock Status -->
            <div class="mb-4">
                <h5>Availability</h5>
                <?php if ($product['stock_quantity'] > 0): ?>
                    <p class="text-success">
                        <i class="fas fa-check-circle"></i> In Stock
                        <?php if ($product['stock_quantity'] < 10): ?>
                            <span class="text-warning">(Only <?= $product['stock_quantity'] ?> left)</span>
                        <?php endif; ?>
                    </p>
                <?php else: ?>
                    <p class="text-danger"><i class="fas fa-times-circle"></i> Out of Stock</p>
                <?php endif; ?>
            </div>
            
            <!-- Add to Cart Form -->
            <?php if ($product['stock_quantity'] > 0): ?>
                <form id="addToCartForm" class="mb-4">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div class="d-flex align-items-center mb-3">
                        <label for="quantity" class="me-3">Quantity:</label>
                        <div class="input-group" style="width: 150px;">
                            <button type="button" class="btn btn-outline-secondary qty-btn" data-action="decrease">-</button>
                            <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="<?= $product['stock_quantity'] ?>">
                            <button type="button" class="btn btn-outline-secondary qty-btn" data-action="increase">+</button>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">Add to Cart</button>
                        <button type="button" id="buyNowBtn" class="btn btn-outline-primary">Buy Now</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    This product is currently out of stock. Please check back later or browse similar products.
                </div>
            <?php endif; ?>
            
            <!-- Additional Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="productTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">Shipping</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="returns-tab" data-bs-toggle="tab" data-bs-target="#returns" type="button" role="tab">Returns</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabContent">
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <?= nl2br(htmlspecialchars($product['description'] ?? '')) ?>
                            
                            <?php if (!empty($product['specifications'])): ?>
                                <h5 class="mt-3">Specifications</h5>
                                <ul>
                                    <?php foreach (json_decode($product['specifications'], true) ?? [] as $key => $value): ?>
                                        <li><strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars($value) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        
                        <div class="tab-pane fade" id="shipping" role="tabpanel">
                            <h5>Shipping Information</h5>
                            <p>Orders typically ship within 1-2 business days. Delivery times vary by location.</p>
                            <p>Free shipping on orders over $50.</p>
                        </div>
                        
                        <div class="tab-pane fade" id="returns" role="tabpanel">
                            <h5>Return Policy</h5>
                            <p>Items can be returned within 30 days of delivery for a full refund.</p>
                            <p>Products must be unused and in original packaging.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    <div class="my-5">
        <h3 class="mb-4">Customer Reviews</h3>
        
        <!-- Review Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center mb-2">
                    <h2 class="me-3 mb-0"><?= number_format($avgRating, 1) ?></h2>
                    <div>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= round($avgRating)): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php else: ?>
                                <i class="far fa-star text-warning"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <div class="small text-muted"><?= $reviewCount ?> reviews</div>
                    </div>
                </div>
                
                <?php if (!$userReview && $isLoggedIn): ?>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        Write a Review
                    </button>
                <?php elseif (!$isLoggedIn): ?>
                    <a href="/login?redirect=<?= urlencode('/products/' . $product['id']) ?>" class="btn btn-outline-primary">
                        Login to Write a Review
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Rating Distribution -->
            <div class="col-md-8">
                <?php
                $ratingDistribution = [
                    5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0
                ];
                
                foreach ($reviews as $review) {
                    if (isset($ratingDistribution[$review['rating']])) {
                        $ratingDistribution[$review['rating']]++;
                    }
                }
                
                for ($i = 5; $i >= 1; $i--):
                    $percentage = $reviewCount > 0 ? ($ratingDistribution[$i] / $reviewCount * 100) : 0;
                ?>
                <div class="d-flex align-items-center mb-2">
                    <div style="width: 60px;"><?= $i ?> stars</div>
                    <div class="progress flex-grow-1 mx-2" style="height: 10px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percentage ?>%"></div>
                    </div>
                    <div style="width: 60px;"><?= $ratingDistribution[$i] ?> reviews</div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        
        <!-- Review List -->
        <?php if (empty($reviews)): ?>
            <div class="alert alert-info">There are no reviews yet for this product.</div>
        <?php else: ?>
            <div class="review-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <h5 class="mb-0"><?= htmlspecialchars($review['title']) ?></h5>
                                    <div class="text-muted small">By <?= htmlspecialchars($review['username'] ?? 'Anonymous') ?> on <?= date('F j, Y', strtotime($review['created_at'])) ?></div>
                                </div>
                                <div>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $review['rating']): ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-warning"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($review['content'])) ?></p>
                            
                            <?php if ($isLoggedIn && $review['user_id'] == $_SESSION['user_id']): ?>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-sm btn-outline-secondary edit-review-btn" 
                                            data-review-id="<?= $review['id'] ?>"
                                            data-rating="<?= $review['rating'] ?>"
                                            data-title="<?= htmlspecialchars($review['title']) ?>"
                                            data-content="<?= htmlspecialchars($review['content']) ?>">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger delete-review-btn" 
                                            data-review-id="<?= $review['id'] ?>">
                                        Delete
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="my-5">
        <h3 class="mb-4">Related Products</h3>
        <div class="row">
            <?php foreach ($relatedProducts as $related): ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 product-card">
                        <?php if ($related['sale_price'] && $related['sale_price'] < $related['price']): ?>
                            <div class="badge bg-danger position-absolute top-0 end-0 m-2">Sale</div>
                        <?php endif; ?>
                        
                        <img src="<?= !empty($related['image']) ? '/uploads/products/' . $related['image'] : '/assets/images/products/placeholder.jpg' ?>" 
                            class="card-img-top" alt="<?= htmlspecialchars($related['name']) ?>">
                        
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($related['name']) ?></h5>
                            
                            <div class="mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= round($related['avg_rating'] ?? 0)): ?>
                                        <i class="fas fa-star text-warning small"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning small"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($related['sale_price'] && $related['sale_price'] < $related['price']): ?>
                                    <div>
                                        <span class="text-muted text-decoration-line-through"><?= number_format($related['price'], 0) ?></span>
                                        <span class="text-danger fw-bold"><?= number_format($related['sale_price'], 0) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="fw-bold"><?= number_format($related['price'], 0) ?></span>
                                <?php endif; ?>
                                <a href="/products/<?= $related['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="hidden" name="review_id" id="reviewId" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="far fa-star star-rating" data-rating="<?= $i ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="0">
                    </div>
                    
                    <div class="mb-3">
                        <label for="reviewTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="reviewTitle" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reviewContent" class="form-label">Review</label>
                        <textarea class="form-control" id="reviewContent" name="content" rows="4" required></textarea>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Review Confirmation Modal -->
<div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete your review? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteReview">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Product image gallery
        function changeMainImage(src) {
            document.querySelector('.main-image').src = src;
        }
        
        // Quantity buttons
        const quantityInput = document.getElementById('quantity');
        const maxQuantity = <?= $product['stock_quantity'] ?? 0 ?>;
        
        document.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', function() {
                let currentQty = parseInt(quantityInput.value);
                if (this.dataset.action === 'increase' && currentQty < maxQuantity) {
                    quantityInput.value = currentQty + 1;
                } else if (this.dataset.action === 'decrease' && currentQty > 1) {
                    quantityInput.value = currentQty - 1;
                }
            });
        });
        
        // Add to cart form
        const addToCartForm = document.getElementById('addToCartForm');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const productId = this.querySelector('[name="product_id"]').value;
                const quantity = this.querySelector('[name="quantity"]').value;
                
                addToCart(productId, quantity);
            });
        }
        
        // Buy now button
        const buyNowBtn = document.getElementById('buyNowBtn');
        if (buyNowBtn) {
            buyNowBtn.addEventListener('click', function() {
                const productId = document.querySelector('[name="product_id"]').value;
                const quantity = document.querySelector('[name="quantity"]').value;
                
                addToCart(productId, quantity, true);
            });
        }
        
        // Rating stars in review form
        const stars = document.querySelectorAll('.star-rating');
        const ratingInput = document.getElementById('ratingInput');
        
        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const rating = this.dataset.rating;
                highlightStars(rating);
            });
            
            star.addEventListener('mouseout', function() {
                const rating = ratingInput.value;
                highlightStars(rating);
            });
            
            star.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingInput.value = rating;
                highlightStars(rating);
            });
        });
        
        function highlightStars(rating) {
            stars.forEach(star => {
                const starRating = star.dataset.rating;
                if (starRating <= rating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                }
            });
        }
        
        // Review form submission
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const isEditMode = formData.get('review_id') !== '';
                
                fetch(isEditMode ? '/reviews/update' : '/reviews/create', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to submit review');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        }
        
        // Edit review
        document.querySelectorAll('.edit-review-btn').forEach(button => {
            button.addEventListener('click', function() {
                const reviewId = this.dataset.reviewId;
                const rating = this.dataset.rating;
                const title = this.dataset.title;
                const content = this.dataset.content;
                
                // Set form values
                document.getElementById('reviewId').value = reviewId;
                document.getElementById('ratingInput').value = rating;
                document.getElementById('reviewTitle').value = title;
                document.getElementById('reviewContent').value = content;
                
                // Highlight stars
                highlightStars(rating);
                
                // Update modal title
                document.getElementById('reviewModalLabel').textContent = 'Edit Review';
                
                // Show modal
                new bootstrap.Modal(document.getElementById('reviewModal')).show();
            });
        });
        
        // Delete review
        let reviewToDelete = null;
        
        document.querySelectorAll('.delete-review-btn').forEach(button => {
            button.addEventListener('click', function() {
                reviewToDelete = this.dataset.reviewId;
                new bootstrap.Modal(document.getElementById('deleteReviewModal')).show();
            });
        });
        
        document.getElementById('confirmDeleteReview').addEventListener('click', function() {
            if (reviewToDelete) {
                fetch('/reviews/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `review_id=${reviewToDelete}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to delete review');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
        });
        
        // Add to cart function
        function addToCart(productId, quantity, redirect = false) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `product_id=${productId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count if available
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cartCount;
                        cartCountElement.style.display = data.cartCount > 0 ? 'inline-block' : 'none';
                    }
                    
                    if (redirect) {
                        window.location.href = '/checkout';
                    } else {
                        alert('Product added to cart!');
                    }
                } else {
                    alert(data.message || 'Failed to add product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    });
    
    // Make changeMainImage function global for image thumbnails
    function changeMainImage(src) {
        document.querySelector('.main-image').src = src;
    }
</script> 