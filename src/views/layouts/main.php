<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Solo E-commerce' ?></title>
    <link rel="stylesheet" href="/public/css/styles.css">
    <!-- Modern, clean UI using custom CSS -->
    <style>
        :root {
            --primary-color: #3a86ff;
            --secondary-color: #ff006e;
            --accent-color: #8338ec;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --gray: #e0e0e0;
            --white: #ffffff;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--light-gray);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: var(--white);
            box-shadow: var(--box-shadow);
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 20px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--text-color);
            font-weight: 500;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }
        
        nav ul li a:hover {
            background-color: var(--gray);
        }
        
        main {
            padding: 40px 0;
            min-height: calc(100vh - 180px);
        }
        
        footer {
            background-color: var(--white);
            padding: 20px 0;
            text-align: center;
            color: var(--text-color);
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn:hover {
            background-color: #2a75e8;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: #e0005c;
        }
        
        .card {
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: var(--transition);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .card-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: var(--text-color);
        }
        
        .card-price {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .product-img {
            width: 100%;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .product-info {
            display: flex;
            flex-direction: column;
        }
        
        .product-title {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        
        .product-price {
            font-size: 1.5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }
        
        .product-description {
            margin-bottom: 20px;
            line-height: 1.8;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .cart-table th,
        .cart-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--gray);
        }
        
        .cart-total {
            margin-top: 20px;
            text-align: right;
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            
            .product-detail {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            nav ul {
                margin-top: 20px;
            }
            
            nav ul li {
                margin: 0 10px 0 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <a href="/" class="logo">Solo E-commerce</a>
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/products">Products</a></li>
                    <li><a href="/cart">Cart</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="container">
        <?php if (isset($pageTitle)): ?>
            <h1><?= $pageTitle ?></h1>
        <?php endif; ?>
        
        <?= $content ?>
    </main>
    
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Solo E-commerce. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="/public/js/main.js"></script>
</body>
</html> 