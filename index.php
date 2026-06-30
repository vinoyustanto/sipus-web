<?php
session_start();
require_once "koneksi.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id_user"];
        $_SESSION["nama"] = $user["nama"];
        $_SESSION["role"] = $user["role"];
        header("Location: dashboard.php");
        exit;
    }
    $error = "Username atau password salah.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIPUS</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <section class="login-card">
        <div class="login-hero">
            <div>
                <div class="brand-mark large">S</div>
                <h1>SIPUS</h1>
                <p>Dashboard perpustakaan modern untuk mengelola koleksi, anggota, transaksi, dan laporan dengan lebih cepat.</p>
                <div class="feature-list">
                    <div>Manajemen buku dan stok real-time</div>
                    <div>Transaksi peminjaman dan pengembalian</div>
                    <div>Laporan siap cetak untuk admin</div>
                </div>
            </div>
        </div>
        <form method="POST" class="login-form">
            <p class="kicker">Admin Access</p>
            <h2>Masuk ke Panel</h2>
            <p class="sub">Gunakan akun admin untuk mengelola sistem informasi perpustakaan.</p>
            <?php if ($error): ?><div class="alert danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
            <button class="btn primary" type="submit">Masuk Dashboard</button>
            <p class="login-foot">Akun default: <b>admin</b> / <b>admin123</b></p>
        </form>
    </section>
</body>
</html>
