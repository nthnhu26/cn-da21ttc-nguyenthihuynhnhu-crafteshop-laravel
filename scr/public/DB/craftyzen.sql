CREATE TABLE `sessions` (
  `id` VARCHAR(191) NOT NULL,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `payload` TEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng banners (Banner quảng cáo)
CREATE TABLE banners (
    banner_id INT AUTO_INCREMENT PRIMARY KEY,  
    title VARCHAR(100),  
    image_url VARCHAR(100),  
    start_date DATE,  
    end_date DATE,  
    active BOOLEAN DEFAULT TRUE,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP  
);

-- Tạo bảng contacts (Bảng thông tin liên hệ)
CREATE TABLE contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    message TEXT NOT NULL,
    status ENUM('pending', 'processing', 'resolved') DEFAULT 'pending',
    response TEXT, 
    responded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng roles (Vai trò người dùng)
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY, 
    role_name VARCHAR(25) NOT NULL UNIQUE, -- Vai trò: admin, user
    description VARCHAR(255) 
);

-- Bảng users (Người dùng)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY, 
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, 
    name VARCHAR(50) NOT NULL,
    phone VARCHAR(15) UNIQUE,
    role_id INT DEFAULT 1, 
    is_email_verified BOOLEAN DEFAULT FALSE, -- Xác thực email
    email_verification_token VARCHAR(100), -- Token xác thực email
    email_verification_expires TIMESTAMP, -- Thời gian hết hạn xác thực email
    reset_password_token VARCHAR(100), -- Token quên mật khẩu
    reset_token_expires TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Thời gian hết hạn token quên mật khẩu
    account_status ENUM('active', 'locked', 'unverified') DEFAULT 'unverified', -- Trạng thái tài khoản
    avatar_url VARCHAR(100), -- URL ảnh đại diện
    remember_token VARCHAR(100), -- Token ghi nhớ đăng nhập
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE RESTRICT
);

-- Bảng user_addresses (Địa chỉ người dùng)
CREATE TABLE user_addresses (
    address_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    address_detail TEXT NOT NULL,
    id_province VARCHAR(255) NOT NULL,
    id_district VARCHAR(255) NOT NULL,
    id_ward VARCHAR(255) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE, -- Địa chỉ mặc định
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Bảng categories (Danh mục sản phẩm)
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY, 
    category_name VARCHAR(100) NOT NULL UNIQUE, 
    description TEXT, 
    display_order INT, -- Thứ tự hiển thị
    is_active BOOLEAN DEFAULT TRUE, -- Trạng thái hoạt động của danh mục
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP 
);

-- Bảng products (Sản phẩm)
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    description TEXT,
    short_description VARCHAR(255),
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0, -- Quản lý tồn kho
    category_id INT,
    weight DECIMAL(8, 2), -- Trọng lượng sản phẩm để tính phí vận chuyển
    dimensions VARCHAR(50), -- Kích thước sản phẩm
    material VARCHAR(100), -- Chất liệu sản phẩm
    origin VARCHAR(255), -- Nguồn gốc sản phẩm
    warranty_period INT, -- Thời gian bảo hành
    status ENUM('in_stock', 'out_of_stock') DEFAULT 'in_stock', -- Trạng thái sản phẩm
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE RESTRICT
);

-- Bảng inventory_changes (Thay đổi tồn kho)
CREATE TABLE inventory_changes (
    inventory_change_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity_change INT NOT NULL, -- Số lượng thay đổi (+ hoặc -)
    reason VARCHAR(255), -- Lý do thay đổi (đơn hàng, bổ sung kho,...)
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Bảng product_images (Hình ảnh sản phẩm)
CREATE TABLE product_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY, 
    product_id INT NOT NULL, 
    image_url VARCHAR(100) NOT NULL,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE 
);

-- Bảng carts (Giỏ hàng)
CREATE TABLE carts (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,  
    user_id INT,  
    session_id VARCHAR(50), -- Dùng để lưu giỏ hàng cho khách chưa đăng nhập
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  
    FOREIGN KEY (user_id) REFERENCES users(user_id)  
);

