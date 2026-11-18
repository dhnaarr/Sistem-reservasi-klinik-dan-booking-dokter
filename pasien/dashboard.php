<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$pasien = data_pasien();


$aktif = db_semua("SELECT p.*, j.tanggal, j.jam_mulai, j.jam_selesai, d.nama_dokter, po.nama_poli, po.kode_poli
    FROM pendaftaran p
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN dokter d  ON d.id_dokter = j.id_dokter
    JOIN poli po   ON po.id_poli = d.id_poli
    WHERE p.id_pasien = ? AND p.status = 'aktif' AND j.tanggal >= CURDATE()
    ORDER BY j.tanggal, j.jam_mulai LIMIT 6", [$pasien['id_pasien']]);

$judul = 'Beranda';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div>
      <p class="redup">Selamat datang,</p>
      <h1><?= e($_SESSION['nama']) ?></h1>
    </div>
    <span class="pil biru">📅 <?= e(tgl_indo(date('Y-m-d'))) ?></span>
  </div>

  <div class="menu-grid">
    <a class="menu-kartu" href="<?= url('pasien/dokter.php') ?>">
      <span class="ikon">🗓️</span><h3>Booking</h3><p>Pilih poli, dokter, dan tanggal periksa</p>
    </a>
    <a class="menu-kartu" href="<?= url('pasien/riwayat.php') ?>">
      <span class="ikon">🕘</span><h3>Riwayat</h3><p>Lihat booking dan cetak bukti</p>
    </a>
  </div>

  <h2 class="sub-judul">Booking aktif</h2>
  <?php if (!$aktif): ?>
    <div class="kartu kosong">
      Belum ada booking aktif.<br><br>
      <a class="tombol" href="<?= url('pasien/dokter.php') ?>">Booking sekarang</a>
    </div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($aktif as $b): ?>
      <article class="kartu">
        <div class="baris-antara">
          <span class="nomor"><?= e(kode_antrian($b['kode_poli'], $b['no_antrian'])) ?></span>
          <span class="pil biru">Aktif</span>
        </div>
        <h3><?= e($b['nama_dokter']) ?></h3>
        <p class="redup"><?= e($b['nama_poli']) ?></p>
        <p>📅 <?= e(tgl_indo($b['tanggal'])) ?><br>🕘 <?= jam($b['jam_mulai']) ?> - <?= jam($b['jam_selesai']) ?></p>
        <p class="redup">Kode booking: <b><?= kode_booking($b['id_pendaftaran']) ?></b></p>
        <a class="tombol kecil garis" href="<?= url('pasien/cetak.php?id=' . $b['id_pendaftaran']) ?>">Cetak bukti</a>
      </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
