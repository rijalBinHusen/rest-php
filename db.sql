CREATE TABLE if not exists my_report_warehouse (
  id VARCHAR(30) PRIMARY KEY,
  warehouse_name VARCHAR(255),
  warehouse_group VARCHAR(255)
);

CREATE TABLE if not exists my_report_supervisor (
  id VARCHAR(30) PRIMARY KEY,
  supervisor_name VARCHAR(30) NOT NULL,
  supervisor_phone VARCHAR(30) NOT NULL,
  supervisor_warehouse VARCHAR(50),
  supervisor_shift TINYINT,
  is_disabled BOOLEAN
);

CREATE TABLE if not exists my_report_error_log (
  id INT(255) PRIMARY KEY AUTO_INCREMENT,
  operation VARCHAR(30) NOT NULL,
  name_table VARCHAR(30) NOT NULL,
  message_error VARCHAR(50),
  time_error TIMESTAMP
);

CREATE TABLE if not exists my_report_base_item (
  id VARCHAR(30) PRIMARY KEY,
  item_kode VARCHAR(30) NOT NULL,
  item_name VARCHAR(30) NOT NULL,
  last_used FLOAT
);

CREATE TABLE if not exists my_report_head_spv (
  id VARCHAR(30) PRIMARY KEY,
  head_name VARCHAR(30) NOT NULL,
  head_phone VARCHAR(30) NOT NULL,
  head_shift TINYINT,
  is_disabled BOOLEAN
);

CREATE TABLE if not exists my_report_problem (
  id VARCHAR(30) PRIMARY KEY,
  warehouse_id varchar(255),
  supervisor_id varchar(255),
  head_spv_id varchar(255),
  item_kode varchar(255),
  tanggal_mulai FLOAT,
  shift_mulai TINYINT,
  pic varchar(255),
  dl FLOAT,
  masalah text,
  sumber_masalah text,
  solusi text,
  solusi_panjang text,
  dl_panjang FLOAT,
  pic_panjang varchar(255),
  tanggal_selesai FLOAT,
  shift_selesai TINYINT
);

CREATE TABLE if not exists my_report_base_report_file (
  id VARCHAR(30) PRIMARY KEY,
  periode FLOAT,
  warehouse_id VARCHAR(30) NOT NULL,
  file_name VARCHAR(30) NOT NULL,
  stock_sheet VARCHAR(30),
  clock_sheet VARCHAR(30),
  is_imported BOOLEAN
);

CREATE TABLE if not exists my_report_field_problem (
  id VARCHAR(30) PRIMARY KEY,
  periode FLOAT,
  supervisor_id VARCHAR(30),
  head_spv_id VARCHAR(30),
  masalah TEXT,
  sumber_masalah TEXT,
  solusi TEXT,
  pic VARCHAR(100),
  dl FLOAT
);

CREATE TABLE if not exists my_report_document (
  id VARCHAR(30) PRIMARY KEY,
  collected FLOAT,
  approval FLOAT,
  status INT,
  shared FLOAT,
  finished FLOAT,
  total_do FLOAT,
  total_kendaraan FLOAT,
  total_waktu FLOAT,
  base_report_file VARCHAR(30),
  is_finished BOOLEAN,
  supervisor_id VARCHAR(30),
  periode FLOAT,
  shift INT,
  head_spv_id VARCHAR(30),
  warehouse_id VARCHAR(30),
  is_generated_document BOOLEAN
);

CREATE TABLE if not exists my_report_complain (
  id VARCHAR(30) PRIMARY KEY,
  periode FLOAT,
  head_spv_id VARCHAR(30),
  dl FLOAT,
  inserted FLOAT,
  masalah TEXT,
  supervisor_id VARCHAR(30),
  parent VARCHAR(30),
  pic VARCHAR(255),
  solusi TEXT,
  is_status_done BOOLEAN,
  sumber_masalah TEXT,
  type varchar(255),
  is_count BOOLEAN
);

CREATE TABLE if not exists my_report_cases (
  id VARCHAR(30) PRIMARY KEY,
  periode FLOAT,
  head_spv_id VARCHAR(30),
  dl FLOAT,
  masalah TEXT,
  supervisor_id VARCHAR(30),
  parent VARCHAR(30),
  pic TEXT,
  solusi TEXT,
  status TEXT,
  sumber_masalah TEXT
);

CREATE TABLE if not exists my_report_case_import (
  id VARCHAR(30) PRIMARY KEY,
  bagian TEXT,
  divisi TEXT,
  fokus TEXT,
  kabag TEXT,
  karu TEXT,
  keterangan1 TEXT,
  keterangan2 TEXT,
  periode FLOAT,
  temuan TEXT
);

