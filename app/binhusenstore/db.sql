CREATE TABLE if not exists binhusenstore_products (
    id VARCHAR(9) NOT NULL PRIMARY KEY, 
    name TEXT, 
    categories VARCHAR(96),
    price INT,
    weight INT,
    images VARCHAR(255),
    description TEXT,
    default_total_week VARCHAR(2),
    is_available TINYINT,
    FULLTEXT(categories)
);

-- THE PREFIX FOR CUSTOM ID binhusenstore_products
CREATE TABLE if not exists binhusenstore_products_prefix(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- CUSTOM UNIQUEEE ID BASED ON PREFIX
DELIMITER $$
    CREATE TRIGGER tg_binhusenstore_products_insert
    BEFORE INSERT ON binhusenstore_products
    FOR EACH ROW
    BEGIN
        INSERT INTO binhusenstore_products_prefix VALUES (NULL, WEEK(CURRENT_DATE));
        SET NEW.id = CONCAT('P', RIGHT(YEAR(CURRENT_DATE), 2), LPAD(WEEK(CURRENT_DATE), 2, '0'), LPAD(LAST_INSERT_ID(), 4, '0'));
    END$$
DELIMITER ;

-- RESET PREFIX TO 0 EVERY WEEK
CREATE EVENT truncate_binhusenstore_products_prefix_seq_event
ON SCHEDULE EVERY 1 WEEK
DO
    TRUNCATE TABLE binhusenstore_products_prefix;


-- ============================== BORDER
CREATE TABLE if not exists binhusenstore_carts (
    id VARCHAR(9) NOT NULL PRIMARY KEY, 
    id_user VARCHAR(30),
    product_id VARCHAR(30),
    qty TINYINT(2)
);

-- THE PREFIX FOR CUSTOM ID binhusenstore_carts
CREATE TABLE if not exists binhusenstore_carts_prefix(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- CUSTOM UNIQUEEE ID BASED ON PREFIX
DELIMITER $$
    CREATE TRIGGER tg_binhusenstore_carts_insert
    BEFORE INSERT ON binhusenstore_carts
    FOR EACH ROW
    BEGIN
        INSERT INTO binhusenstore_carts_prefix VALUES (NULL, WEEK(CURRENT_DATE));
        SET NEW.id = CONCAT('C', RIGHT(YEAR(CURRENT_DATE), 2), LPAD(WEEK(CURRENT_DATE), 2, '0'), LPAD(LAST_INSERT_ID(), 4, '0'));
    END$$
DELIMITER ;

-- RESET PREFIX TO 0 EVERY WEEK
CREATE EVENT truncate_binhusenstore_carts_prefix_seq_event
ON SCHEDULE EVERY 1 WEEK
DO
    TRUNCATE TABLE binhusenstore_carts_prefix;

-- ============================== BORDER ======================
CREATE TABLE if not exists binhusenstore_categories (
    id VARCHAR(9) NOT NULL PRIMARY KEY, 
    name_category VARCHAR(30)
);

-- THE PREFIX FOR CUSTOM ID binhusenstore_categories
CREATE TABLE if not exists binhusenstore_categories_prefix(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- CUSTOM UNIQUEEE ID BASED ON PREFIX
DELIMITER $$
    CREATE TRIGGER tg_binhusenstore_categories_insert
    BEFORE INSERT ON binhusenstore_categories
    FOR EACH ROW
    BEGIN
        INSERT INTO binhusenstore_categories_prefix VALUES (NULL, WEEK(CURRENT_DATE));
        SET NEW.id = CONCAT('T', RIGHT(YEAR(CURRENT_DATE), 2), LPAD(WEEK(CURRENT_DATE), 2, '0'), LPAD(LAST_INSERT_ID(), 4, '0'));
    END$$
DELIMITER ;

-- RESET PREFIX TO 0 EVERY WEEK
CREATE EVENT truncate_binhusenstore_categories_prefix_seq_event
ON SCHEDULE EVERY 1 WEEK
DO
    TRUNCATE TABLE binhusenstore_categories_prefix;

-- ============================== BORDER ======================
CREATE TABLE if not exists binhusenstore_orders (
    id VARCHAR(9) NOT NULL PRIMARY KEY, 
    date_order VARCHAR(10),
    id_group VARCHAR(9),
    is_group TINYINT(1),
    id_product VARCHAR(9),
    name_of_customer VARCHAR(47),
    sent VARCHAR(10),
    title VARCHAR(47),
    total_balance INT
);

-- THE PREFIX FOR CUSTOM ID binhusenstore_orders
CREATE TABLE if not exists binhusenstore_orders_prefix(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- CUSTOM UNIQUEEE ID BASED ON PREFIX
DELIMITER $$
    CREATE TRIGGER tg_binhusenstore_orders_insert
    BEFORE INSERT ON binhusenstore_orders
    FOR EACH ROW
    BEGIN
        INSERT INTO binhusenstore_orders_prefix VALUES (NULL, WEEK(CURRENT_DATE));
        SET NEW.id = CONCAT('O', RIGHT(YEAR(CURRENT_DATE), 2), LPAD(WEEK(CURRENT_DATE), 2, '0'), LPAD(LAST_INSERT_ID(), 4, '0'));
    END$$
DELIMITER ;

-- RESET PREFIX TO 0 EVERY WEEK
CREATE EVENT truncate_binhusenstore_orders_prefix_seq_event
ON SCHEDULE EVERY 1 WEEK
DO
    TRUNCATE TABLE binhusenstore_orders_prefix;