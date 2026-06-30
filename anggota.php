<?php
require_once "koneksi.php";
require_once "auth.php";
require_login();
$title = "Data Anggota";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $nomor = trim($_POST["nomor_anggota"] ?? "");
    $nama = trim($_POST["nama"] ?? "");
    $kelas = trim($_POST["kelas"] ?? "");
    $alamat = trim($_POST["alamat"] ?? "");
    $nohp = trim($_POST["no_hp"] ?? "");
    if ($action === "add") {
        $stmt = mysqli_prepare($conn, "INSERT INTO anggota (nomor_anggota, nama, kelas, alamat, no_hp) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssss", $nomor, $nama, $kelas, $alamat, $nohp);
        mysqli_stmt_execute($stmt);
    }
    if ($action === "edit") {
        $id = (int)$_POST["id_anggota"];
        $stmt = mysqli_prepare($conn, "UPDATE anggota SET nomor_anggota=?, nama=?, kelas=?, alamat=?, no_hp=? WHERE id_anggota=?");
        mysqli_stmt_bind_param($stmt, "sssssi", $nomor, $nama, $kelas, $alamat, $nohp, $id);
        mysqli_stmt_execute($stmt);
    }
    if ($action === "delete") {
        $id = (int)$_POST["id_anggota"];
        $stmt = mysqli_prepare($conn, "DELETE FROM anggota WHERE id_anggota=?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: anggota.php"); exit;
}
$data = mysqli_query($conn, "SELECT * FROM anggota ORDER BY id_anggota DESC");
include "header.php";
?>
<section class="panel"><div class="panel-head"><div><h2>Anggota Perpustakaan</h2><p>Kelola data identitas anggota.</p></div><button class="btn primary" data-modal="modalTambah">+ Tambah Anggota</button></div>
<div class="table-wrap"><table><thead><tr><th>No Anggota</th><th>Nama</th><th>Kelas</th><th>Alamat</th><th>No HP</th><th>Aksi</th></tr></thead><tbody>
<?php while($a = mysqli_fetch_assoc($data)): ?>
<tr><td><?= e($a['nomor_anggota']) ?></td><td><b><?= e($a['nama']) ?></b></td><td><?= e($a['kelas']) ?></td><td><?= e($a['alamat']) ?></td><td><?= e($a['no_hp']) ?></td><td><button class="btn small warn" onclick='fillMember(<?= json_encode($a) ?>)'>Edit</button><form method="POST" class="inline" onsubmit="return confirm('Hapus anggota ini?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id_anggota" value="<?= $a['id_anggota'] ?>"><button class="btn small danger">Hapus</button></form></td></tr>
<?php endwhile; ?>
</tbody></table></div></section>
<div class="modal" id="modalTambah"><div class="modal-card"><button class="close" data-close>×</button><h2>Tambah Anggota</h2><form method="POST" class="form-grid"><input type="hidden" name="action" value="add"><label>No Anggota<input name="nomor_anggota" required></label><label>Nama<input name="nama" required></label><label>Kelas<input name="kelas" required></label><label>Alamat<textarea name="alamat"></textarea></label><label>No HP<input name="no_hp"></label><button class="btn primary">Simpan</button></form></div></div>
<div class="modal" id="modalEdit"><div class="modal-card"><button class="close" data-close>×</button><h2>Edit Anggota</h2><form method="POST" class="form-grid"><input type="hidden" name="action" value="edit"><input type="hidden" id="edit_id_anggota" name="id_anggota"><label>No Anggota<input id="edit_nomor_anggota" name="nomor_anggota" required></label><label>Nama<input id="edit_nama" name="nama" required></label><label>Kelas<input id="edit_kelas" name="kelas" required></label><label>Alamat<textarea id="edit_alamat" name="alamat"></textarea></label><label>No HP<input id="edit_no_hp" name="no_hp"></label><button class="btn primary">Update</button></form></div></div>
<?php include "footer.php"; ?>
