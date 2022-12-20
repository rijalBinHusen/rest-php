-- Supervisor table
CREATE TABLE supervisor (
id VARCHAR(30) PRIMARY KEY,
supervisor_name VARCHAR(30) NOT NULL,
supervisor_phone VARCHAR(30) NOT NULL,
supervisor_warehouse VARCHAR(50),
supervisor_shift INT(50),
is_disabled BOOLEAN
)

-- error log table
CREATE TABLE error_log (
id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
operation VARCHAR(30) NOT NULL,
name_table VARCHAR(30) NOT NULL,
message_error VARCHAR(50),
time_error TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)