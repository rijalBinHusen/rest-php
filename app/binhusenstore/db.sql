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
        SET NEW.id = CONCAT('P', RIGHT(YEAR(CURRENT_DATE), 2), LPAD(WEEK(CURRENT_DATE), 2, '0'), LPAD(LAST_INSERT_ID(), 4, '0'));
    END$$
DELIMITER ;

-- RESET PREFIX TO 0 EVERY WEEK
CREATE EVENT truncate_binhusenstore_carts_prefix_seq_event
ON SCHEDULE EVERY 1 WEEK
DO
    TRUNCATE TABLE binhusenstore_carts_prefix;