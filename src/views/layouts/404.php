<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #f97316;
            --text-color: #0f172a;
            --light-text: #64748b;
            --light-bg: #f8fafc;
            --dark-bg: #0f172a;
            --dark-text: #e2e8f0;
            --border-radius: 12px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            line-height: 1.7;
            color: var(--text-color);
            background-color: var(--light-bg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at center, rgba(99, 102, 241, 0.03) 0%, transparent 70%);
            z-index: -1;
            animation: pulse 15s infinite linear;
        }
        
        @keyframes pulse {
            0% {
                transform: translate(0, 0) scale(1);
            }
            50% {
                transform: translate(-5%, -5%) scale(1.05);
            }
            100% {
                transform: translate(0, 0) scale(1);
            }
        }
        
        .container {
            max-width: 800px;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }
        
        .error-code {
            font-size: 12rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 5px 30px rgba(99, 102, 241, 0.2);
            margin-bottom: 2rem;
            position: relative;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        
        .error-code::after {
            content: '404';
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: -1;
            -webkit-text-fill-color: rgba(99, 102, 241, 0.1);
            filter: blur(10px);
        }
        
        h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }
        
        p {
            margin-bottom: 2.5rem;
            font-size: 1.2rem;
            color: var(--light-text);
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .button-container {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .btn-outline {
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .icon {
            margin-right: 8px;
        }
        
        .illustrations {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: -1;
        }
        
        .illustration {
            position: absolute;
            background-color: rgba(99, 102, 241, 0.1);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        }
        
        .illustration-1 {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -100px;
            animation: morph1 15s linear infinite alternate;
        }
        
        .illustration-2 {
            width: 200px;
            height: 200px;
            bottom: -50px;
            left: -50px;
            animation: morph2 10s linear infinite alternate;
        }
        
        @keyframes morph1 {
            0% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }
            50% {
                border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
            }
            100% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }
        }
        
        @keyframes morph2 {
            0% {
                border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
            }
            50% {
                border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            }
            100% {
                border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: var(--dark-bg);
                color: var(--dark-text);
            }
            h2 {
                color: var(--dark-text);
            }
            .illustration {
                background-color: rgba(99, 102, 241, 0.15);
            }
        }
        
        .dark-mode-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: var(--text-color);
            cursor: pointer;
            transition: var(--transition-normal);
        }
        
        .dark-mode-toggle:hover {
            color: var(--primary-color);
            transform: rotate(15deg);
        }
        
        /* Dark mode classes */
        body.dark-mode {
            background-color: var(--dark-bg);
            color: var(--dark-text);
        }
        
        body.dark-mode h2 {
            color: var(--dark-text);
        }
        
        body.dark-mode .dark-mode-toggle {
            color: var(--dark-text);
        }
        
        body.dark-mode .btn-outline {
            color: var(--dark-text);
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>
    <button class="dark-mode-toggle" id="dark-mode-toggle" aria-label="Toggle dark mode">
        <i class="fas fa-moon"></i>
    </button>
    
    <div class="illustrations">
        <div class="illustration illustration-1"></div>
        <div class="illustration illustration-2"></div>
    </div>
    
    <div class="container">
        <h1 class="error-code">404</h1>
        <h2>Không tìm thấy trang</h2>
        <p>Trang bạn đang tìm kiếm không tồn tại hoặc đã được di chuyển. Vui lòng kiểm tra lại đường dẫn.</p>
        <div class="button-container">
            <a href="/" class="btn btn-primary"><i class="fas fa-home icon"></i>Trang chủ</a>
            <a href="/products" class="btn btn-outline"><i class="fas fa-shopping-bag icon"></i>Mua sắm</a>
        </div>
    </div>
    
    <script>
        // Dark mode toggle functionality
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const htmlElement = document.body;
        
        // Check for saved theme preference or prefer-color-scheme
        const savedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Apply dark mode if saved or preferred
        if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
            htmlElement.classList.add('dark-mode');
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
        
        // Toggle theme on click
        darkModeToggle.addEventListener('click', function() {
            htmlElement.classList.toggle('dark-mode');
            
            if (htmlElement.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                this.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                localStorage.setItem('theme', 'light');
                this.innerHTML = '<i class="fas fa-moon"></i>';
            }
        });
    </script>
</body>
</html> 