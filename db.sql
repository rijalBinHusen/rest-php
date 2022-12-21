CREATE TABLE
    supervisor (
        id VARCHAR(30) PRIMARY KEY,
        supervisor_name VARCHAR(30) NOT NULL,
        supervisor_phone VARCHAR(30) NOT NULL,
        supervisor_warehouse VARCHAR(50),
        supervisor_shift INT(50),
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
        last_used INT(50)
    ) -- base_item
CREATE TABLE
    head_spv (
        id VARCHAR(30) PRIMARY KEY,
        head_name VARCHAR(30) NOT NULL,
        head_phone VARCHAR(30) NOT NULL,
        head_shift INT(50),
        is_disabled BOOLEAN
    ) -- Head supervisor table
CREATE TABLE
    problem (
        id VARCHAR(30) PRIMARY KEY,
        warehouse_id VARCHAR(30) NOT NULL,
        supervisor_id VARCHAR(30) NOT NULL,
        head_spv_id INT(50),
        item_kode VARCHAR NOT NULL,
        tanggal_mulai INT(30),
        shift_mulai INT(1),
        pic VARCHAR(100),
        dl INT(30),
        masalah TEXT,
        sumber_masalah TEXT,
        solusi TEXT,
        solusi_panjang TEXT,
        dl_panjang INT(30),
        pic_panjang VARCHAR(100),
        tanggal_selesai INT(30),
        shift_selesai INT(30)
    ) -- problem