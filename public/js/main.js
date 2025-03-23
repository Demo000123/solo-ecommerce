document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle if needed
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    if (menuToggle) {
        const navMenu = document.querySelector('nav ul');
        menuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });
    }
    
    // Quantity input validation
    const quantityInputs = document.querySelectorAll('input[type="number"][name="quantity"]');
    if (quantityInputs.length > 0) {
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const max = parseInt(this.getAttribute('max'));
                const value = parseInt(this.value);
                
                if (value > max) {
                    this.value = max;
                    alert('Sorry, we only have ' + max + ' items in stock.');
                }
                
                if (value < 1) {
                    this.value = 1;
                }
            });
        });
    }
    
    // Product image hover effect
    const productImages = document.querySelectorAll('.card-img');
    if (productImages.length > 0) {
        productImages.forEach(img => {
            img.addEventListener('mouseover', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = 'transform 0.3s ease';
            });
            
            img.addEventListener('mouseout', function() {
                this.style.transform = 'scale(1)';
            });
        });
    }
    
    // Search form validation
    const searchForm = document.querySelector('form[action="/products"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
}); 