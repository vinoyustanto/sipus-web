<?php
require_once "koneksi.php";
require_once "auth.php";
require_login();
$title = "Pengembalian";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_pinjam = (int)($_POST['id_pinjam'] ?? 0);
    $tanggal_kembali = $_POST['tanggal_kembali'] ?? date('Y-m-d');
    mysqli_begin_transaction($conn);
    try {
        $pinjam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_pinjam=$id_pinjam AND status='dipinjam' FOR UPDATE"));
        if ($pinjam) {
            $tempo = new DateTime($pinjam['tanggal_jatuh_tempo']);
            $kembali = new DateTime($tanggal_kembali);
            $terlambat = max(0, (int)$tempo->diff($kembali)->format('%r%a'));
            $denda = $terlambat * 1000;
            $stmt = mysqli_prepare($conn, "INSERT INTO pengembalian (id_pinjam, tanggal_kembali, terlambat, denda) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isii", $id_pinjam, $tanggal_kembali, $terlambat, $denda);
            mysqli_stmt_execute($stmt);
            mysqli_query($conn, "UPDATE peminjaman SET status='dikembalikan' WHERE id_pinjam=$id_pinjam");
            mysqli_query($conn, "UPDATE buku SET stok=stok+1 WHERE id_buku=".(int)$pinjam['id_buku']);
        }
        mysqli_commit($conn);
    } catch (Exception $e) { mysqli_rollback($conn); }
    header("Location: pengembalian.php"); exit;
}
$aktif = mysqli_query($conn, "SELECT p.*, a.nama AS nama_anggota, b.judul FROM peminjaman p JOIN anggota a ON p.id_anggota=a.id_anggota JOIN buku b ON p.id_buku=b.id_buku WHERE p.status='dipinjam' ORDER BY p.id_pinjam DESC");
$riwayat = mysqli_query($conn, "SELECT k.*, a.nama AS nama_anggota, b.judul FROM pengembalian k JOIN peminjaman p ON k.id_pinjam=p.id_pinjam JOIN anggota a ON p.id_anggota=a.id_anggota JOIN buku b ON p.id_buku=b.id_buku ORDER BY k.id_kembali DESC");
include "header.php";
?>
<section class="panel"><div class="panel-head"><div><h2>Proses Pengembalian</h2><p>Hitung keterlambatan dan denda otomatis.</p></div><button class="btn primary" data-modal="modalTambah">+ Proses Kembali</button></div>
<div class="table-wrap"><table><thead><tr><th>Kode</th><th>Anggota</th><th>Buku</th><th>Tanggal Kembali</th><th>Terlambat</th><th>Denda</th></tr></thead><tbody><?php while($k=mysqli_fetch_assoc($riwayat)): ?><tr><td>KB<?= str_pad($k['id_kembali'],3,'0',STR_PAD_LEFT) ?></td><td><?= e($k['nama_anggota']) ?></td><td><?= e($k['judul']) ?></td><td><?= e($k['tanggal_kembali']) ?></td><td><?= e($k['terlambat']) ?> hari</td><td>Rp <?= number_format($k['denda'],0,',','.') ?></td></tr><?php endwhile; ?></tbody></table></div></section>
<div class="modal" id="modalTambah"><div class="modal-card"><button class="close" data-close>×</button><h2>Form Pengembalian</h2><form method="POST" class="form-grid"><label>Pilih Peminjaman<select name="id_pinjam" required><?php while($p=mysqli_fetch_assoc($aktif)): ?><option value="<?= $p['id_pinjam'] ?>">PJ<?= str_pad($p['id_pinjam'],3,'0',STR_PAD_LEFT) ?> - <?= e($p['nama_anggota']) ?> - <?= e($p['judul']) ?></option><?php endwhile; ?></select></label><label>Tanggal Kembali<input type="date" name="tanggal_kembali" value="<?= date('Y-m-d') ?>" required></label><button class="btn primary">Proses</button></form></div></div>
<?php include "footer.php"; ?>
