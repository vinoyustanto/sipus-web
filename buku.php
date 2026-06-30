<?php
require_once "koneksi.php";
require_once "auth.php";
require_login();
$title = "Data Buku";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $kode = trim($_POST["kode_buku"] ?? "");
    $judul = trim($_POST["judul"] ?? "");
    $penulis = trim($_POST["penulis"] ?? "");
    $penerbit = trim($_POST["penerbit"] ?? "");
    $tahun = (int)($_POST["tahun"] ?? date('Y'));
    $kategori = trim($_POST["kategori"] ?? "");
    $stok = (int)($_POST["stok"] ?? 0);

    if ($action === "add") {
        $stmt = mysqli_prepare($conn, "INSERT INTO buku (kode_buku, judul, penulis, penerbit, tahun, kategori, stok) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssisi", $kode, $judul, $penulis, $penerbit, $tahun, $kategori, $stok);
        mysqli_stmt_execute($stmt);
    }
    if ($action === "edit") {
        $id = (int)$_POST["id_buku"];
        $stmt = mysqli_prepare($conn, "UPDATE buku SET kode_buku=?, judul=?, penulis=?, penerbit=?, tahun=?, kategori=?, stok=? WHERE id_buku=?");
        mysqli_stmt_bind_param($stmt, "ssssisii", $kode, $judul, $penulis, $penerbit, $tahun, $kategori, $stok, $id);
        mysqli_stmt_execute($stmt);
    }
    if ($action === "delete") {
        $id = (int)$_POST["id_buku"];
        $stmt = mysqli_prepare($conn, "DELETE FROM buku WHERE id_buku=?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: buku.php"); exit;
}
$data = mysqli_query($conn, "SELECT * FROM buku ORDER BY id_buku DESC");
include "header.php";
?>
<section class="panel">
    <div class="panel-head"><div><h2>Koleksi Buku</h2><p>Tambah, edit, dan hapus koleksi perpustakaan.</p></div><button class="btn primary" data-modal="modalTambah">+ Tambah Buku</button></div>
    <div class="table-wrap"><table><thead><tr><th>Kode</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    <?php while($b = mysqli_fetch_assoc($data)): ?>
        <tr><td><?= e($b['kode_buku']) ?></td><td><b><?= e($b['judul']) ?></b><br><small><?= e($b['penerbit']) ?> • <?= e($b['tahun']) ?></small></td><td><?= e($b['penulis']) ?></td><td><span class="badge muted"><?= e($b['kategori']) ?></span></td><td><?= e($b['stok']) ?></td><td><?= $b['stok'] > 0 ? '<span class="badge success">Tersedia</span>' : '<span class="badge danger">Habis</span>' ?></td><td><button class="btn small warn" onclick='fillBook(<?= json_encode($b) ?>)'>Edit</button><form method="POST" class="inline" onsubmit="return confirm('Hapus buku ini?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id_buku" value="<?= $b['id_buku'] ?>"><button class="btn small danger">Hapus</button></form></td></tr>
    <?php endwhile; ?>
    </tbody></table></div>
</section>
<div class="modal" id="modalTambah"><div class="modal-card"><button class="close" data-close>×</button><h2>Tambah Buku</h2><form method="POST" class="form-grid"><input type="hidden" name="action" value="add"><?php include "partials_buku_form.php"; ?><button class="btn primary">Simpan</button></form></div></div>
<div class="modal" id="modalEdit"><div class="modal-card"><button class="close" data-close>×</button><h2>Edit Buku</h2><form method="POST" class="form-grid"><input type="hidden" name="action" value="edit"><input type="hidden" name="id_buku" id="edit_id_buku"><?php include "partials_buku_form_edit.php"; ?><button class="btn primary">Update</button></form></div></div>
<?php include "footer.php"; ?>
