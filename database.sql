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
(2, 'Tenda Dekorasi VIP', 45000, '/m²', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCoAGKuoaiX4zsBV5Uhyp_bdog6qzoADrwhImgGWwNePh3OttkbD0OYU4mjQRaCluRzNfHn2qfcpyfCDetQbBRJEiFz6C_nvlzuc9ERrDi8GfYrkpTytfsE1WE333mnfZLR6yCxFrHSacwyfrhUddFHPM0TuIxRTDT5T8VQamYmkeSxTDB1X5RaAU_9xTlHXYQl6SbktpOT4nA9PB-FEw4MzFbJ9Bbb7iaLL03GspfOQr5SidXD04YvkrsYKgJV3GefcTZQMxABr6g2', 'Tenda dekorasi VIP dengan kualitas terbaik.'),
(2, 'Kursi Futura + Cover', 12000, '/unit', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAusf5ieFsD0Ft1gmqnNLycuBDCVH-g08ptG9aLn_cMxmOSWcT0iqNy4e6MNfBbZzcrDfoozOqGlY96Q48sc5Op7XC2eLx1nz-RHSILAbsVqmzMqD0UNoEW9znT6BnibQaJTVgPPZ0h-O-aL8y8YnnVvCLYVgBIdSAOOhKrk9w4ygri8WC7pjyaQ5Hn1IJ_jZE4b-80FU0-Lk8DWmsdCYOD2T4FURwy35jK8hwDIsMj7RQ2fm81zT22CpiF2nmFdTk269H6FXGJDod7', 'Kursi futura lengkap dengan cover elegan.'),
(1, 'Panggung Rigging', 750000, '/set', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBIkyuPtKGJOKDOgaIEWkcdUg6WO-9HGcIHN26phrh6tRnnJGUsgVyBVr8GhSr1BhkeOZXDYoIvdJMrV4EvcseQv2fHinAlngjwx2jGFmxbiVLIG_QKt5RB689IONNVgddcEH3vIqJBFyPoXuRZ8epJ6qVNtI_CcfcEJYdmuW2OGW-xfegAGneZLdTU-mdxDUiLYVMn_zZTL4hXkyeDVDu5XDQYZOpa65QK2xFfZbXgflv-zGcdNQq1Fy_Z0RJxJeDSkZJxCNsWffFJ', 'Panggung rigging kokoh untuk berbagai acara.'),
(1, 'Misty Fan (Kipas Air)', 350000, '/hari', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDBTKjpyiyd35d42WibyuTHXgm_D3h7BdZePh9bLuBFZFG6UP3qKFpifiX1qMuzhEBxeWaWST7C90J-f5lR6K2AD99dhsFjIO3EsEUWRkBahV53Q400YFnaxJI09JzyU-LB8IH4_EexmRq1ItNyvBvVOaj-1psFOabLwqCMy9sAuylw8sxoZ5qhvcdDKi259jJKh7IraZXmD0-55qAU4UT3hXe77YBry5NUXOzTsyNIKlYge2Q0Nq3sxqxCUe9LgW8b_mWWmq2MaDQV', 'Kipas air untuk menyejukkan suasana acara.');

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
