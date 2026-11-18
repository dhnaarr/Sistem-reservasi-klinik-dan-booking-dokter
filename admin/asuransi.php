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
