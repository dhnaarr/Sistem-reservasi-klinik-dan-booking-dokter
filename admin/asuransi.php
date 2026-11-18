<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hapus'])) {
        db_jalankan('DELETE FROM asuransi WHERE id_asuransi = ?', [(int)$_POST['hapus']]);
        set_pesan('sukses', 'Asuransi dihapus.');
    } else {
        $id   = (int)$_POST['id'];                         
        $nama = trim($_POST['nama_asuransi']);

        if ($nama === '') {
            set_pesan('error', 'Nama asuransi wajib diisi.');
        } else {
            if ($id) db_jalankan('UPDATE asuransi SET nama_asuransi = ? WHERE id_asuransi = ?', [$nama, $id]);
            else     db_jalankan('INSERT INTO asuransi (nama_asuransi) VALUES (?)', [$nama]);
            set_pesan('sukses', 'Data asuransi disimpan.');
        }
    }
    redirect('admin/asuransi.php');
}


$edit = isset($_GET['edit']) ? db_satu('SELECT * FROM asuransi WHERE id_asuransi = ?', [(int)$_GET['edit']]) : null;
$rows = db_semua('SELECT * FROM asuransi ORDER BY nama_asuransi');

$judul = 'Data Asuransi';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1>Asuransi</h1><p class="redup">Daftar penjamin yang bisa dipilih pasien saat booking.</p></div>
  </div>

  <form method="post" class="kartu form-baris">
    <input type="hidden" name="id" value="<?= $edit['id_asuransi'] ?? 0 ?>">
    <div><label>Nama perusahaan asuransi</label><input name="nama_asuransi" value="<?= e($edit['nama_asuransi'] ?? '') ?>" required maxlength="100"></div>
    <div class="tombol-form">
      <button class="tombol"><?= $edit ? 'Simpan perubahan' : '+ Tambah asuransi' ?></button>
      <?php if ($edit): ?><a class="tombol garis" href="<?= url('admin/asuransi.php') ?>">Batal</a><?php endif; ?>
    </div>
  </form>

  <div class="tabel-bungkus"><table>
    <tr><th>#</th><th>Nama asuransi</th><th></th></tr>
    <?php foreach ($rows as $i => $r): ?>
    <tr>
      <td><?= $i + 1 ?></td>
      <td><?= e($r['nama_asuransi']) ?></td>
      <td class="aksi">
        <a class="tombol kecil garis" href="<?= url('admin/asuransi.php?edit=' . $r['id_asuransi']) ?>">Ubah</a>
        <form method="post" onsubmit="return confirm('Hapus asuransi ini?')">
          <input type="hidden" name="hapus" value="<?= $r['id_asuransi'] ?>">
          <button class="tombol kecil bahaya">Hapus</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
