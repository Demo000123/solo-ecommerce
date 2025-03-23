<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'KIM - Mua sắm thời trang trực tuyến' ?></title>
    
    <!-- Base CSS -->
    <link rel="stylesheet" href="/public/css/styles.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #A3A874;
            --primary-hover: #878a4e;
            --secondary-color: #4A5568;
            --accent-color: #4A5568;
            --heading-color: #2D3748;
            --body-color: #4A5568;
            --light-text: #718096;
            --white: #ffffff;
            --black: #000000;
            --light-bg: #F7FAFC;
            --dark-bg: #1A202C;
            --border-color: #E2E8F0;
            --border-radius: 4px;
            --border-radius-lg: 8px;
            --box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --box-shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            --transition-normal: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--body-color);
            line-height: 1.7;
            background-color: var(--white);
            margin: 0;
            padding: 0;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--heading-color);
        }

        /* Topbar - Information bar */
        .topbar {
            background-color: var(--primary-color);
            padding: 8px 0;
            font-size: 0.8rem;
            color: var(--white);
        }
        
        .topbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .topbar-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .topbar-info a {
            color: var(--white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .topbar-info a:hover {
            text-decoration: underline;
        }
        
        .topbar-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .language-selector, .currency-selector {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        /* Header Styling */
        .main-header {
            background-color: var(--white);
            box-shadow: var(--box-shadow-sm);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo a {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--heading-color);
            text-decoration: none;
            letter-spacing: 1px;
        }

        .logo a span {
            color: var(--primary-color);
        }

        .header-search {
            position: relative;
            width: 40%;
        }
        
        .search-form {
            display: flex;
            width: 100%;
        }
        
        .search-input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-right: none;
            border-radius: var(--border-radius) 0 0 var(--border-radius);
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .search-btn {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 0 20px;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
            cursor: pointer;
            transition: var(--transition-normal);
        }
        
        .search-btn:hover {
            background-color: var(--primary-hover);
        }

        .nav-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-icon {
            position: relative;
            color: var(--heading-color);
            text-decoration: none;
            transition: var(--transition-normal);
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-icon:hover {
            color: var(--primary-color);
        }
        
        .nav-icon .label {
            font-size: 0.7rem;
            display: block;
            margin-top: 4px;
            text-align: center;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .cart-count, .wishlist-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Navigation - separate from header for cleaner design */
        .main-nav {
            background-color: var(--white);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }
        
        .nav-container {
            display: flex;
            justify-content: center;
        }
        
        .nav-links {
            display: flex;
            gap: 3rem;
            padding: 15px 0;
        }

        .nav-links a {
            color: var(--heading-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition-normal);
            position: relative;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a.active {
            color: var(--primary-color);
        }
        
        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -16px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--primary-color);
        }

        .main-content {
            min-height: 70vh;
            padding: 3rem 0;
        }

        /* Mobile Menu */
        .menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            cursor: pointer;
        }

        .menu-toggle span {
            display: block;
            height: 2px;
            width: 100%;
            background-color: var(--heading-color);
            border-radius: 2px;
            transition: var(--transition-normal);
        }
        
        /* Responsive Menu */
        @media (max-width: 992px) {
            .header-search {
                width: 30%;
            }
            
            .nav-links {
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .topbar-info {
                display: none;
            }
            
            .header-content {
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .logo {
                order: 1;
                flex: 1;
            }
            
            .nav-controls {
                order: 2;
            }
            
            .header-search {
                order: 3;
                width: 100%;
                margin-top: 10px;
            }
            
            .menu-toggle {
                display: flex;
                order: 0;
            }

            .main-nav {
                display: none;
            }
            
            .nav-links {
                position: fixed;
                top: 0;
                left: -280px;
                width: 280px;
                height: 100vh;
                background: var(--white);
                flex-direction: column;
                padding: 80px 30px 30px;
                gap: 1.5rem;
                box-shadow: var(--box-shadow);
                z-index: 1010;
                transition: var(--transition-normal);
            }
            
            .nav-links.active {
                left: 0;
            }
            
            .mobile-close {
                position: absolute;
                top: 20px;
                right: 20px;
                font-size: 1.5rem;
                color: var(--heading-color);
                background: none;
                border: none;
                cursor: pointer;
            }
            
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1005;
                opacity: 0;
                visibility: hidden;
                transition: var(--transition-normal);
            }
            
            .mobile-overlay.active {
                opacity: 1;
                visibility: visible;
            }
        }
        
        @media (max-width: 480px) {
            .nav-controls {
                gap: 10px;
            }
            
            .nav-item .label {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Top information bar -->
    <div class="topbar">
        <div class="container topbar-container">
            <div class="topbar-info">
                <a href="mailto:hotro@KIM.vn"><i class="far fa-envelope"></i> hotro@KIM.vn</a>
                <a href="tel:+8497707745"><i class="far fa-phone"></i> +84 977 077 45</a>
                <span><i class="far fa-clock"></i> 08:00 - 21:00, Hàng ngày</span>
            </div>
            <div class="topbar-controls">
                <div class="language-selector">
                    <span>Tiếng Việt</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="currency-selector">
                    <span>VND</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main header with logo and search -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <button id="mobile-menu" class="menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <div class="logo">
                    <a href="/">KIM<span>.</span></a>
                </div>
                
                <div class="header-search">
                    <form action="/products" method="GET" class="search-form">
                        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." class="search-input">
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                
                <div class="nav-controls">
                    <div class="nav-item">
                        <a href="/solo-ecommerce/src/views/account/index.php" class="nav-icon">
                            <i class="far fa-user"></i>
                            <span class="label">Tài khoản</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="/wishlist" class="nav-icon">
                            <i class="far fa-heart"></i>
                            <span class="label">Yêu thích</span>
                            <span class="wishlist-count"><?= $wishlistCount ?? 0 ?></span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="/solo-ecommerce/src/views/cart/index.php" class="nav-icon">
                            <i class="far fa-shopping-bag"></i>
                            <span class="label">Giỏ hàng</span>
                            <span class="cart-count"><?= $cartCount ?? 0 ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main navigation -->
    <nav class="main-nav">
        <div class="container nav-container">
            <ul class="nav-links">
                <li><a href="/solo-ecommerce/src/views/home/index.php" class="<?= $currentPage === 'home' ? 'active' : '' ?>">Trang chủ</a></li>
                <li><a href="/solo-ecommerce/src/views/products/index.php" class="<?= $currentPage === 'products' ? 'active' : '' ?>">Sản phẩm</a></li>
                <li><a href="/solo-ecommerce/src/views/products/index.php?category=3" class="<?= $currentCategory === 3 ? 'active' : '' ?>">Thời trang nam</a></li>
                <li><a href="/solo-ecommerce/src/views/products/index.php?category=4" class="<?= $currentCategory === 4 ? 'active' : '' ?>">Thời trang nữ</a></li>
                <li><a href="/solo-ecommerce/src/views/products/index.php?category=1" class="<?= $currentCategory === 1 ? 'active' : '' ?>">Điện tử</a></li>
                <li><a href="/products?category=2" class="<?= $currentCategory === 2 ? 'active' : '' ?>">Đồ gia dụng</a></li>
                <li><a href="/blog" class="<?= $currentPage === 'blog' ? 'active' : '' ?>">Blog</a></li>
                <li><a href="/contact" class="<?= $currentPage === 'contact' ? 'active' : '' ?>">Liên hệ</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="mobile-overlay"></div>
    
    <div class="main-content">
        <div class="container"> 