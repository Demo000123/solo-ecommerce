/**
 * Solo E-commerce - Main JavaScript File
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Initialize Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    });
    
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantity = this.getAttribute('data-quantity') || 1;
            
            addToCart(productId, quantity);
        });
    });
    
    function addToCart(productId, quantity) {
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
                // Show success message
                showNotification('Product added to cart', 'success');
                
                // Update cart count in navbar if available
                updateCartCount(data.cartCount);
            } else {
                showNotification(data.message || 'Failed to add product to cart', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    function updateCartCount(count) {
        const cartCountBadge = document.querySelector('.cart-count');
        if (cartCountBadge) {
            cartCountBadge.textContent = count;
            cartCountBadge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }
    
    // Quantity input handling
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityInputs.forEach(input => {
        const minusBtn = input.parentElement.querySelector('.quantity-minus');
        const plusBtn = input.parentElement.querySelector('.quantity-plus');
        
        if (minusBtn) {
            minusBtn.addEventListener('click', function() {
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    if (input.hasAttribute('data-cart-item')) {
                        updateCartItem(input.getAttribute('data-cart-item'), input.value);
                    }
                }
            });
        }
        
        if (plusBtn) {
            plusBtn.addEventListener('click', function() {
                let value = parseInt(input.value);
                let max = parseInt(input.getAttribute('max') || 100);
                
                if (value < max) {
                    input.value = value + 1;
                    if (input.hasAttribute('data-cart-item')) {
                        updateCartItem(input.getAttribute('data-cart-item'), input.value);
                    }
                }
            });
        }
    });
    
    function updateCartItem(itemId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `item_id=${itemId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart totals if available
                if (data.subtotal) {
                    document.getElementById('cart-subtotal').textContent = data.subtotal;
                }
                
                if (data.total) {
                    document.getElementById('cart-total').textContent = data.total;
                }
                
                if (data.itemTotal) {
                    document.getElementById('item-total-' + itemId).textContent = data.itemTotal;
                }
            } else {
                showNotification(data.message || 'Failed to update cart', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }
    
    // Show notification
    function showNotification(message, type = 'info') {
        const notificationContainer = document.getElementById('notification-container');
        
        if (!notificationContainer) {
            // Create container if it doesn't exist
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.style.position = 'fixed';
            container.style.top = '20px';
            container.style.right = '20px';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }
        
        const notification = document.createElement('div');
        notification.className = `toast bg-${type} text-white`;
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'assertive');
        notification.setAttribute('aria-atomic', 'true');
        
        notification.innerHTML = `
            <div class="toast-header bg-${type} text-white">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        document.getElementById('notification-container').appendChild(notification);
        
        const toast = new bootstrap.Toast(notification, {
            autohide: true,
            delay: 3000
        });
        
        toast.show();
        
        // Remove notification after it's hidden
        notification.addEventListener('hidden.bs.toast', function() {
            notification.remove();
        });
    }
});

// Dynamic country/state selector
function populateStates(countrySelect, stateSelect) {
    const countryStates = {
        'US': {
            'AL': 'Alabama',
            'AK': 'Alaska',
            'AZ': 'Arizona',
            'AR': 'Arkansas',
            'CA': 'California',
            'CO': 'Colorado',
            'CT': 'Connecticut',
            'DE': 'Delaware',
            'FL': 'Florida',
            'GA': 'Georgia',
            'HI': 'Hawaii',
            'ID': 'Idaho',
            'IL': 'Illinois',
            'IN': 'Indiana',
            'IA': 'Iowa',
            'KS': 'Kansas',
            'KY': 'Kentucky',
            'LA': 'Louisiana',
            'ME': 'Maine',
            'MD': 'Maryland',
            'MA': 'Massachusetts',
            'MI': 'Michigan',
            'MN': 'Minnesota',
            'MS': 'Mississippi',
            'MO': 'Missouri',
            'MT': 'Montana',
            'NE': 'Nebraska',
            'NV': 'Nevada',
            'NH': 'New Hampshire',
            'NJ': 'New Jersey',
            'NM': 'New Mexico',
            'NY': 'New York',
            'NC': 'North Carolina',
            'ND': 'North Dakota',
            'OH': 'Ohio',
            'OK': 'Oklahoma',
            'OR': 'Oregon',
            'PA': 'Pennsylvania',
            'RI': 'Rhode Island',
            'SC': 'South Carolina',
            'SD': 'South Dakota',
            'TN': 'Tennessee',
            'TX': 'Texas',
            'UT': 'Utah',
            'VT': 'Vermont',
            'VA': 'Virginia',
            'WA': 'Washington',
            'WV': 'West Virginia',
            'WI': 'Wisconsin',
            'WY': 'Wyoming'
        },
        'CA': {
            'AB': 'Alberta',
            'BC': 'British Columbia',
            'MB': 'Manitoba',
            'NB': 'New Brunswick',
            'NL': 'Newfoundland and Labrador',
            'NS': 'Nova Scotia',
            'NT': 'Northwest Territories',
            'NU': 'Nunavut',
            'ON': 'Ontario',
            'PE': 'Prince Edward Island',
            'QC': 'Quebec',
            'SK': 'Saskatchewan',
            'YT': 'Yukon'
        },
        'UK': {
            'England': 'England',
            'Northern Ireland': 'Northern Ireland',
            'Scotland': 'Scotland',
            'Wales': 'Wales'
        }
    };
    
    const country = countrySelect.value;
    const stateDropdown = document.getElementById(stateSelect);
    
    // Clear existing options
    stateDropdown.innerHTML = '<option value="">Select State/Province</option>';
    
    if (country && countryStates[country]) {
        const states = countryStates[country];
        
        for (const [code, name] of Object.entries(states)) {
            const option = document.createElement('option');
            option.value = code;
            option.textContent = name;
            stateDropdown.appendChild(option);
        }
        
        stateDropdown.disabled = false;
    } else {
        stateDropdown.disabled = true;
    }
} 