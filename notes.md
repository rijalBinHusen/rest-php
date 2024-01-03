
<!-- 
- [ ] Tidak boleh hapus category jika ada produk yang masih menggunakan produk tersebut
- [ ] Consider to return 1 image when request products
- [ ] Jangan boleh update paymet jika pembayaran > tagihan
- [ ] Try to update the password on unit testing, old password must be valid
- [ ] Jangan bolehkan update harga product jika produk sudah pernah dipesan
- [ ] Amankan end point/user/register
- [ ] Buat endpoint untuk mendapatkan nomor hp admin
- [ ] Create index on column my_report_base_item.last_used
- [ ] Create index on column my_report_base_report_file.periode
- [ ] Create index on column my_report_base_stock.parent
- [ ] Create index on column my_report_base_clock.parent
- [ ] Create index on column my_report_problem.tanggal_mulai
- [ ] Create index on column my_report_problem.supervisor_id
- [ ] Create index on column my_report_problem.warehouse_id
- [ ] Create index on column my_report_document.periode
- [ ] Check all critical endpoint and implement the access code request
- [ ] Periksa lagi event truncate table index di database
- [ ] endpoint untuk dapatkan order by group_id
- [ ] Endpoint to Retrieve payments that has group_id
- [ ] Create multiple order as group
  - [ ] Make sure that each order has same phone number (Throw error if not)
  - [ ] the group_id must be the smallest id
  - [ ] Inert the id group to payment list too
 -->

## 19 Des 2023
- [x] Retrieve payments group by order_id where id_group = ""

## 06 Des 2023
- [x] Add id_order_group on column payment

## 23 Nov 2023
- [x] Gunakan kode akses untuk akses resource binhusenstore
- [x] Bikin validasi is admin(id_admin) untuk admin permission

## 28 Oct 2023
- [x] End point to show total order
- [x] End point to show total money

## 27 Oct 2023
- [x] Fix error get testimony by product id
- [x] create API spec for access code endpoint
- [x] People who can create access code is admin
- [x] testimony POST|UPDATE validate payload rating as number, and the other as string
- [x] Products endpoint, validate payload price, weight, and default_total_week as number, validate payload is_available as boolean
- [x] validate other than above as string

## 26 Oct 2023
- [x] binhusenstore/products?id_category=p12323&limit=10
- [x] Jika nama produk > 44 character, tidak usah ditambah ...
- [x] (GET) produk/landing page order categories by id desc
- [x] (GET) binhusentore/testimonies, order by id desc with custom limit
- [x] Get random testimony for landing page (endpoint)
- [x] Add api spec for testimony landing page endpoint

## 23 Oct 23
- [x] hapus small image juga jika request remove image bukan small image
- [x] tambahkan display_name pada table database testimonies
- [x] terima request body display_name untuk post testimony
- [x] hanya terima request body rating dan content untuk put testimony
- [x] Kembalikan seluruh kolom kecuali id user saat get testimonies, get testimony

## 22 Oct 23

- [x] Reject request post binhusenstore/product jika image === ""