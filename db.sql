CREATE TABLE warehouse (
    id VARCHAR(30) PRIMARY KEY,
    warehouse_name VARCHAR(255),
    warehouse_group VARCHAR(255),
    warehouse_supervisors VARCHAR(255)
)

CREATE TABLE
    supervisor (
        id VARCHAR(30) PRIMARY KEY,
        supervisor_name VARCHAR(30) NOT NULL,
        supervisor_phone VARCHAR(30) NOT NULL,
        supervisor_warehouse VARCHAR(50),
        supervisor_shift TINYINT,
        is_disabled BOOLEAN
    ) -- Supervisor table
CREATE TABLE
    error_log (
        id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        operation VARCHAR(30) NOT NULL,
        name_table VARCHAR(30) NOT NULL,
        message_error VARCHAR(50),
        time_error TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) -- error log table
CREATE TABLE
    base_item (
        id VARCHAR(30) PRIMARY KEY,
        item_kode VARCHAR(30) NOT NULL,
        item_name VARCHAR(30) NOT NULL,
        last_used FLOAT
    ) -- base_item
CREATE TABLE
    head_spv (
        id VARCHAR(30) PRIMARY KEY,
        head_name VARCHAR(30) NOT NULL,
        head_phone VARCHAR(30) NOT NULL,
        head_shift TINYINT,
        is_disabled BOOLEAN
    ) -- Head supervisor table
CREATE TABLE problem (
    id VARCHAR(30) PRIMARY KEY,
    warehouse_id varchar(255),
    supervisor_id varchar(255),
    head_spv_id varchar(255),
    item_kode varchar(255),
    tanggal_mulai FLOAT,
    shift_mulai TINYINT,
    pic varchar (255),
    dl FLOAT,
    masalah text,
    sumber_masalah text,
    solusi text,
    solusi_panjang text,
    dl_panjang FLOAT,
    pic_panjang varchar(255),
    tanggal_selesai FLOAT,
    shift_selesai TINYINT
); -- problem
CREATE TABLE
    base_file (
        id VARCHAR(30) PRIMARY KEY,
        periode FLOAT,
        warehouse_id VARCHAR(30) NOT NULL,
        file_name VARCHAR(30) NOT NULL,
        stock_sheet VARCHAR(30),
        clock_sheet VARCHAR(30),
        is_imported BOOLEAN
    ) -- base_file