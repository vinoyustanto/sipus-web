<?php
require_once "koneksi.php";
require_once "auth.php";
require_login();
$title = "Laporan";
$jenis = $_GET['jenis'] ?? 'peminjaman';
$awal = $_GET['tanggal_awal'] ?? '';
$akhir = $_GET['tanggal_akhir'] ?? '';

if ($jenis === 'buku') {
    $sql = "SELECT * FROM buku ORDER BY id_buku DESC";
} elseif ($jenis === 'anggota') {
    $sql = "SELECT * FROM anggota ORDER BY id_anggota DESC";
} elseif ($jenis === 'pengembalian') {
    $sql = "SELECT k.*, a.nama AS nama_anggota, b.judul FROM pengembalian k JOIN peminjaman p ON k.id_pinjam=p.id_pinjam JOIN anggota a ON p.id_anggota=a.id_anggota JOIN buku b ON p.id_buku=b.id_buku WHERE 1=1";
    if ($awal) $sql .= " AND k.tanggal_kembali >= '".mysqli_real_escape_string($conn,$awal)."'";
    if ($akhir) $sql .= " AND k.tanggal_kembali <= '".mysqli_real_escape_string($conn,$akhir)."'";
    $sql .= " ORDER BY k.id_kembali DESC";
} else {
    $jenis = 'peminjaman';
    $sql = "SELECT p.*, a.nama AS nama_anggota, b.judul FROM peminjaman p JOIN anggota a ON p.id_anggota=a.id_anggota JOIN buku b ON p.id_buku=b.id_buku WHERE 1=1";
    if ($awal) $sql .= " AND p.tanggal_pinjam >= '".mysqli_real_escape_string($conn,$awal)."'";
    if ($akhir) $sql .= " AND p.tanggal_pinjam <= '".mysqli_real_escape_string($conn,$akhir)."'";
    $sql .= " ORDER BY p.id_pinjam DESC";
}
$data = mysqli_query($conn, $sql);
include "header.php";
?>
<section class="panel no-print"><div class="panel-head"><div><h2>Filter Laporan</h2><p>Pilih jenis laporan dan rentang tanggal.</p></div><button class="btn primary" onclick="window.print()">Cetak</button></div>
<form class="report-filter" method="GET"><label>Jenis<select name="jenis"><option value="peminjaman" <?= $jenis==='peminjaman'?'selected':'' ?>>Peminjaman</option><option value="pengembalian" <?= $jenis==='pengembalian'?'selected':'' ?>>Pengembalian</option><option value="buku" <?= $jenis==='buku'?'selected':'' ?>>Data Buku</option><option value="anggota" <?= $jenis==='anggota'?'selected':'' ?>>Anggota</option></select></label><label>Tanggal Awal<input type="date" name="tanggal_awal" value="<?= e($awal) ?>"></label><label>Tanggal Akhir<input type="date" name="tanggal_akhir" value="<?= e($akhir) ?>"></label><button class="btn primary">Tampilkan</button></form></section>
<section class="panel"><div class="panel-head"><div><h2>Preview Laporan <?= ucfirst(e($jenis)) ?></h2><p>Dicetak dari SIPUS.</p></div></div><div class="table-wrap"><table>
<?php if ($jenis==='buku'): ?><thead><tr><th>No</th><th>Kode</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th></tr></thead><tbody><?php $no=1; while($r=mysqli_fetch_assoc($data)): ?><tr><td><?= $no++ ?></td><td><?= e($r['kode_buku']) ?></td><td><?= e($r['judul']) ?></td><td><?= e($r['penulis']) ?></td><td><?= e($r['kategori']) ?></td><td><?= e($r['stok']) ?></td></tr><?php endwhile; ?></tbody>
<?php elseif ($jenis==='anggota'): ?><thead><tr><th>No</th><th>No Anggota</th><th>Nama</th><th>Kelas</th><th>No HP</th></tr></thead><tbody><?php $no=1; while($r=mysqli_fetch_assoc($data)): ?><tr><td><?= $no++ ?></td><td><?= e($r['nomor_anggota']) ?></td><td><?= e($r['nama']) ?></td><td><?= e($r['kelas']) ?></td><td><?= e($r['no_hp']) ?></td></tr><?php endwhile; ?></tbody>
<?php elseif ($jenis==='pengembalian'): ?><thead><tr><th>No</th><th>Anggota</th><th>Buku</th><th>Tanggal Kembali</th><th>Terlambat</th><th>Denda</th></tr></thead><tbody><?php $no=1; while($r=mysqli_fetch_assoc($data)): ?><tr><td><?= $no++ ?></td><td><?= e($r['nama_anggota']) ?></td><td><?= e($r['judul']) ?></td><td><?= e($r['tanggal_kembali']) ?></td><td><?= e($r['terlambat']) ?> hari</td><td>Rp <?= number_format($r['denda'],0,',','.') ?></td></tr><?php endwhile; ?></tbody>
<?php else: ?><thead><tr><th>No</th><th>Kode</th><th>Anggota</th><th>Buku</th><th>Pinjam</th><th>Tempo</th><th>Status</th></tr></thead><tbody><?php $no=1; while($r=mysqli_fetch_assoc($data)): ?><tr><td><?= $no++ ?></td><td>PJ<?= str_pad($r['id_pinjam'],3,'0',STR_PAD_LEFT) ?></td><td><?= e($r['nama_anggota']) ?></td><td><?= e($r['judul']) ?></td><td><?= e($r['tanggal_pinjam']) ?></td><td><?= e($r['tanggal_jatuh_tempo']) ?></td><td><?= e($r['status']) ?></td></tr><?php endwhile; ?></tbody><?php endif; ?>
</table></div></section>
<?php include "footer.php"; ?>
