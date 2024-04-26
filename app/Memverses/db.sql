CREATE TABLE
    if not exists memverses_folders (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        id_user VARCHAR(9) NOT NULL,
        name TEXT NOT NULL,
        total_verse_to_show INT NOT NULL,
        show_next_chapter_on_second INT NOT NULL,
        read_target INT NOT NULL,
        is_show_first_letter TINYINT,
        is_show_tafseer TINYINT,
        arabic_size TINYINT,
        changed_by TEXT
    );

-- THE PREFIX FOR CUSTOM ID memverses_folders

CREATE TABLE
    if not exists memverses_folders_prefix(
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
    );

-- TRIGGER TO CREATE UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER tg_memverses_folder_insert 
BEFORE INSERT ON memverses_folders 
FOR EACH ROW BEGIN 
    INSERT INTO memverses_folders_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'F',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$


DELIMITER;

-- ============================== BORDER

CREATE TABLE
    if not exists memverses_chapters (
        id VARCHAR(9) NOT NULL PRIMARY KEY,
        id_chapter_client VARCHAR(9) NOT NULL,
        chapter INT,
        verse INT,
        readed_times INT,
        id_user VARCHAR(30),
        id_folder VARCHAR(30)
    );

-- THE PREFIX FOR CUSTOM ID memverses_chapters

CREATE TABLE
    if not exists memverses_chapters_prefix(
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
    );

-- CUSTOM UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER tg_memverses_chapter_insert 
BEFORE INSERT ON memverses_chapters 
FOR EACH ROW BEGIN
	INSERT INTO memverses_chapters_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'C',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$


DELIMITER;

-- ========================================================BORDER

CREATE TABLE
    if not exists memverses_users (
        id bigint(20) PRIMARY KEY AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        date_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

INSERT INTO
    memverses_users (id, name, email, password)
VALUES (
        1,
        'Jon Doe',
        'mem_test@test.com',
        '$2y$10$5S0BORM0dC/pVrddltxbg.Fa5EBa5zZDXxNhL5Jt57bCi1aFZpcee'
    );