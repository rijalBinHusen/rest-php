CREATE TABLE
    if not exists note_app (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
        isi TEXT,
        FULLTEXT(isi),
        owner_id VARCHAR(9)
    );

-- THE PREFIX FOR CUSTOM ID note_app

CREATE TABLE
    if not exists note_app_prefix(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER tg_note_app_insert 
BEFORE INSERT ON note_app 
FOR EACH ROW 
BEGIN 
	INSERT INTO note_app_prefix VALUES (NULL);
	SET NEW.id = CONCAT('N', RIGHT(YEAR(CURRENT_DATE), 2), LPAD(WEEK(CURRENT_DATE), 2, '0'), LPAD(LAST_INSERT_ID(), 4, '0'));
	END$$ 
DELIMITER ;

-- RESET PREFIX TO 0 EVERY WEEK

CREATE EVENT truncate_note_app_prefix_seq_event 
ON SCHEDULE EVERY 1 WEEK
	DO TRUNCATE TABLE note_app_prefix;

-- ==================BORDER===========================================

CREATE TABLE
    if not exists note_app_users (
        id VARCHAR(9) PRIMARY KEY,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        password varchar(255) NOT NULL
    );

-- THE PREFIX FOR CUSTOM ID note_app_users

CREATE TABLE
    if not exists note_app_users_prefix(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY);

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER tg_note_app_users_insert 
BEFORE INSERT ON note_app_users 
FOR EACH ROW 
BEGIN 
	INSERT INTO note_app_users_prefix VALUES (NULL);
	SET NEW.id = CONCAT('U', RIGHT(YEAR(CURRENT_DATE), 2), LPAD(WEEK(CURRENT_DATE), 2, '0'), LPAD(LAST_INSERT_ID(), 4, '0'));
	END$$ 
DELIMITER ;

-- RESET PREFIX TO 0 EVERY WEEK

CREATE EVENT truncate_note_app_users_prefix_seq_event 
ON SCHEDULE EVERY 1 WEEK
	DO TRUNCATE TABLE note_app_users_prefix;

-- ==================BORDER