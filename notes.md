
<!-- 
- [ ] Tidak boleh hapus category jika ada produk yang masih menggunakan produk tersebut
- [ ] Jika nama produk > 44 character, tidak usah ditambah ...
- [ ] Bikin validasi is admin(id_admin) untuk admin permission
 -->

## 26 Oct 2023
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