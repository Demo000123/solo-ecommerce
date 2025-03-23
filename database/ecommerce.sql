-- Database creation
CREATE DATABASE IF NOT EXISTS solo_ecommerce;
USE solo_ecommerce;

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Insert sample categories
INSERT INTO categories (id, name, description) VALUES
(1, 'Electronics', 'Cutting-edge technology and gadgets for modern living'),
(2, 'Home & Kitchen', 'Transform your living space with our essential products'),
(3, 'Clothing', 'Stay stylish with our latest fashion collections'),
(4, 'Beauty & Personal Care', 'Enhance your natural beauty with premium products'),
(5, 'Sports & Outdoors', 'Gear up for your active lifestyle and adventures');

-- Insert sample products
INSERT INTO products (name, description, price, image, stock, category_id) VALUES
-- Electronics
('Smartphone X11', 'The latest smartphone with advanced camera capabilities, 5G connectivity, and a powerful processor.', 799.99, '/public/images/smartphone.jpg', 25, 1),
('Wireless Headphones', 'Premium noise-cancelling wireless headphones with 30-hour battery life.', 199.99, '/public/images/headphones.jpg', 50, 1),
('Smart Watch', 'Track your fitness goals, receive notifications, and more with this premium smart watch.', 249.99, '/public/images/smartwatch.jpg', 30, 1),
('Laptop Pro', 'Powerful laptop for professionals and creators.', 1299.99, '/public/images/laptop.jpg', 15, 1),
('Wireless Speaker', 'Portable Bluetooth speaker with immersive sound quality.', 89.99, '/public/images/speaker.jpg', 40, 1),

-- Clothing
('Men\'s T-Shirt', 'Classic cotton t-shirt for everyday comfort.', 24.99, '/public/images/tshirt.jpg', 100, 3),
('Women\'s Jeans', 'High-quality denim jeans with the perfect blend of style and comfort.', 59.99, '/public/images/jeans.jpg', 75, 3),
('Running Shoes', 'Lightweight running shoes designed for maximum comfort and performance.', 129.99, '/public/images/shoes.jpg', 35, 3),
('Winter Jacket', 'Stay warm with this insulated winter jacket.', 149.99, '/public/images/jacket.jpg', 25, 3),
('Summer Dress', 'Elegant summer dress made from lightweight, breathable fabric.', 79.99, '/public/images/dress.jpg', 30, 3),

-- Home
('Coffee Maker', 'Programmable coffee maker with 12-cup capacity and built-in grinder.', 79.99, '/public/images/coffeemaker.jpg', 20, 2),
('Bedding Set', 'Luxury bedding set including duvet cover, fitted sheet, and pillowcases.', 129.99, '/public/images/bedding.jpg', 25, 2),
('Table Lamp', 'Modern table lamp with adjustable brightness for reading or ambient lighting.', 49.99, '/public/images/lamp.jpg', 40, 2),
('Kitchen Knife Set', 'Professional-grade kitchen knife set with ergonomic handles.', 99.99, '/public/images/knives.jpg', 15, 2),
('Throw Pillows (Set of 2)', 'Decorative throw pillows to accent your living space.', 39.99, '/public/images/pillows.jpg', 50, 2);

-- Create indexes for faster searching
CREATE INDEX idx_product_category ON products(category_id);
CREATE INDEX idx_product_name ON products(name); 