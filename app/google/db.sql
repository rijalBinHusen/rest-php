CREATE TABLE if not exists google_accounts (
    id VARCHAR(9) NOT NULL PRIMARY KEY,
    email TEXT NOT NULL,
);

-- THE PREFIX FOR CUSTOM ID google_accounts

CREATE TABLE if not exists google_accounts_prefix (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
);

-- TRIGGER TO CREATE UNIQUEEE ID BASED ON PREFIX

DELIMITER $$

CREATE TRIGGER tg_google_account_insert 
BEFORE INSERT ON google_accounts 
FOR EACH ROW BEGIN 
    INSERT INTO google_accounts_prefix VALUES (NULL);
	SET
	    NEW.id = CONCAT(
	        'G',
	        RIGHT(YEAR(CURRENT_DATE), 2),
	        LPAD(WEEK(CURRENT_DATE), 2, '0'),
	        LPAD(LAST_INSERT_ID(), 4, '0')
	    );
	END $$

DELIMITER;