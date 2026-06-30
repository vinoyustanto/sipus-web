document.querySelectorAll('[data-modal]').forEach(btn => {
  btn.addEventListener('click', () => document.getElementById(btn.dataset.modal)?.classList.add('show'));
});
document.querySelectorAll('[data-close]').forEach(btn => {
  btn.addEventListener('click', () => btn.closest('.modal')?.classList.remove('show'));
});
document.querySelectorAll('.modal').forEach(modal => {
  modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('show'); });
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') document.querySelectorAll('.modal.show').forEach(m => m.classList.remove('show'));
});
const sidebar = document.getElementById('sidebar');
document.querySelectorAll('[data-sidebar-toggle]').forEach(btn => {
  btn.addEventListener('click', () => sidebar?.classList.toggle('open'));
});
document.addEventListener('click', e => {
  if (window.innerWidth <= 820 && sidebar?.classList.contains('open')) {
    const isInside = sidebar.contains(e.target) || e.target.closest('[data-sidebar-toggle]');
    if (!isInside) sidebar.classList.remove('open');
  }
});
function fillBook(b) {
  document.getElementById('edit_id_buku').value = b.id_buku;
  document.getElementById('edit_kode_buku').value = b.kode_buku;
  document.getElementById('edit_judul').value = b.judul;
  document.getElementById('edit_penulis').value = b.penulis;
  document.getElementById('edit_penerbit').value = b.penerbit;
  document.getElementById('edit_tahun').value = b.tahun;
  document.getElementById('edit_kategori').value = b.kategori;
  document.getElementById('edit_stok').value = b.stok;
  document.getElementById('modalEdit').classList.add('show');
}
function fillMember(a) {
  document.getElementById('edit_id_anggota').value = a.id_anggota;
  document.getElementById('edit_nomor_anggota').value = a.nomor_anggota;
  document.getElementById('edit_nama').value = a.nama;
  document.getElementById('edit_kelas').value = a.kelas;
  document.getElementById('edit_alamat').value = a.alamat || '';
  document.getElementById('edit_no_hp').value = a.no_hp || '';
  document.getElementById('modalEdit').classList.add('show');
}
