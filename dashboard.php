<?php
require_once "koneksi.php";
require_once "auth.php";
require_login();
$title = "Dashboard";

function count_row($conn, $sql) {
    $res = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($res)["total"] ?? 0;
}
$total_buku = count_row($conn, "SELECT COUNT(*) AS total FROM buku");
$total_anggota = count_row($conn, "SELECT COUNT(*) AS total FROM anggota");
$total_dipinjam = count_row($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='dipinjam'");
$total_terlambat = count_row($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='dipinjam' AND tanggal_jatuh_tempo < CURDATE()");
$aktivitas = mysqli_query($conn, "SELECT p.*, a.nama AS nama_anggota, b.judul FROM peminjaman p JOIN anggota a ON p.id_anggota=a.id_anggota JOIN buku b ON p.id_buku=b.id_buku ORDER BY p.id_pinjam DESC LIMIT 6");
include "header.php";
?>
<section class="hero-panel">
    <p class="kicker">Library Control Center</p>
    <h2>Selamat datang, <?= e($_SESSION['nama'] ?? 'Admin') ?>.</h2>
    <p>Pantau kondisi perpustakaan, kelola koleksi, dan proses transaksi peminjaman dari satu dashboard yang lebih bersih dan profesional.</p>
    <div class="hero-actions">
        <a class="btn primary" href="peminjaman.php">Buat Peminjaman</a>
        <a class="btn secondary" href="laporan.php">Lihat Laporan</a>
    </div>
</section>
<section class="stats-grid">
    <div class="stat-card purple"><span>Total Buku</span><strong><?= $total_buku ?></strong><p>Koleksi tercatat</p></div>
    <div class="stat-card blue"><span>Anggota</span><strong><?= $total_anggota ?></strong><p>Terdaftar aktif</p></div>
    <div class="stat-card orange"><span>Dipinjam</span><strong><?= $total_dipinjam ?></strong><p>Masih berjalan</p></div>
    <div class="stat-card red"><span>Terlambat</span><strong><?= $total_terlambat ?></strong><p>Perlu tindak lanjut</p></div>
</section>
<section class="panel two-col">
    <div>
        <div class="panel-head"><div><h2>Akses Cepat</h2><p>Pilih modul yang sering digunakan.</p></div></div>
        <div class="quick-grid">
            <a href="buku.php">Kelola Buku</a>
            <a href="anggota.php">Data Anggota</a>
            <a href="peminjaman.php">Transaksi Pinjam</a>
            <a href="pengembalian.php">Proses Kembali</a>
        </div>
    </div>
    <div>
        <div class="panel-head"><div><h2>Aktivitas Terbaru</h2><p>Riwayat transaksi terakhir.</p></div></div>
        <div class="activity-list">
            <?php if (mysqli_num_rows($aktivitas) > 0): ?>
                <?php while($r = mysqli_fetch_assoc($aktivitas)): ?>
                    <div class="activity"><div><span><?= e($r['nama_anggota']) ?></span><small><?= e($r['judul']) ?> • <?= e($r['tanggal_pinjam']) ?></small></div><span class="badge <?= $r['status']==='dikembalikan'?'success':'primary' ?>"><?= e($r['status']) ?></span></div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty">Belum ada aktivitas transaksi.</div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include "footer.php"; ?>
