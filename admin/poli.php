<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['hapus'])) {
            db_jalankan('DELETE FROM poli WHERE id_poli = ?', [(int)$_POST['hapus']]);
            set_pesan('sukses', 'Poli dihapus.');
        } else {
            $id   = (int)$_POST['id'];                      
            $nama = trim($_POST['nama_poli']);
            $kode = strtoupper(trim($_POST['kode_poli']));

            if ($nama === '' || !preg_match('/^[A-Z]$/', $kode)) {
                set_pesan('error', 'Nama wajib diisi dan kode berupa 1 huruf.');
            } else {
                if ($id) db_jalankan('UPDATE poli SET nama_poli = ?, kode_poli = ? WHERE id_poli = ?', [$nama, $kode, $id]);
                else     db_jalankan('INSERT INTO poli (nama_poli, kode_poli) VALUES (?, ?)', [$nama, $kode]);
                set_pesan('sukses', 'Data poli disimpan.');
            }
        }
    } catch (PDOException $e) {
        set_pesan('error', 'Gagal: kode poli sudah dipakai, atau poli masih dipakai oleh dokter.');
    }
    redirect('admin/poli.php');
}


$edit = isset($_GET['edit']) ? db_satu('SELECT * FROM poli WHERE id_poli = ?', [(int)$_GET['edit']]) : null;
$rows = db_semua('SELECT p.*, (SELECT COUNT(*) FROM dokter d WHERE d.id_poli = p.id_poli) AS jml_dokter FROM poli p ORDER BY nama_poli');

$judul = 'Data Poli';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1>Poli</h1><p class="redup">Kode poli menjadi awalan nomor antrian (contoh: U-03).</p></div>
  </div>

  
  <form method="post" class="kartu form-baris">
    <input type="hidden" name="id" value="<?= $edit['id_poli'] ?? 0 ?>">
    <div><label>Nama poli</label><input name="nama_poli" value="<?= e($edit['nama_poli'] ?? '') ?>" required maxlength="100"></div>
    <div style="max-width:140px"><label>Kode (1 huruf)</label><input name="kode_poli" value="<?= e($edit['kode_poli'] ?? '') ?>" required maxlength="1"></div>
    <div class="tombol-form">
      <button class="tombol"><?= $edit ? 'Simpan perubahan' : '+ Tambah poli' ?></button>
      <?php if ($edit): ?><a class="tombol garis" href="<?= url('admin/poli.php') ?>">Batal</a><?php endif; ?>
    </div>
  </form>


  <div class="tabel-bungkus"><table>
    <tr><th>Kode</th><th>Nama poli</th><th>Jumlah dokter</th><th></th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
      <td><b><?= e($r['kode_poli']) ?></b></td>
      <td><?= e($r['nama_poli']) ?></td>
      <td><?= $r['jml_dokter'] ?></td>
      <td class="aksi">
        <a class="tombol kecil garis" href="<?= url('admin/poli.php?edit=' . $r['id_poli']) ?>">Ubah</a>
        <form method="post" onsubmit="return confirm('Hapus poli ini?')">
          <input type="hidden" name="hapus" value="<?= $r['id_poli'] ?>">
          <button class="tombol kecil bahaya">Hapus</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
