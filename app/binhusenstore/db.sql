CREATE TABLE if not exists binhusenstore_products (
    id VARCHAR(30) NOT NULL PRIMARY KEY, 
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

CREATE TABLE if not exists binhusenstore_carts (
    id VARCHAR(30) NOT NULL PRIMARY KEY, 
    id_user VARCHAR(30),
    product_id VARCHAR(30),
    qty TINYINT(2)
);