<?php require_once "auth.php"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) . ' | SIPUS' : 'SIPUS' ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-mark">S</div>
            <div class="brand-copy">
                <strong>SIPUS</strong>
                <span>Smart Library Panel</span>
            </div>
        </div>

        <div class="side-section">Menu Utama</div>
        <nav class="nav">
            <a class="<?= active('dashboard.php') ?>" href="dashboard.php"><span class="nav-ico">⌂</span><span>Dashboard</span></a>
            <a class="<?= active('buku.php') ?>" href="buku.php"><span class="nav-ico">◈</span><span>Data Buku</span></a>
            <a class="<?= active('anggota.php') ?>" href="anggota.php"><span class="nav-ico">◎</span><span>Anggota</span></a>
            <a class="<?= active('peminjaman.php') ?>" href="peminjaman.php"><span class="nav-ico">↗</span><span>Peminjaman</span></a>
            <a class="<?= active('pengembalian.php') ?>" href="pengembalian.php"><span class="nav-ico">↙</span><span>Pengembalian</span></a>
            <a class="<?= active('laporan.php') ?>" href="laporan.php"><span class="nav-ico">▣</span><span>Laporan</span></a>
        </nav>

        <div class="sidebar-card">
            <span>Mode Admin</span>
            <strong>Perpustakaan Aktif</strong>
            <p>Kelola transaksi buku dengan tampilan lebih rapi dan profesional.</p>
        </div>

        <a class="logout" href="logout.php"><span>⎋</span> Keluar</a>
    </aside>

    <main class="main">
        <header class="topbar">
            <button class="mobile-toggle" data-sidebar-toggle type="button">☰</button>
            <div class="page-title">
                <p class="kicker">Sistem Informasi Perpustakaan</p>
                <h1><?= isset($title) ? e($title) : 'SIPUS' ?></h1>
            </div>
            <div class="top-actions">
                <div class="date-pill"><?= date('d M Y') ?></div>
                <div class="user-chip">
                    <div class="avatar"><?= strtoupper(substr($_SESSION['nama'] ?? 'A', 0, 1)) ?></div>
                    <div>
                        <span>Masuk sebagai</span>
                        <strong><?= e($_SESSION['nama'] ?? 'Admin') ?></strong>
                    </div>
                </div>
            </div>
        </header>
