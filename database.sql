CREATE DATABASE IF NOT EXISTS azka_tenda;
USE azka_tenda;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

INSERT INTO categories (name) VALUES ('Semua Produk'), ('Wedding'), ('Khitanan'), ('Event Corporate');

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    image VARCHAR(500),
    description TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO products (category_id, name, price, unit, image, description) VALUES
(2, 'Tenda Dekorasi VIP', 45000, '/m²', 'assets/images/product1.jpg', 'Tenda dekorasi VIP dengan kualitas terbaik.'),
(2, 'Kursi Futura + Cover', 12000, '/unit', 'assets/images/product2.jpg', 'Kursi futura lengkap dengan cover elegan.'),
(1, 'Panggung Rigging', 750000, '/set', 'assets/images/product3.jpg', 'Panggung rigging kokoh untuk berbagai acara.'),
(1, 'Misty Fan (Kipas Air)', 350000, '/hari', 'assets/images/product4.jpg', 'Kipas air untuk menyejukkan suasana acara.');

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) NOT NULL UNIQUE,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(50) NOT NULL,
    event_date DATE NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    location TEXT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    snap_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) NOT NULL,
    product_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
