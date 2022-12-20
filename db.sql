-- Supervisor table

CREATE TABLE
    supervisor (
        id VARCHAR(30) PRIMARY KEY,
        supervisor_name VARCHAR(30) NOT NULL,
        supervisor_phone VARCHAR(30) NOT NULL,
        supervisor_warehouse VARCHAR(50),
        supervisor_shift INT(50),
        is_disabled BOOLEAN
    ) -- error log table
CREATE TABLE
    error_log (
        id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        operation VARCHAR(30) NOT NULL,
        name_table VARCHAR(30) NOT NULL,
        message_error VARCHAR(50),
        time_error TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) -- base_item
CREATE TABLE
    base_item (
        id VARCHAR(30) PRIMARY KEY,
        item_kode VARCHAR(30) NOT NULL,
        item_name VARCHAR(30) NOT NULL,
        last_used INT(50)
    ) -- Head supervisor table
CREATE TABLE
    head_spv (
        id VARCHAR(30) PRIMARY KEY,
        head_name VARCHAR(30) NOT NULL,
        head_phone VARCHAR(30) NOT NULL,
        head_shift INT(50),
        is_disabled BOOLEAN
    )