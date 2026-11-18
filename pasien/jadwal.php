<?php

require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$id_dokter = (int)($_GET['dokter'] ?? 0);
$dokter = db_satu('SELECT d.*, p.nama_poli FROM dokter d JOIN poli p ON p.id_poli = d.id_poli WHERE d.id_dokter = ?', [$id_dokter]);
if (!$dokter) redirect('pasien/dokter.php');


$jadwal = db_semua("SELECT j.*,
        j.kuota - (SELECT COUNT(*) FROM pendaftaran x WHERE x.id_jadwal = j.id_jadwal AND x.status <> 'batal') AS sisa
    FROM jadwal_praktik j
    WHERE j.id_dokter = ? AND j.tanggal >= CURDATE()
    ORDER BY j.tanggal, j.jam_mulai", [$id_dokter]);

$judul = 'Pilih Tanggal';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi sempit">
  <div class="judul-halaman">
    <div><h1>Pilih tanggal periksa</h1><p class="redup">Langkah 2 dari 3 — <a href="<?= url('pasien/dokter.php') ?>">‹ ganti dokter</a></p></div>
  </div>

  <div class="kartu ringkas">
    <div class="avatar"><?= e(inisial($dokter['nama_dokter'])) ?></div>
    <div>
      <h3><?= e($dokter['nama_dokter']) ?></h3>
      <p class="redup"><?= e($dokter['spesialis']) ?> · <?= e($dokter['nama_poli']) ?></p>
    </div>
  </div>

  <?php if (!$jadwal): ?><div class="kartu kosong">Belum ada jadwal untuk dokter ini.</div><?php endif; ?>

  <?php foreach ($jadwal as $j):
      $cuti  = $j['status'] === 'cuti';
      $penuh = !$cuti && $j['sisa'] <= 0;
      $bisa  = !$cuti && !$penuh;
  ?>
    <?php if ($bisa): ?><a class="tiket" href="<?= url('pasien/daftar.php?jadwal=' . $j['id_jadwal']) ?>">
    <?php else: ?><div class="tiket mati"><?php endif; ?>
      <div>
        <h3><?= e(tgl_indo($j['tanggal'])) ?></h3>
        <p class="redup">Sesi <?= e($j['sesi']) ?> · <?= jam($j['jam_mulai']) ?> - <?= jam($j['jam_selesai']) ?></p>
      </div>
      <?php if ($cuti): ?>
        <span class="pil kuning"><?= e($j['catatan'] ?: 'Cuti') ?></span>
      <?php elseif ($penuh): ?>
        <span class="pil merah">Kuota penuh</span>
      <?php else: ?>
        <span class="pil hijau">Sisa <?= $j['sisa'] ?></span>
      <?php endif; ?>
    <?php if ($bisa): ?></a><?php else: ?></div><?php endif; ?>
  <?php endforeach; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
