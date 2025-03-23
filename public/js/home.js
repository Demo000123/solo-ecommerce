document.addEventListener('DOMContentLoaded', function() {
    // Initialize 3D tilt effect on product cards
    initTiltEffect();
    
    // Initialize animations for hero products
    initHeroProducts();
    
    // Initialize scroll animations
    initScrollAnimations();
    
    // Initialize category cards hover effects
    initCategoryCards();
});

/**
 * Initialize 3D tilt effect on product cards
 */
function initTiltEffect() {
    const productCards = document.querySelectorAll('.product-card-3d');
    
    if (productCards.length > 0 && typeof VanillaTilt !== 'undefined') {
        VanillaTilt.init(productCards, {
            max: 15,
            speed: 400,
            glare: true,
            "max-glare": 0.3,
            scale: 1.05
        });
    } else {
        // Fallback for when VanillaTilt is not available
        productCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    }
}

/**
 * Initialize animations for hero floating products
 */
function initHeroProducts() {
    const floatingProducts = document.querySelectorAll('.floating-product');
    
    floatingProducts.forEach((product, index) => {
        // Set different animation delays for each product
        product.style.animationDelay = (index * 0.5) + 's';
        
        // Add random rotation to each product
        const randomRotation = Math.floor(Math.random() * 10) - 5;
        product.style.transform = `rotate(${randomRotation}deg)`;
    });
}

/**
 * Initialize scroll animations for sections
 */
function initScrollAnimations() {
    // Check if the Intersection Observer API is supported
    if ('IntersectionObserver' in window) {
        const sections = document.querySelectorAll('.section-header, .categories-grid, .featured-slider, .category-showcase, .features-section, .newsletter-section');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });
        
        sections.forEach(section => {
            section.classList.add('will-animate');
            observer.observe(section);
        });
    }
}

/**
 * Initialize category cards hover effects
 */
function initCategoryCards() {
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(card => {
        const icon = card.querySelector('.category-icon');
        
        card.addEventListener('mouseenter', function() {
            if (icon) {
                icon.innerHTML = icon.innerHTML.replace('far', 'fas');
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (icon) {
                icon.innerHTML = icon.innerHTML.replace('fas', 'far');
            }
        });
    });
}

/**
 * Lazy load product images
 */
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers without Intersection Observer support
        images.forEach(img => {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        });
    }
});

/**
 * Add to cart functionality
 */
function addToCart(productId) {
    // You'll implement the actual cart functionality with AJAX
    const notification = document.createElement('div');
    notification.className = 'cart-notification';
    notification.innerHTML = '<i class="fas fa-check"></i> Product added to cart';
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide and remove notification
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 2000);
    
    // Send request to server (this would be your actual implementation)
    /*
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        // Update cart count in header
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = data.total_items;
        }
    });
    */
}

/**
 * Newsletter subscription handling
 */
function subscribeNewsletter(event) {
    event.preventDefault();
    
    const form = event.target;
    const emailInput = form.querySelector('input[type="email"]');
    const email = emailInput.value;
    
    if (!email || !validateEmail(email)) {
        showFormError(emailInput, 'Please enter a valid email address');
        return;
    }
    
    // Replace form with success message
    const formContainer = form.parentElement;
    formContainer.innerHTML = `
        <div class="newsletter-success">
            <i class="fas fa-check-circle"></i>
            <h3>Thank you for subscribing!</h3>
            <p>You'll receive our latest updates and exclusive offers.</p>
        </div>
    `;
    
    // You would normally send this to the server
    /*
    fetch('/newsletter/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            email: email
        })
    });
    */
}

/**
 * Email validation helper
 */
function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/**
 * Form error display helper
 */
function showFormError(inputElement, message) {
    const errorElement = document.createElement('div');
    errorElement.className = 'form-error';
    errorElement.textContent = message;
    
    // Remove any existing error message
    const existingError = inputElement.parentElement.querySelector('.form-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error message after the input
    inputElement.parentElement.appendChild(errorElement);
    
    // Add error class to input
    inputElement.classList.add('input-error');
    
    // Remove error after 3 seconds
    setTimeout(() => {
        if (errorElement.parentElement) {
            errorElement.remove();
            inputElement.classList.remove('input-error');
        }
    }, 3000);
}

/**
 * Add CSS animation classes
 */
const style = document.createElement('style');
style.textContent = `
    .will-animate {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    .animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    .cart-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: var(--primary-color);
        color: white;
        padding: 15px 20px;
        border-radius: var(--border-radius-md);
        box-shadow: var(--box-shadow-md);
        display: flex;
        align-items: center;
        gap: 10px;
        transform: translateY(100px);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
        z-index: 1000;
    }
    
    .cart-notification.show {
        transform: translateY(0);
        opacity: 1;
    }
    
    .form-error {
        color: var(--secondary-color);
        font-size: 0.875rem;
        margin-top: 5px;
    }
    
    .input-error {
        border-color: var(--secondary-color) !important;
    }
    
    .newsletter-success {
        text-align: center;
        animation: fadeIn 0.5s ease;
    }
    
    .newsletter-success i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
`;
document.head.appendChild(style); 