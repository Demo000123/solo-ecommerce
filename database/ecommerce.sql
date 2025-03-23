-- Database creation
CREATE DATABASE IF NOT EXISTS solo_ecommerce;
USE solo_ecommerce;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    province VARCHAR(100),
    district VARCHAR(100),
    ward VARCHAR(100),
    role ENUM('admin', 'customer') DEFAULT 'customer',
    avatar VARCHAR(255) DEFAULT '/public/images/avatar-default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    slug VARCHAR(100) UNIQUE,
    parent_id INT NULL,
    image VARCHAR(255),
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    short_description VARCHAR(500),
    price DECIMAL(10, 2) NOT NULL,
    sale_price DECIMAL(10, 2) NULL,
    image VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    sku VARCHAR(50) UNIQUE,
    stock INT NOT NULL DEFAULT 0,
    status ENUM('active', 'inactive', 'draft') DEFAULT 'active',
    is_featured TINYINT(1) DEFAULT 0,
    category_id INT NOT NULL,
    brand VARCHAR(100),
    weight DECIMAL(10, 2),
    dimensions VARCHAR(50),
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Product images (additional images for product gallery)
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Product variants (for size, color, etc.)
CREATE TABLE IF NOT EXISTS product_attributes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attribute values
CREATE TABLE IF NOT EXISTS attribute_values (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attribute_id INT NOT NULL,
    value VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (attribute_id) REFERENCES product_attributes(id) ON DELETE CASCADE
);

-- Product variant combinations
CREATE TABLE IF NOT EXISTS product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    sku VARCHAR(50),
    price_adjustment DECIMAL(10, 2) DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Variant attribute values
CREATE TABLE IF NOT EXISTS variant_attribute_values (
    id INT AUTO_INCREMENT PRIMARY KEY,
    variant_id INT NOT NULL,
    attribute_value_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
    FOREIGN KEY (attribute_value_id) REFERENCES attribute_values(id) ON DELETE CASCADE
);

-- Carts table
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Cart items
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL
);

-- Wishlists
CREATE TABLE IF NOT EXISTS wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Wishlist items
CREATE TABLE IF NOT EXISTS wishlist_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    wishlist_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (wishlist_id) REFERENCES wishlists(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY (wishlist_id, product_id)
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE,
    user_id INT,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending',
    total_amount DECIMAL(10, 2) NOT NULL,
    tax_amount DECIMAL(10, 2) DEFAULT 0,
    shipping_amount DECIMAL(10, 2) DEFAULT 0,
    discount_amount DECIMAL(10, 2) DEFAULT 0,
    payment_method ENUM('cod', 'bank_transfer', 'momo', 'vnpay') DEFAULT 'cod',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    notes TEXT,
    shipping_fullname VARCHAR(100) NOT NULL,
    shipping_phone VARCHAR(20) NOT NULL,
    shipping_address TEXT NOT NULL,
    shipping_province VARCHAR(100) NOT NULL,
    shipping_district VARCHAR(100) NOT NULL,
    shipping_ward VARCHAR(100) NOT NULL,
    shipping_method ENUM('standard', 'express') DEFAULT 'standard',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Order items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT,
    name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (variant_id) REFERENCES product_variants(id) ON DELETE SET NULL
);

-- Reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    title VARCHAR(255),
    comment TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Coupons
CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    min_order_amount DECIMAL(10, 2) DEFAULT 0,
    max_discount_amount DECIMAL(10, 2),
    start_date DATE,
    end_date DATE,
    usage_limit INT,
    usage_count INT DEFAULT 0,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- User addresses
CREATE TABLE IF NOT EXISTS user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_name VARCHAR(100),
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    province VARCHAR(100) NOT NULL,
    district VARCHAR(100) NOT NULL,
    ward VARCHAR(100) NOT NULL,
    is_default TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Shipping methods
CREATE TABLE IF NOT EXISTS shipping_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    estimated_days VARCHAR(50),
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Settings for website configuration
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample categories
INSERT INTO categories (id, name, description, slug) VALUES
(1, 'Electronics', 'Cutting-edge technology and gadgets for modern living', 'electronics'),
(2, 'Home & Kitchen', 'Transform your living space with our essential products', 'home-kitchen'),
(3, 'Clothing', 'Stay stylish with our latest fashion collections', 'clothing'),
(4, 'Beauty & Personal Care', 'Enhance your natural beauty with premium products', 'beauty-personal-care'),
(5, 'Sports & Outdoors', 'Gear up for your active lifestyle and adventures', 'sports-outdoors');

-- Insert sample products
INSERT INTO products (name, description, short_description, price, image, slug, sku, stock, category_id, brand) VALUES
-- Electronics
('Smartphone X11', 'The latest smartphone with advanced camera capabilities, 5G connectivity, and a powerful processor.', 'Latest smartphone with advanced features', 799.99, '/public/images/smartphone.jpg', 'smartphone-x11', 'SM-X11-BLK', 25, 1, 'TechX'),
('Wireless Headphones', 'Premium noise-cancelling wireless headphones with 30-hour battery life.', 'Premium noise-cancelling headphones', 199.99, '/public/images/headphones.jpg', 'wireless-headphones', 'HP-WL-BLK', 50, 1, 'AudioPro'),
('Smart Watch', 'Track your fitness goals, receive notifications, and more with this premium smart watch.', 'Premium smartwatch with fitness tracking', 249.99, '/public/images/smartwatch.jpg', 'smart-watch', 'SW-PRO-SLV', 30, 1, 'TechX'),
('Laptop Pro', 'Powerful laptop for professionals and creators.', 'High-performance laptop for professionals', 1299.99, '/public/images/laptop.jpg', 'laptop-pro', 'LP-PRO-15', 15, 1, 'TechBook'),
('Wireless Speaker', 'Portable Bluetooth speaker with immersive sound quality.', 'Portable speaker with premium sound', 89.99, '/public/images/speaker.jpg', 'wireless-speaker', 'SPK-BT-BLK', 40, 1, 'AudioPro'),

