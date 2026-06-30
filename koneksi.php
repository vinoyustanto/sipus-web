<?php
// Konfigurasi database untuk Laragon/XAMPP lokal.
// Pastikan database dengan nama di bawah sudah dibuat dan database.sql sudah di-import.
$host = "localhost";
$user = "root";
$pass = "";
$db   = "sipus_web.sql";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>
