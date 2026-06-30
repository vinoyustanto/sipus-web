SIPUS InfinityFree - Professional UI Package

ISI PACKAGE:
- index.php
- dashboard.php
- buku.php
- anggota.php
- peminjaman.php
- pengembalian.php
- laporan.php
- logout.php
- header.php
- footer.php
- auth.php
- assets/css/style.css
- assets/js/app.js
- database.sql
- koneksi.example.php

PENTING:
Package ini sengaja TIDAK memakai file koneksi.php utama agar koneksi database kamu yang sudah benar tidak tertimpa.

CARA UPDATE UI DI INFINITYFREE:
1. Extract ZIP ini di laptop.
2. Masuk File Manager InfinityFree.
3. Masuk folder htdocs.
4. Upload semua isi folder sipus_infinityfree_pro_ui ke htdocs.
5. Jika diminta overwrite file lama, pilih overwrite untuk file PHP, CSS, dan JS.
6. Pastikan file koneksi.php lama kamu tetap ada di htdocs.
7. Jika belum punya koneksi.php, duplikat koneksi.example.php menjadi koneksi.php lalu isi detail database InfinityFree.

JANGAN LUPA:
- Kalau masih muncul HTTP 500, cek koneksi.php terlebih dahulu.
- Jangan ada spasi di username database, contoh benar: if0_42150684
- Database name biasanya memakai prefix, contoh: if0_42150684_sipus

LOGIN DEFAULT jika memakai database.sql bawaan:
Username: admin
Password: admin123
