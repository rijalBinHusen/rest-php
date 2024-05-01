CREATE TABLE
    if not exists binhusenstore_products (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        name TEXT,
        categories VARCHAR(96),
        price INT,
        weight INT,
        images TEXT,
        description TEXT,
        default_total_week VARCHAR(2),
        is_available TINYINT,
        links TEXT,
        is_admin_charge TINYINT,
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        FULLTEXT(categories) FULLTEXT(name)
    );

-- THE PREFIX FOR CUSTOM ID binhusenstore_products

CREATE TABLE
    if not exists binhusenstore_products_prefix(
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
    );

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER TG_BINHUSENSTORE_PRODUCTS_INSERT 
BEFORE INSERT ON BINHUSENSTORE_PRODUCTS 
FOR EACH ROW BEGIN 
    INSERT INTO binhusenstore_products_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'P',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$


DELIMITER;

-- RESET PREFIX TO 0 EVERY WEEK

CREATE EVENT truncate_binhusenstore_products_prefix_seq_event ON SCHEDULE EVERY 1 WEEK
	DO TRUNCATE TABLE binhusenstore_products_prefix;


-- ============================== BORDER

CREATE TABLE
    if not exists binhusenstore_carts (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        id_user VARCHAR(30),
        product_id VARCHAR(30),
        qty TINYINT(2),
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

-- THE PREFIX FOR CUSTOM ID binhusenstore_carts

CREATE TABLE
    if not exists binhusenstore_carts_prefix(
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
    );

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER TG_BINHUSENSTORE_CARTS_INSERT 
BEFORE INSERT ON BINHUSENSTORE_CARTS 
FOR EACH ROW BEGIN
	INSERT INTO binhusenstore_carts_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'C',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$


DELIMITER;

-- RESET PREFIX TO 0 EVERY WEEK

CREATE EVENT truncate_binhusenstore_carts_prefix_seq_event ON SCHEDULE EVERY 1 WEEK
	DO
	TRUNCATE TABLE
	    binhusenstore_carts_prefix;


-- ============================== BORDER ======================

CREATE TABLE
    if not exists binhusenstore_categories (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        name_category VARCHAR(30),
        is_landing_page TINYINT(1),
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

-- THE PREFIX FOR CUSTOM ID binhusenstore_categories

CREATE TABLE
    if not exists binhusenstore_categories_prefix(
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
    );

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER TG_BINHUSENSTORE_CATEGORIES_INSERT 
BEFORE INSERT ON BINHUSENSTORE_CATEGORIES 
FOR EACH ROW 
    BEGIN 
	INSERT INTO binhusenstore_categories_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'C',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$


DELIMITER;

-- RESET PREFIX TO 0 EVERY WEEK

CREATE EVENT truncate_binhusenstore_categories_prefix_seq_event ON SCHEDULE EVERY 1 WEEK
	DO
	TRUNCATE TABLE
	    binhusenstore_categories_prefix;


-- ============================== BORDER ======================

CREATE TABLE
    if not exists binhusenstore_orders (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        date_order VARCHAR(10),
        id_group VARCHAR(9),
        is_group TINYINT(1),
        id_product VARCHAR(9),
        name_of_customer VARCHAR(47),
        sent VARCHAR(10),
        title VARCHAR(47),
        total_balance INT,
        phone TEXT,
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

-- THE PREFIX FOR CUSTOM ID binhusenstore_orders

CREATE TABLE
    if not exists binhusenstore_orders_prefix(
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
    );

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER TG_BINHUSENSTORE_ORDERS_INSERT 
BEFORE INSERT ON BINHUSENSTORE_ORDERS FOR EACH ROW 
BEGIN 
	INSERT INTO binhusenstore_orders_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'O',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$


DELIMITER;

-- RESET PREFIX TO 0 EVERY WEEK

CREATE EVENT truncate_binhusenstore_orders_prefix_seq_event ON SCHEDULE EVERY 1 WEEK
	DO
	TRUNCATE TABLE
	    binhusenstore_orders_prefix;


CREATE TABLE
    if not exists binhusenstore_orders_archived (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        date_order VARCHAR(10),
        id_group VARCHAR(9),
        is_group TINYINT(1),
        id_product VARCHAR(9),
        name_of_customer VARCHAR(47),
        sent VARCHAR(10),
        title VARCHAR(47),
        total_balance INT,
        phone TEXT,
        date_created DATETIME
    );

-- ============================== BORDER ======================

CREATE TABLE
    if not exists binhusenstore_payments (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        date_payment VARCHAR(10),
        id_order VARCHAR(9),
        id_order_group VARCHAR(9),
        balance INT,
        is_paid TINYINT(1),
        date_paid VARCHAR(10),
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

-- THE PREFIX FOR CUSTOM ID binhusenstore_payments

CREATE TABLE
    if not exists binhusenstore_payments_prefix(
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
    );

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER TG_BINHUSENSTORE_PAYMENTS_INSERT 
BEFORE INSERT ON BINHUSENSTORE_PAYMENTS FOR EACH ROW 
BEGIN 
	INSERT INTO binhusenstore_payments_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'O',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$


DELIMITER;

-- RESET PREFIX TO 0 EVERY WEEK

CREATE EVENT truncate_binhusenstore_payments_prefix_seq_event ON SCHEDULE EVERY 1 WEEK
	DO
	TRUNCATE TABLE
	    binhusenstore_payments_prefix;


CREATE TABLE
    if not exists binhusenstore_payments_archived (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        date_payment VARCHAR(10),
        id_order VARCHAR(9),
        id_order_group VARCHAR(9),
        balance INT,
        is_paid TINYINT(1),
        date_paid VARCHAR(10),
        date_created DATETIME
    );

-- ============================== BORDER ======================

CREATE TABLE
    if not exists binhusenstore_testimonies (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        id_user VARCHAR(30),
        display_name VARCHAR(30),
        id_product VARCHAR(30),
        rating TINYINT(1),
        content TEXT,
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

-- THE PREFIX FOR CUSTOM ID binhusenstore_testimonies

CREATE TABLE
    if not exists binhusenstore_testimonies_prefix(
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
    );

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER TG_BINHUSENSTORE_TESTIMONIES_INSERT 
BEFORE INSERT ON BINHUSENSTORE_TESTIMONIES FOR EACH ROW 
BEGIN
	INSERT INTO binhusenstore_testimonies_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'O',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$


DELIMITER;

-- RESET PREFIX TO 0 EVERY WEEK

CREATE EVENT truncate_binhusenstore_testimonies_prefix_seq_event ON SCHEDULE EVERY 1 WEEK
	DO
	TRUNCATE TABLE
	    binhusenstore_testimonies_prefix;


-- ========================================================BORDER

CREATE TABLE
    if not exists binhusenstore_users (
        id bigint(20) PRIMARY KEY AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

INSERT INTO
    binhusenstore_users (id, name, email, password)
VALUES (
        1,
        'Jon Doe',
        'binhusenstore_test@test.com',
        '$2y$10$5S0BORM0dC/pVrddltxbg.Fa5EBa5zZDXxNhL5Jt57bCi1aFZpcee'
    );

-- ========================================================BORDER

CREATE TABLE
    if not exists binhusenstore_products_archived (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        name TEXT,
        categories VARCHAR(96),
        price INT,
        weight INT,
        images TEXT,
        description TEXT,
        default_total_week VARCHAR(2),
        is_available TINYINT,
        links TEXT,
        FULLTEXT(categories),
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP FULLTEXT(name)
    );

-- ========================================================BORDER

CREATE TABLE
    if not exists binhusenstore_date_end (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        title TEXT,
        date VARCHAR(10)
    );

CREATE TABLE
    if not exists admin_charge (
        domain VARCHAR(20) PRIMARY KEY,
        admin_charge INT
    );

-- migration 27 Apr 2024
ALTER TABLE `binhusenstore_orders` ADD `admin_charge` INT(11) NOT NULL AFTER `total_balance`; 
ALTER TABLE `binhusenstore_orders` ADD `phone` TEXT NOT NULL AFTER `date_order`; 
ALTER TABLE `binhusenstore_payments` ADD `id_order_group` VARCHAR(9) NOT NULL AFTER `id_order`, ADD `date_paid` VARCHAR(10) NOT NULL AFTER `id_order_group`; 
ALTER TABLE `binhusenstore_products` ADD `is_admin_charge` TINYINT(1) NULL DEFAULT '0' AFTER `links`; 

CREATE TABLE
    if not exists binhusenstore_orders_archived (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        date_order VARCHAR(10),
        id_group VARCHAR(9),
        is_group TINYINT(1),
        id_product VARCHAR(9),
        name_of_customer VARCHAR(47),
        sent VARCHAR(10),
        title VARCHAR(47),
        total_balance INT,
        phone TEXT,
        date_created DATETIME
    );

CREATE TABLE
    if not exists binhusenstore_payments_archived (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        date_payment VARCHAR(10),
        id_order VARCHAR(9),
        id_order_group VARCHAR(9),
        balance INT,
        is_paid TINYINT(1),
        date_paid VARCHAR(10),
        date_created DATETIME
    );