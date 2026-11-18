<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$pasien = data_pasien();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $hasil = db_jalankan("UPDATE pendaftaran p JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
        SET p.status = 'batal'
        WHERE p.id_pendaftaran = ? AND p.id_pasien = ? AND p.status = 'aktif' AND j.tanggal >= CURDATE()",
        [(int)$_POST['id'], $pasien['id_pasien']]);

    if ($hasil->rowCount()) set_pesan('sukses', 'Booking dibatalkan.');
    else                    set_pesan('error', 'Booking tidak bisa dibatalkan.');
    redirect('pasien/riwayat.php');
}

$riwayat = db_semua("SELECT p.*, j.tanggal, j.jam_mulai, j.jam_selesai, d.nama_dokter, po.nama_poli, po.kode_poli
    FROM pendaftaran p
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN dokter d ON d.id_dokter = j.id_dokter
    JOIN poli po  ON po.id_poli = d.id_poli
    WHERE p.id_pasien = ?
    ORDER BY j.tanggal DESC, p.id_pendaftaran DESC", [$pasien['id_pasien']]);

$judul = 'Riwayat Booking';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1>Riwayat booking</h1><p class="redup">Status, nomor antrian, dan kode booking Anda.</p></div>
  </div>

  <?php if (!$riwayat): ?>
    <div class="kartu kosong">Belum ada riwayat booking. <a href="<?= url('pasien/dokter.php') ?>">Booking sekarang</a></div>
  <?php else: ?>
  <div class="tabel-bungkus"><table>
    <tr><th>Kode</th><th>Tanggal</th><th>Dokter</th><th>Antrian</th><th>Status</th><th></th></tr>
    <?php foreach ($riwayat as $r):
        [$teks, $warna] = label_status($r['status']);
        $bisa_batal = $r['status'] === 'aktif' && $r['tanggal'] >= date('Y-m-d'); ?>
    <tr>
      <td><b><?= kode_booking($r['id_pendaftaran']) ?></b></td>
      <td><?= e(tgl_indo($r['tanggal'])) ?><br><small class="redup"><?= jam($r['jam_mulai']) ?> - <?= jam($r['jam_selesai']) ?></small></td>
      <td><?= e($r['nama_dokter']) ?><br><small class="redup"><?= e($r['nama_poli']) ?></small></td>
      <td><b><?= e(kode_antrian($r['kode_poli'], $r['no_antrian'])) ?></b></td>
      <td><span class="pil <?= $warna ?>"><?= $teks ?></span></td>
      <td class="aksi">
        <?php if ($r['status'] !== 'batal'): ?>
          <a class="tombol kecil garis" href="<?= url('pasien/cetak.php?id=' . $r['id_pendaftaran']) ?>">Cetak</a>
        <?php endif; ?>
        <?php if ($bisa_batal): ?>
          <form method="post" onsubmit="return confirm('Batalkan booking ini?')">
            <input type="hidden" name="id" value="<?= $r['id_pendaftaran'] ?>">
            <button class="tombol kecil bahaya">Batalkan</button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
