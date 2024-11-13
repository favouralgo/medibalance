-- Set foreign key checks to 0 to avoid dependency issues during table deletion
SET FOREIGN_KEY_CHECKS=0;

-- Drop tables if they exist
DROP TABLE IF EXISTS invoice_product;
DROP TABLE IF EXISTS invoice;
DROP TABLE IF EXISTS wallet;
DROP TABLE IF EXISTS facility;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS `user`;  -- Using backticks for reserved word
DROP TABLE IF EXISTS status;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;

-- Status table for payment status
CREATE TABLE status (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initial status values
INSERT INTO status (status_name) VALUES ('PAID'), ('UNPAID');

-- Product table for storing available products
CREATE TABLE product (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,
    product_description TEXT,
    product_price DECIMAL(10,2) NOT NULL,
    product_quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Customer table
CREATE TABLE customer (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_firstname VARCHAR(100) NOT NULL,
    customer_lastname VARCHAR(100) NOT NULL,
    customer_password VARCHAR(255) NOT NULL,
    customer_address TEXT NOT NULL,
    customer_phonenumber VARCHAR(20) NOT NULL,
    customer_city VARCHAR(100) NOT NULL,
    customer_country VARCHAR(100) NOT NULL,
    customer_email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User table (using backticks as user is a reserved word)
CREATE TABLE `user` (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    user_firstname VARCHAR(100) NOT NULL,
    user_lastname VARCHAR(100) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_phonenumber VARCHAR(20) NOT NULL,
    user_country VARCHAR(100) NOT NULL,
    user_city VARCHAR(100) NOT NULL,
    user_facilityname VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL UNIQUE,
    user_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Facility table
CREATE TABLE facility (
    facility_id INT PRIMARY KEY AUTO_INCREMENT,
    facility_name VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES `user`(user_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE(facility_name, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wallet table
CREATE TABLE wallet (
    wallet_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    wallet_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES `user`(user_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoice table
CREATE TABLE invoice (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    status_id INT NOT NULL,
    invoice_date_start DATE NOT NULL,
    invoice_date_due DATE NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    invoice_discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    invoice_vat DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    invoice_total DECIMAL(10,2) NOT NULL,
    user_id INT NOT NULL,
    customer_id INT NOT NULL,
    facility_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (status_id) REFERENCES status(status_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES `user`(user_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customer(customer_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (facility_id) REFERENCES facility(facility_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoice Product table
CREATE TABLE invoice_product (
    invoiceproduct_id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT NOT NULL,
    invoiceproduct_price DECIMAL(10,2) NOT NULL,
    invoiceproduct_quantity INT NOT NULL,
    invoiceproduct_description TEXT,
    invoiceproduct_name VARCHAR(255) NOT NULL,
    status_id INT NOT NULL,
    invoiceproduct_subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoice(invoice_id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (status_id) REFERENCES status(status_id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes for better performance
CREATE INDEX idx_invoice_number ON invoice(invoice_number);
CREATE INDEX idx_customer_email ON customer(customer_email);
CREATE INDEX idx_user_email ON `user`(user_email);
CREATE INDEX idx_facility_user ON facility(user_id);
CREATE INDEX idx_invoice_dates ON invoice(invoice_date_start, invoice_date_due);
CREATE INDEX idx_invoice_status ON invoice(status_id);
CREATE INDEX idx_invoice_product_invoice ON invoice_product(invoice_id);
CREATE INDEX idx_invoice_product_status ON invoice_product(status_id);