-- Clothing
('Men\'s T-Shirt', 'Classic cotton t-shirt for everyday comfort.', 'Classic cotton t-shirt', 24.99, '/public/images/tshirt.jpg', 'mens-tshirt', 'TS-M-BLK-L', 100, 3, 'FashionPlus'),
('Women\'s Jeans', 'High-quality denim jeans with the perfect blend of style and comfort.', 'High-quality stylish denim jeans', 59.99, '/public/images/jeans.jpg', 'womens-jeans', 'WJ-BLU-M', 75, 3, 'FashionPlus'),
('Running Shoes', 'Lightweight running shoes designed for maximum comfort and performance.', 'Lightweight performance running shoes', 129.99, '/public/images/shoes.jpg', 'running-shoes', 'RS-BLK-42', 35, 3, 'AthleteX'),
('Winter Jacket', 'Stay warm with this insulated winter jacket.', 'Insulated jacket for cold weather', 149.99, '/public/images/jacket.jpg', 'winter-jacket', 'WJ-BLK-L', 25, 3, 'OutdoorLife'),
('Summer Dress', 'Elegant summer dress made from lightweight, breathable fabric.', 'Elegant lightweight summer dress', 79.99, '/public/images/dress.jpg', 'summer-dress', 'SD-WHT-M', 30, 3, 'FashionPlus'),

-- Home
('Coffee Maker', 'Programmable coffee maker with 12-cup capacity and built-in grinder.', '12-cup programmable coffee maker', 79.99, '/public/images/coffeemaker.jpg', 'coffee-maker', 'CM-PRO-12', 20, 2, 'HomeEssentials'),
('Bedding Set', 'Luxury bedding set including duvet cover, fitted sheet, and pillowcases.', 'Luxury complete bedding set', 129.99, '/public/images/bedding.jpg', 'bedding-set', 'BS-LUX-Q', 25, 2, 'ComfortHome'),
('Table Lamp', 'Modern table lamp with adjustable brightness for reading or ambient lighting.', 'Modern adjustable table lamp', 49.99, '/public/images/lamp.jpg', 'table-lamp', 'TL-MOD-WHT', 40, 2, 'LightDesign'),
('Kitchen Knife Set', 'Professional-grade kitchen knife set with ergonomic handles.', 'Professional kitchen knife set', 99.99, '/public/images/knives.jpg', 'kitchen-knife-set', 'KS-PRO-6PC', 15, 2, 'ChefSelect'),
('Throw Pillows (Set of 2)', 'Decorative throw pillows to accent your living space.', 'Decorative accent throw pillows', 39.99, '/public/images/pillows.jpg', 'throw-pillows', 'TP-DEC-BLU', 50, 2, 'ComfortHome');

-- Insert sample users
INSERT INTO users (fullname, email, password, phone, role) VALUES
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0987654321', 'admin'), -- password is 'password'
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0123456789', 'customer'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0123456788', 'customer');

-- Insert product attributes
INSERT INTO product_attributes (name) VALUES
('Color'),
('Size');

-- Insert attribute values
INSERT INTO attribute_values (attribute_id, value) VALUES
(1, 'Black'),
(1, 'White'),
(1, 'Red'),
(1, 'Blue'),
(1, 'Green'),
(2, 'S'),
(2, 'M'),
(2, 'L'),
(2, 'XL'),
(2, 'XXL');

-- Insert shipping methods
INSERT INTO shipping_methods (name, description, price, estimated_days) VALUES
('Standard Shipping', 'Regular delivery service with tracking', 30000, '3-5 days'),
('Express Shipping', 'Fast delivery service with priority handling', 60000, '1-2 days');

-- Insert settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'Solo Ecommerce'),
('site_description', 'Your one-stop shop for all your needs'),
('contact_email', 'support@soloecommerce.com'),
('contact_phone', '0987654321'),
('address', '123 Commerce Street, Hanoi, Vietnam'),
('currency', 'VND'),
('facebook_url', 'https://facebook.com/soloecommerce'),
('instagram_url', 'https://instagram.com/soloecommerce'),
('twitter_url', 'https://twitter.com/soloecommerce');

-- Create indexes for faster searching
CREATE INDEX idx_product_category ON products(category_id);
CREATE INDEX idx_product_name ON products(name);
CREATE INDEX idx_product_slug ON products(slug);
CREATE INDEX idx_product_price ON products(price);
CREATE INDEX idx_product_status ON products(status);
CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_order_status ON orders(status);
CREATE INDEX idx_review_product ON reviews(product_id);
CREATE INDEX idx_user_email ON users(email); 