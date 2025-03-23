<?php
/**
 * Product detail page template
 * 
 * Variables available:
 * $product - Product details array
 * $images - Array of product images
 * $attributes - Array of product attributes/specifications
 * $reviews - Array of product reviews
 * $averageRating - Average rating (0-5)
 * $relatedProducts - Array of related products
 */
?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/products">Products</a></li>
            <li class="breadcrumb-item"><a href="/category/<?= htmlspecialchars($product['category_slug']) ?>"><?= htmlspecialchars($product['category_name']) ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
        </ol>
    </nav>
    
    <div class="row mb-5">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
            <div class="product-gallery">
                <!-- Main Image -->
                <div class="main-image-container mb-3">
                    <img id="mainImage" src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded shadow-sm" onerror="this.src='/assets/images/placeholder-product.jpg'">
                    <?php if ($product['is_sale']): ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-3">Sale</span>
                    <?php endif; ?>
                    <?php if ($product['is_new']): ?>
                    <span class="badge bg-success position-absolute top-0 end-0 m-3">New</span>
                    <?php endif; ?>
                </div>
                
                <!-- Thumbnail Gallery -->
                <?php if (!empty($images)): ?>
                <div class="thumbnails d-flex">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-thumbnail me-2 active" onclick="changeMainImage(this.src)" onerror="this.src='/assets/images/placeholder-product.jpg'">
                    
                    <?php foreach ($images as $image): ?>
                    <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-thumbnail me-2" onclick="changeMainImage(this.src)" onerror="this.src='/assets/images/placeholder-product.jpg'">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="h2 mb-2"><?= htmlspecialchars($product['name']) ?></h1>
            
            <!-- Rating -->
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= round($averageRating)): ?>
                            <i class="fas fa-star"></i>
                        <?php elseif ($i - 0.5 <= $averageRating): ?>
                            <i class="fas fa-star-half-alt"></i>
                        <?php else: ?>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <span class="text-muted">(<?= count($reviews) ?> reviews)</span>
            </div>
            
            <!-- Price -->
            <div class="mb-3">
                <?php if ($product['discount_price']): ?>
                <div class="d-flex align-items-center">
                    <span class="text-danger fs-3 fw-bold me-3">$<?= number_format($product['discount_price'], 2) ?></span>
                    <s class="text-muted">$<?= number_format($product['price'], 2) ?></s>
                    <span class="badge bg-danger ms-2">Save <?= number_format(($product['price'] - $product['discount_price']) / $product['price'] * 100) ?>%</span>
                </div>
                <?php else: ?>
                <span class="fs-3 fw-bold">$<?= number_format($product['price'], 2) ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Stock Status -->
            <div class="mb-3">
                <?php if ($product['stock_quantity'] > 0): ?>
                <span class="badge bg-success">In Stock (<?= $product['stock_quantity'] ?> available)</span>
                <?php else: ?>
                <span class="badge bg-danger">Out of Stock</span>
                <?php endif; ?>
            </div>
            
            <!-- Short Description -->
            <div class="mb-4">
                <p><?= htmlspecialchars($product['short_description']) ?></p>
            </div>
            
            <!-- Add to Cart Form -->
            <form action="/cart/add" method="post" class="mb-4">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                
                <!-- Quantity Selector -->
                <div class="d-flex align-items-center mb-3">
                    <label for="quantity" class="me-3">Quantity:</label>
                    <div class="input-group input-group-sm" style="width: 130px;">
                        <button type="button" class="btn btn-outline-secondary quantity-minus"><i class="fas fa-minus"></i></button>
                        <input type="number" id="quantity" name="quantity" class="form-control text-center quantity-input" value="1" min="1" max="<?= $product['stock_quantity'] ?>" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>>
                        <button type="button" class="btn btn-outline-secondary quantity-plus"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?>>
                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="addToWishlist(<?= $product['id'] ?>)">
                        <i class="far fa-heart me-2"></i>Add to Wishlist
                    </button>
                </div>
            </form>
            
            <!-- Delivery Info -->
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h5 class="card-title h6"><i class="fas fa-truck me-2"></i>Delivery Information</h5>
                    <p class="card-text mb-0 small">Free shipping on orders over $50</p>
                    <p class="card-text mb-0 small">Usually ships within 24 hours</p>
                </div>
            </div>
            
            <!-- Social Share -->
            <div class="d-flex align-items-center">
                <span class="me-3">Share:</span>
                <a href="#" class="text-secondary me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-secondary me-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-secondary me-2"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="text-secondary"><i class="fas fa-envelope"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Product Tabs -->
    <div class="row mb-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Description</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Specifications</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Reviews (<?= count($reviews) ?>)</button>
                </li>
            </ul>
            <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabsContent">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <?= $product['description'] ?>
                </div>
                
                <!-- Specifications Tab -->
                <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                    <table class="table table-striped">
                        <tbody>
                            <?php foreach ($attributes as $attribute): ?>
                            <tr>
                                <th scope="row" style="width: 30%;"><?= htmlspecialchars($attribute['name']) ?></th>
                                <td><?= htmlspecialchars($attribute['value']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <!-- Review Summary -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-4 text-center">
                            <div class="display-4 fw-bold"><?= number_format($averageRating, 1) ?></div>
                            <div class="text-warning mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= round($averageRating)): ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif ($i - 0.5 <= $averageRating): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="text-muted small"><?= count($reviews) ?> reviews</div>
                        </div>
                        
                        <div class="flex-grow-1">
                            <?php 
                            $ratingCounts = array_fill(1, 5, 0);
                            foreach ($reviews as $review) {
                                $ratingCounts[$review['rating']]++;
                            }
                            for ($i = 5; $i >= 1; $i--): 
                                $percentage = count($reviews) > 0 ? round($ratingCounts[$i] / count($reviews) * 100) : 0;
                            ?>
                            <div class="d-flex align-items-center mb-1">
                                <div class="text-muted small me-3" style="width: 40px;"><?= $i ?> stars</div>
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="text-muted small ms-3"><?= $percentage ?>%</div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <!-- Write Review Button -->
                    <div class="mb-4">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                            <i class="fas fa-edit me-2"></i>Write a Review
                        </button>
                    </div>
                    
                    <!-- Review List -->
                    <?php if (!empty($reviews)): ?>
                    <div class="review-list">
                        <?php foreach ($reviews as $review): ?>
                        <div class="review-item card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="card-title h6 mb-0"><?= htmlspecialchars($review['title']) ?></h5>
                                    <div class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="text-muted small me-3">By <?= htmlspecialchars($review['customer_name']) ?></div>
                                    <div class="text-muted small">on <?= date('M d, Y', strtotime($review['created_at'])) ?></div>
                                </div>
                                <p class="card-text"><?= htmlspecialchars($review['comment']) ?></p>
                                
                                <?php if (!empty($review['response'])): ?>
                                <div class="admin-response bg-light p-3 mt-3 rounded">
                                    <div class="text-primary mb-2">Response from Solo E-commerce:</div>
                                    <p class="mb-0"><?= htmlspecialchars($review['response']) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p>There are no reviews yet. Be the first to review this product!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="row">
        <div class="col-12">
            <h2 class="h4 mb-4">Related Products</h2>
            
            <div class="row row-cols-2 row-cols-md-4 g-4">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="col">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars($relatedProduct['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($relatedProduct['name']) ?>" onerror="this.src='/assets/images/placeholder-product.jpg'">
                            <?php if ($relatedProduct['is_sale']): ?>
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                            <?php endif; ?>
                            <?php if ($relatedProduct['is_new']): ?>
                            <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($relatedProduct['name']) ?></h5>
                            <p class="card-text text-muted small"><?= htmlspecialchars($relatedProduct['category_name']) ?></p>
                            <div class="mt-auto">
                                <?php if ($relatedProduct['discount_price']): ?>
                                <div class="d-flex align-items-center mb-2">
                                    <span class="text-danger fw-bold me-2">$<?= number_format($relatedProduct['discount_price'], 2) ?></span>
                                    <s class="text-muted small">$<?= number_format($relatedProduct['price'], 2) ?></s>
                                </div>
                                <?php else: ?>
                                <p class="fw-bold mb-2">$<?= number_format($relatedProduct['price'], 2) ?></p>
                                <?php endif; ?>
                                
                                <div class="d-grid gap-2">
                                    <a href="/product/<?= htmlspecialchars($relatedProduct['slug']) ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                                    <button class="btn btn-primary btn-sm add-to-cart-btn" data-product-id="<?= $relatedProduct['id'] ?>">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
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
                <form action="/product/review" method="post" id="reviewForm">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <div class="rating-stars">
                            <div class="d-flex">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="me-2">
                                    <input type="radio" id="rating<?= $i ?>" name="rating" value="<?= $i ?>" class="d-none" required>
                                    <label for="rating<?= $i ?>" class="rating-star text-muted">
                                        <i class="far fa-star" data-rating="<?= $i ?>"></i>
                                    </label>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reviewTitle" class="form-label">Review Title</label>
                        <input type="text" class="form-control" id="reviewTitle" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reviewComment" class="form-label">Your Review</label>
                        <textarea class="form-control" id="reviewComment" name="comment" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reviewName" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="reviewName" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reviewEmail" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="reviewEmail" name="email" required>
                        <div class="form-text">Your email will not be published.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('reviewForm').submit()">Submit Review</button>
            </div>
        </div>
    </div>
</div>

<style>
    .main-image-container {
        position: relative;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        overflow: hidden;
    }
    
    .main-image-container img {
        max-height: 100%;
        object-fit: contain;
    }
    
    .thumbnails img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .thumbnails img:hover, .thumbnails img.active {
        border-color: var(--primary-color);
    }
    
    .rating-star {
        cursor: pointer;
        font-size: 1.5rem;
    }
    
    .rating-star:hover i, .rating-star.active i {
        color: #ffc107;
    }
</style>

<script>
    function changeMainImage(src) {
        document.getElementById('mainImage').src = src;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnails img').forEach(img => {
            img.classList.remove('active');
            if (img.src === src) {
                img.classList.add('active');
            }
        });
    }
    
    function addToWishlist(productId) {
        fetch('/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Product added to wishlist', 'success');
            } else {
                showNotification(data.message || 'Failed to add product to wishlist', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    // Rating stars in review form
    document.addEventListener('DOMContentLoaded', function() {
        const ratingStars = document.querySelectorAll('.rating-star');
        
        ratingStars.forEach(star => {
            star.addEventListener('mouseover', function() {
                const rating = parseInt(this.querySelector('i').getAttribute('data-rating'));
                
                ratingStars.forEach(s => {
                    const starRating = parseInt(s.querySelector('i').getAttribute('data-rating'));
                    
                    if (starRating <= rating) {
                        s.querySelector('i').classList.remove('far');
                        s.querySelector('i').classList.add('fas');
                    } else {
                        s.querySelector('i').classList.remove('fas');
                        s.querySelector('i').classList.add('far');
                    }
                });
            });
            
            star.addEventListener('click', function() {
                const rating = parseInt(this.querySelector('i').getAttribute('data-rating'));
                document.querySelector(`#rating${rating}`).checked = true;
                
                ratingStars.forEach(s => {
                    s.classList.remove('active');
                    const starRating = parseInt(s.querySelector('i').getAttribute('data-rating'));
                    
                    if (starRating <= rating) {
                        s.classList.add('active');
                    }
                });
            });
            
            star.addEventListener('mouseout', function() {
                ratingStars.forEach(s => {
                    if (!s.classList.contains('active')) {
                        s.querySelector('i').classList.remove('fas');
                        s.querySelector('i').classList.add('far');
                    }
                });
            });
        });
        
        // Quantity buttons
        const quantityInput = document.querySelector('.quantity-input');
        const minusBtn = document.querySelector('.quantity-minus');
        const plusBtn = document.querySelector('.quantity-plus');
        
        if (minusBtn && quantityInput) {
            minusBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value > 1) {
                    quantityInput.value = value - 1;
                }
            });
        }
        
        if (plusBtn && quantityInput) {
            plusBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                let max = parseInt(quantityInput.getAttribute('max') || 100);
                
                if (value < max) {
                    quantityInput.value = value + 1;
                }
            });
        }
    });
</script> 