SIPUS PHP + MySQL untuk InfinityFree

1. Import database.sql lewat phpMyAdmin InfinityFree.
2. Edit koneksi.php:
   $host = MySQL Hostname dari InfinityFree
   $user = Database Username
   $pass = Database Password
   $db   = Database Name
3. Upload semua isi folder ini ke htdocs, bukan ke root file manager.
4. Buka domain kamu.
5. Login default:
   Username: admin
   Password: admin123
6. Setelah login berhasil, ganti password admin dari database kalau diperlukan.

Catatan:
- Folder ini khusus InfinityFree/PHP, bukan untuk PythonAnywhere.
- File app.py Flask tidak dipakai di versi ini.
