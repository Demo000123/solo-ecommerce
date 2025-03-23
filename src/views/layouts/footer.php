        </div><!-- container -->
    </div><!-- main-content -->

    <!-- Back to top button -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Newsletter subscription -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-container">
                <div class="newsletter-content">
                    <h2>Đăng ký nhận thông tin</h2>
                    <p>Nhận thông báo về các bộ sưu tập mới và ưu đãi đặc biệt</p>
                </div>
                <form class="newsletter-form">
                    <div class="form-group">
                        <input type="email" placeholder="Địa chỉ email của bạn" required>
                        <button type="submit">Đăng ký</button>
                    </div>
                    <div class="form-policy">
                        Chúng tôi tôn trọng <a href="/privacy">chính sách bảo mật</a> của bạn
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Main footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-grid">
                    <div class="footer-col">
                        <div class="footer-logo">
                            <a href="/">KIM<span>.</span></a>
                        </div>
                        <p class="footer-desc">
                            Thương hiệu thời trang cao cấp với sản phẩm chất lượng vượt trội và dịch vụ khách hàng xuất sắc.
                        </p>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>

                    <div class="footer-col">
                        <h3>Thông tin</h3>
                        <ul class="footer-links">
                            <li><a href="/about">Về chúng tôi</a></li>
                            <li><a href="/contact">Liên hệ</a></li>
                            <li><a href="/terms">Điều khoản & Điều kiện</a></li>
                            <li><a href="/returns">Chính sách đổi trả</a></li>
                            <li><a href="/faq">Câu hỏi thường gặp</a></li>
                        </ul>
                    </div>

                    <div class="footer-col">
                        <h3>Tài khoản</h3>
                        <ul class="footer-links">
                            <li><a href="/solo-ecommerce/src/views/account/index.php">Tài khoản của tôi</a></li>
                            <li><a href="/orders">Lịch sử đặt hàng</a></li>
                            <li><a href="/wishlist">Danh sách yêu thích</a></li>
                            <li><a href="/newsletter">Thông báo</a></li>
                            <li><a href="/rewards">Điểm thưởng</a></li>
                        </ul>
                    </div>

                    <div class="footer-col">
                        <h3>Cửa hàng</h3>
                        <div class="contact-info">
                            <p><strong>Địa chỉ:</strong> 123 Phạm Văn Bạch, Tân Bình, TP.HCM</p>
                            <p><strong>Điện thoại:</strong> +84 977 077 45</p>
                            <p><strong>Email:</strong> hotro@KIM.vn</p>
                            <p><strong>Giờ làm việc:</strong> 08:00 - 21:00, Hàng ngày</p>
                        </div>
                    </div>
                </div>

                <div class="footer-middle">
                    <div class="payment-methods">
                        <h4>Chấp nhận thanh toán</h4>
                        <div class="payment-icons">
                            <img src="/public/images/payment/visa.png" alt="Visa">
                            <img src="/public/images/payment/mastercard.png" alt="MasterCard">
                            <img src="/public/images/payment/paypal.png" alt="PayPal">
                            <img src="/public/images/payment/momo.png" alt="MoMo">
                            <img src="/public/images/payment/cod.png" alt="COD">
                        </div>
                    </div>
                    <div class="app-download">
                        <h4>Tải ứng dụng</h4>
                        <div class="app-buttons">
                            <a href="#" class="app-btn">
                                <img src="/public/images/app/appstore.png" alt="App Store">
                            </a>
                            <a href="#" class="app-btn">
                                <img src="/public/images/app/googleplay.png" alt="Google Play">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="copyright">
                        &copy; <?= date('Y') ?> KIM. Thiết kế bởi <a href="#">KIM</a>. Tất cả quyền được bảo lưu.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu');
        const navLinks = document.querySelector('.nav-links');
        const mobileOverlay = document.querySelector('.mobile-overlay');
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                navLinks.classList.add('active');
                mobileOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }
        
        // Add close button to mobile menu
        if (navLinks) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'mobile-close';
            closeBtn.innerHTML = '<i class="fas fa-times"></i>';
            navLinks.appendChild(closeBtn);
            
            closeBtn.addEventListener('click', function() {
                navLinks.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
            
            mobileOverlay.addEventListener('click', function() {
                navLinks.classList.remove('active');
                mobileOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Back to top functionality
        const backToTopBtn = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Handle lazy loading for images
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('img[data-src]');
            
            if ('IntersectionObserver' in window) {
                let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            let lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src;
                            lazyImage.removeAttribute('data-src');
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });

                lazyImages.forEach(function(lazyImage) {
                    lazyImageObserver.observe(lazyImage);
                });
            } else {
                // Fallback for browsers that don't support IntersectionObserver
                let lazyImageTimeout;
                
                function lazyLoad() {
                    if(lazyImageTimeout) {
                        clearTimeout(lazyImageTimeout);
                    }

                    lazyImageTimeout = setTimeout(function() {
                        let scrollTop = window.pageYOffset;
                        lazyImages.forEach(function(lazyImage) {
                            if(lazyImage.offsetTop < (window.innerHeight + scrollTop)) {
                                lazyImage.src = lazyImage.dataset.src;
                                lazyImage.removeAttribute('data-src');
                            }
                        });
                        if(lazyImages.length == 0) { 
                            document.removeEventListener("scroll", lazyLoad);
                            window.removeEventListener("resize", lazyLoad);
                            window.removeEventListener("orientationChange", lazyLoad);
                        }
                    }, 20);
                }

                document.addEventListener("scroll", lazyLoad);
                window.addEventListener("resize", lazyLoad);
                window.addEventListener("orientationChange", lazyLoad);
            }
        });
    </script>

    <!-- Additional styling for footer and other new components -->
    <style>
        /* Newsletter section */
        .newsletter-section {
            background-color: var(--light-bg);
            padding: 4rem 0;
        }
        
        .newsletter-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }
        
        .newsletter-content h2 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: var(--heading-color);
        }
        
        .newsletter-content p {
            color: var(--body-color);
            max-width: 400px;
        }
        
        .newsletter-form {
            flex: 1;
            max-width: 500px;
        }
        
        .newsletter-form .form-group {
            display: flex;
            margin-bottom: 0.8rem;
        }
        
        .newsletter-form input {
            flex: 1;
            padding: 14px;
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            font-family: inherit;
            font-size: 0.9rem;
        }
        
        .newsletter-form input:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .newsletter-form button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0 2rem;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition-normal);
        }
        
        .newsletter-form button:hover {
            background-color: var(--primary-hover);
        }
        
        .form-policy {
            font-size: 0.8rem;
            color: var(--light-text);
        }
        
        .form-policy a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .form-policy a:hover {
            text-decoration: underline;
        }
        
        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition-normal);
            z-index: 99;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background-color: var(--primary-hover);
            transform: translateY(-5px);
        }

        /* Footer styling */
        .main-footer {
            background-color: var(--heading-color);
            color: #F7FAFC;
            padding: 4rem 0 2rem;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-logo a {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            margin-bottom: 1.5rem;
            display: inline-block;
            letter-spacing: 1px;
        }

        .footer-logo a span {
            color: var(--primary-color);
        }

        .footer-desc {
            color: #A0AEC0;
            margin-bottom: 1.8rem;
            line-height: 1.8;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            transition: var(--transition-normal);
        }

        .social-link:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .footer-col h3 {
            color: #fff;
            font-size: 1.15rem;
            margin-bottom: 1.5rem;
            position: relative;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #A0AEC0;
            text-decoration: none;
            transition: var(--transition-normal);
            font-size: 0.95rem;
        }

        .footer-links a:hover {
            color: #fff;
            padding-left: 5px;
        }

        .contact-info p {
            margin-bottom: 0.8rem;
            color: #A0AEC0;
            font-size: 0.95rem;
        }

        .contact-info strong {
            color: #fff;
            font-weight: 500;
        }
        
        .footer-middle {
            display: flex;
            justify-content: space-between;
            padding: 2rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 2rem;
        }
        
        .payment-methods, .app-download {
            flex: 1;
            min-width: 250px;
        }
        
        .payment-methods h4, .app-download h4 {
            color: #fff;
            font-size: 1rem;
            margin-bottom: 1rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }
        
        .payment-icons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .payment-icons img {
            height: 30px;
            background-color: #fff;
            border-radius: var(--border-radius);
            padding: 5px;
        }
        
        .app-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .app-btn img {
            height: 40px;
        }

        .footer-bottom {
            text-align: center;
        }
        
        .copyright {
            color: #A0AEC0;
            font-size: 0.9rem;
        }
        
        .copyright a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .copyright a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 992px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .newsletter-container {
                flex-direction: column;
                text-align: center;
            }
            
            .newsletter-content p {
                max-width: 100%;
            }
            
            .newsletter-form {
                max-width: 100%;
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .footer-col:first-child {
                text-align: center;
            }
            
            .social-links {
                justify-content: center;
            }
            
            .footer-middle {
                flex-direction: column;
                gap: 2rem;
            }
            
            .payment-icons, .app-buttons {
                justify-content: center;
            }
        }
    </style>
</body>
</html> 