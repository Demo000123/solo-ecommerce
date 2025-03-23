<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        :root {
            --primary-color: #3a86ff;
            --text-color: #333;
            --light-gray: #f5f5f5;
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
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        
        .container {
            max-width: 800px;
            padding: 0 20px;
        }
        
        h1 {
            font-size: 8rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        
        p {
            margin-bottom: 30px;
            font-size: 1.2rem;
        }
        
        a {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        a:hover {
            background-color: #2a75e8;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you are looking for does not exist or has been moved.</p>
        <a href="/">Return to Home</a>
    </div>
</body>
</html> 