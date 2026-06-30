<?php
require_once "koneksi.php";
require_once "auth.php";
require_login();
$title = "Peminjaman";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_anggota = (int)($_POST['id_anggota'] ?? 0);
    $id_buku = (int)($_POST['id_buku'] ?? 0);
    $tgl_pinjam = $_POST['tanggal_pinjam'] ?? date('Y-m-d');
    $tgl_tempo = $_POST['tanggal_jatuh_tempo'] ?? date('Y-m-d');
    mysqli_begin_transaction($conn);
    try {
        $stok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku=$id_buku FOR UPDATE"));
        if (!$stok || (int)$stok['stok'] <= 0) { throw new Exception('Stok buku habis.'); }
        $stmt = mysqli_prepare($conn, "INSERT INTO peminjaman (id_anggota, id_buku, tanggal_pinjam, tanggal_jatuh_tempo, status) VALUES (?, ?, ?, ?, 'dipinjam')");
        mysqli_stmt_bind_param($stmt, "iiss", $id_anggota, $id_buku, $tgl_pinjam, $tgl_tempo);
        mysqli_stmt_execute($stmt);
        mysqli_query($conn, "UPDATE buku SET stok=stok-1 WHERE id_buku=$id_buku");
        mysqli_commit($conn);
    } catch (Exception $e) { mysqli_rollback($conn); }
    header("Location: peminjaman.php"); exit;
}
$anggota = mysqli_query($conn, "SELECT * FROM anggota ORDER BY nama ASC");
$buku = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0 ORDER BY judul ASC");
$data = mysqli_query($conn, "SELECT p.*, a.nama AS nama_anggota, b.judul FROM peminjaman p JOIN anggota a ON p.id_anggota=a.id_anggota JOIN buku b ON p.id_buku=b.id_buku ORDER BY p.id_pinjam DESC");
include "header.php";
?>
<section class="panel"><div class="panel-head"><div><h2>Transaksi Peminjaman</h2><p>Catat buku yang dipinjam anggota.</p></div><button class="btn primary" data-modal="modalTambah">+ Tambah Peminjaman</button></div>
<div class="table-wrap"><table><thead><tr><th>Kode</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tempo</th><th>Status</th></tr></thead><tbody><?php while($p=mysqli_fetch_assoc($data)): ?><tr><td>PJ<?= str_pad($p['id_pinjam'],3,'0',STR_PAD_LEFT) ?></td><td><?= e($p['nama_anggota']) ?></td><td><?= e($p['judul']) ?></td><td><?= e($p['tanggal_pinjam']) ?></td><td><?= e($p['tanggal_jatuh_tempo']) ?></td><td><span class="badge <?= $p['status']==='dikembalikan'?'success':'primary' ?>"><?= e($p['status']) ?></span></td></tr><?php endwhile; ?></tbody></table></div></section>
<div class="modal" id="modalTambah"><div class="modal-card"><button class="close" data-close>×</button><h2>Tambah Peminjaman</h2><form method="POST" class="form-grid"><label>Anggota<select name="id_anggota" required><?php while($a=mysqli_fetch_assoc($anggota)): ?><option value="<?= $a['id_anggota'] ?>"><?= e($a['nama']) ?> - <?= e($a['nomor_anggota']) ?></option><?php endwhile; ?></select></label><label>Buku<select name="id_buku" required><?php while($b=mysqli_fetch_assoc($buku)): ?><option value="<?= $b['id_buku'] ?>"><?= e($b['judul']) ?> (stok <?= e($b['stok']) ?>)</option><?php endwhile; ?></select></label><label>Tanggal Pinjam<input type="date" name="tanggal_pinjam" value="<?= date('Y-m-d') ?>" required></label><label>Jatuh Tempo<input type="date" name="tanggal_jatuh_tempo" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required></label><button class="btn primary">Simpan</button></form></div></div>
<?php include "footer.php"; ?>