-- Bảng cart_items (Sản phẩm trong giỏ hàng)
CREATE TABLE cart_items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (cart_id) REFERENCES carts(cart_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);
-- Bảng promotions (Khuyến mãi)
CREATE TABLE promotions (
    promo_id INT AUTO_INCREMENT PRIMARY KEY,
    promo_name VARCHAR(255) NOT NULL,
    promo_type ENUM('percentage', 'fixed_amount') NOT NULL, -- Giảm giá theo % hoặc số tiền cố định
    code VARCHAR(20) UNIQUE, -- Mã giảm giá
    discount_value DECIMAL(10, 2) NOT NULL, -- Giá trị giảm giá
    max_discount DECIMAL(10, 2), -- Số tiền giảm tối đa
    min_purchase DECIMAL(10, 2), -- Số tiền mua tối thiểu để áp dụng khuyến mãi
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL,
    usage_limit INT, -- Giới hạn số lần sử dụng khuyến mãi
    used_count INT DEFAULT 0, -- Số lần khuyến mãi đã được sử dụng
    applies_to ENUM('all_products', 'specific_products') DEFAULT 'all_products', -- Áp dụng cho tất cả hoặc một số sản phẩm cụ thể
    applicable_to ENUM('all', 'new_customers') DEFAULT 'all', -- Áp dụng cho tất cả hoặc khách hàng mới
    active BOOLEAN DEFAULT TRUE, -- Khuyến mãi đang hoạt động
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CHECK (start_date < end_date),
    CHECK (used_count <= usage_limit)
);

-- Bảng promotion_products (Sản phẩm liên kết với khuyến mãi)
CREATE TABLE promotion_products (
    promo_id INT,
    product_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (promo_id, product_id),
    FOREIGN KEY (promo_id) REFERENCES promotions(promo_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

-- Bảng orders (Đơn hàng)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address_id INT, 
    promo_id INT,
    shipping_fee DECIMAL(10, 2) DEFAULT 0.00, -- Phí vận chuyển
    customer_note TEXT,
    total_amount DECIMAL(10, 2), -- Tổng số tiền đơn hàng
    discount DECIMAL(10, 2), -- Số tiền giảm giá
    final_amount DECIMAL(10, 2), -- Tổng số tiền thanh toán cuối cùng
    order_status ENUM('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE RESTRICT,
    FOREIGN KEY (promo_id) REFERENCES promotions(promo_id) ON DELETE SET NULL,
    FOREIGN KEY (address_id) REFERENCES user_addresses(address_id) ON DELETE SET NULL
);

-- Bảng order_items (Sản phẩm trong đơn hàng)
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL, -- Giá gốc của sản phẩm
    discount_amount DECIMAL(10, 2) DEFAULT 0.00, -- Số tiền giảm giá cho sản phẩm
    total_price DECIMAL(10, 2), -- Tổng giá của sản phẩm sau khi giảm giá
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE RESTRICT
);

-- Bảng reviews (Bình luận sản phẩm)
CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    title VARCHAR(255),
    rating INT CHECK (rating >= 1 AND rating <= 5), -- Đánh giá (1-5 sao)
    comment TEXT NOT NULL,
    media_urls VARCHAR(100), -- URL hình ảnh hoặc video liên quan đến đánh giá
    status ENUM('visible', 'hidden') DEFAULT 'visible', -- Trạng thái hiển thị
    is_reviewed BOOLEAN DEFAULT FALSE, -- Admin đã xem xét chưa
    reviewed_by INT, -- ID của admin đã xem xét
    reviewed_at TIMESTAMP, -- Thời gian xem xét
    hide_reason TEXT, -- Lý do ẩn (nếu bị ẩn)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id),
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id)
);


-- Bảng payments (Thanh toán)
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,    
    order_id INT NOT NULL,                        
    amount DECIMAL(10, 2) NOT NULL,   -- Số tiền thanh toán cuối cùng            
    payment_method ENUM('COD', 'Momo') NOT NULL,  -- Phương thức thanh toán
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',-- Trạng thái thanh toán
    transaction_id VARCHAR(255),                  -- Mã giao dịch (nếu có)
    payment_date TIMESTAMP,                       -- Ngày thanh toán (nếu đã hoàn thành)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, 
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE
);