CREATE TABLE if not exists my_report_base_stock (
  id VARCHAR(30) PRIMARY KEY,
  parent VARCHAR(30),
  shift INT,
  item VARCHAR(30),
  awal FLOAT,
  in_stock FLOAT,
  plan_out FLOAT,
  out_stock FLOAT,
  date_in VARCHAR(30),
  date_out VARCHAR(30),
  date_end VARCHAR(30),
  real_stock FLOAT,
  problem VARCHAR(30)
);

CREATE TABLE if not exists my_report_base_clock (
  id VARCHAR(30),
  parent VARCHAR(30),
  shift INT,
  no_do FLOAT,
  reg VARCHAR(30),
  start VARCHAR(30),
  finish VARCHAR(30),
  rehat FLOAT
);

CREATE TABLE if not exists my_report_complain_import (
  id VARCHAR(30) PRIMARY KEY,
  customer VARCHAR(255),
  do_ FLOAT,
  gudang VARCHAR(255),
  item VARCHAR(255),
  kabag VARCHAR(255),
  nomor_SJ VARCHAR(255),
  nopol VARCHAR(255),
  real_ FLOAT,
  row_ VARCHAR(255),
  spv VARCHAR(255),
  tally VARCHAR(255),
  tanggal_bongkar VARCHAR(255),
  tanggal_info VARCHAR(255),
  tanggal_komplain VARCHAR(255),
  tanggal_SJ VARCHAR(255),
  type_ VARCHAR(255)
);

CREATE TABLE if not exists users (
  id bigint(20) PRIMARY KEY AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL
);
 
INSERT INTO users (id, name, email, password) VALUES
  (1, 'Jon Doe', 'jon@doe.com', '$2y$10$5S0BORM0dC/pVrddltxbg.Fa5EBa5zZDXxNhL5Jt57bCi1aFZpcee');

-- password $2y$10$5S0BORM0dC/pVrddltxbg.Fa5EBa5zZDXxNhL5Jt57bCi1aFZpcee === 12345
ALTER TABLE my_report_supervisor ADD FOREIGN KEY (supervisor_warehouse) REFERENCES my_report_warehouse (id);

ALTER TABLE my_report_base_report_file ADD FOREIGN KEY (warehouse_id) REFERENCES my_report_warehouse (id);

ALTER TABLE my_report_field_problem ADD FOREIGN KEY (supervisor_id) REFERENCES my_report_supervisor (id);

ALTER TABLE my_report_field_problem ADD FOREIGN KEY (head_spv_id) REFERENCES my_report_head_spv (id);

ALTER TABLE my_report_document  ADD FOREIGN KEY (base_report_file) REFERENCES my_report_base_report_file (id);

ALTER TABLE my_report_document ADD FOREIGN KEY (supervisor_id) REFERENCES my_report_supervisor (id);

ALTER TABLE my_report_document ADD FOREIGN KEY (head_spv_id) REFERENCES my_report_head_spv (id);

ALTER TABLE my_report_document ADD FOREIGN KEY (warehouse_id) REFERENCES my_report_warehouse (id);

ALTER TABLE my_report_problem ADD FOREIGN KEY (warehouse_id) REFERENCES my_report_warehouse (id);

ALTER TABLE my_report_problem ADD FOREIGN KEY (supervisor_id) REFERENCES my_report_supervisor (id);

ALTER TABLE my_report_problem ADD FOREIGN KEY (head_spv_id) REFERENCES my_report_head_spv (id);

ALTER TABLE my_report_complain ADD FOREIGN KEY (head_spv_id) REFERENCES my_report_head_spv (id);

ALTER TABLE my_report_complain ADD FOREIGN KEY (supervisor_id) REFERENCES my_report_supervisor (id);

ALTER TABLE my_report_cases ADD FOREIGN KEY (head_spv_id) REFERENCES my_report_head_spv (id);

ALTER TABLE my_report_cases ADD FOREIGN KEY (supervisor_id) REFERENCES my_report_supervisor (id);

ALTER TABLE my_report_complain ADD FOREIGN KEY (parent) REFERENCES my_report_complain_import (id);

ALTER TABLE my_report_cases ADD FOREIGN KEY (parent) REFERENCES my_report_case_import (id);

ALTER TABLE my_report_base_stock ADD FOREIGN KEY (parent) REFERENCES my_report_base_report_file (id);

ALTER TABLE my_report_base_clock ADD FOREIGN KEY (parent) REFERENCES my_report_base_report_file (id);
ALTER TABLE my_report_base_stock ADD FOREIGN KEY (problem) REFERENCES my_report_problem (id);

ALTER TABLE my_report_base_stock ADD FOREIGN KEY (item) REFERENCES my_report_base_item (item_kode);
