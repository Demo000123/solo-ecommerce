-- Database creation
CREATE DATABASE IF NOT EXISTS ecommerce;
USE ecommerce;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category VARCHAR(50) NOT NULL
);

-- Insert sample products
INSERT INTO products (name, description, price, image, stock, category) VALUES
-- Electronics
('Smartphone X11', 'The latest smartphone with advanced camera capabilities, 5G connectivity, and a powerful processor. Features include a 6.5-inch OLED display, 128GB storage, and all-day battery life.', 799.99, '/public/images/smartphone.jpg', 25, 'electronics'),
('Wireless Headphones', 'Premium noise-cancelling wireless headphones with 30-hour battery life. Featuring high-resolution audio and comfortable over-ear design for the ultimate listening experience.', 199.99, '/public/images/headphones.jpg', 50, 'electronics'),
('Smart Watch', 'Track your fitness goals, receive notifications, and more with this premium smart watch. Includes heart rate monitoring, sleep tracking, and water resistance up to 50 meters.', 249.99, '/public/images/smartwatch.jpg', 30, 'electronics'),
('Laptop Pro', 'Powerful laptop for professionals and creators. Features a 15-inch display, 16GB RAM, 512GB SSD, and the latest generation processor for maximum performance.', 1299.99, '/public/images/laptop.jpg', 15, 'electronics'),
('Wireless Speaker', 'Portable Bluetooth speaker with immersive sound quality and 20-hour playback time. Waterproof design makes it perfect for outdoor activities and poolside entertainment.', 89.99, '/public/images/speaker.jpg', 40, 'electronics'),

-- Clothing
('Men\'s T-Shirt', 'Classic cotton t-shirt for everyday comfort. Available in multiple colors, this breathable shirt features a modern fit and durable construction that lasts through countless washes.', 24.99, '/public/images/tshirt.jpg', 100, 'clothing'),
('Women\'s Jeans', 'High-quality denim jeans with the perfect blend of style and comfort. These mid-rise jeans feature just the right amount of stretch for all-day wear.', 59.99, '/public/images/jeans.jpg', 75, 'clothing'),
('Running Shoes', 'Lightweight running shoes designed for maximum comfort and performance. Featuring responsive cushioning and breathable mesh to keep your feet cool during your workout.', 129.99, '/public/images/shoes.jpg', 35, 'clothing'),
('Winter Jacket', 'Stay warm with this insulated winter jacket. Waterproof exterior and thermal lining make this the perfect choice for cold weather adventures.', 149.99, '/public/images/jacket.jpg', 25, 'clothing'),
('Summer Dress', 'Elegant summer dress made from lightweight, breathable fabric. The flowing design and vibrant pattern make this perfect for warm weather occasions.', 79.99, '/public/images/dress.jpg', 30, 'clothing'),

-- Home
('Coffee Maker', 'Programmable coffee maker with 12-cup capacity and built-in grinder. Wake up to freshly ground and brewed coffee every morning with this easy-to-use appliance.', 79.99, '/public/images/coffeemaker.jpg', 20, 'home'),
('Bedding Set', 'Luxury bedding set including duvet cover, fitted sheet, and pillowcases. Made from 100% cotton with a 400 thread count for ultimate softness and comfort.', 129.99, '/public/images/bedding.jpg', 25, 'home'),
('Table Lamp', 'Modern table lamp with adjustable brightness for reading or ambient lighting. The sleek design complements any home decor style.', 49.99, '/public/images/lamp.jpg', 40, 'home'),
('Kitchen Knife Set', 'Professional-grade kitchen knife set with ergonomic handles. Includes chef\'s knife, bread knife, utility knife, paring knife, and kitchen shears in a wooden block.', 99.99, '/public/images/knives.jpg', 15, 'home'),
('Throw Pillows (Set of 2)', 'Decorative throw pillows to accent your living space. Soft, removable covers make these easy to clean and perfect for refreshing your home decor.', 39.99, '/public/images/pillows.jpg', 50, 'home');

-- Create indexes for faster searching
CREATE INDEX idx_product_category ON products(category);
CREATE INDEX idx_product_name ON products(name); 