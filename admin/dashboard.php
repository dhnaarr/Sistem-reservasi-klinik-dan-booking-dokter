<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


$jml_pasien  = db_satu('SELECT COUNT(*) AS n FROM pasien')['n'];
$jml_dokter  = db_satu('SELECT COUNT(*) AS n FROM dokter')['n'];
$jml_jadwal  = db_satu("SELECT COUNT(*) AS n FROM jadwal_praktik WHERE tanggal >= CURDATE() AND status = 'aktif'")['n'];
$jml_hari_ini = db_satu("SELECT COUNT(*) AS n FROM pendaftaran p JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
                         WHERE j.tanggal = CURDATE() AND p.status <> 'batal'")['n'];


$terbaru = db_semua("SELECT p.id_pendaftaran, p.status, p.no_antrian, u.nama AS pasien, d.nama_dokter, j.tanggal, po.kode_poli
    FROM pendaftaran p
    JOIN pasien ps ON ps.id_pasien = p.id_pasien
    JOIN users u   ON u.id_user = ps.id_user
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN dokter d  ON d.id_dokter = j.id_dokter
    JOIN poli po   ON po.id_poli = d.id_poli
    ORDER BY p.id_pendaftaran DESC LIMIT 8");

$judul = 'Dashboard Admin';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1>Dashboard admin</h1><p class="redup"><?= e(tgl_indo(date('Y-m-d'))) ?></p></div>
  </div>

  <div class="statistik">
    <div class="stat"><strong><?= $jml_hari_ini ?></strong><span>Booking hari ini</span></div>
    <div class="stat"><strong><?= $jml_pasien ?></strong><span>Pasien terdaftar</span></div>
    <div class="stat"><strong><?= $jml_dokter ?></strong><span>Dokter</span></div>
    <div class="stat"><strong><?= $jml_jadwal ?></strong><span>Jadwal aktif ke depan</span></div>
  </div>

  <div class="menu-grid">
    <a class="menu-kartu" href="<?= url('admin/poli.php') ?>"><span class="ikon">🏥</span><h3>Poli</h3><p>Nama dan kode poli</p></a>
    <a class="menu-kartu" href="<?= url('admin/dokter.php') ?>"><span class="ikon">🩺</span><h3>Dokter</h3><p>Data dan akun login dokter</p></a>
    <a class="menu-kartu" href="<?= url('admin/jadwal.php') ?>"><span class="ikon">📅</span><h3>Jadwal</h3><p>Sesi, kuota, dan cuti</p></a>
    <a class="menu-kartu" href="<?= url('admin/asuransi.php') ?>"><span class="ikon">🛡️</span><h3>Asuransi</h3><p>Daftar penjamin</p></a>
  </div>

  <h2 class="sub-judul">Booking terbaru</h2>
  <div class="tabel-bungkus"><table>
    <tr><th>Kode</th><th>Pasien</th><th>Dokter</th><th>Tanggal</th><th>Antrian</th><th>Status</th></tr>
    <?php if (!$terbaru): ?><tr><td colspan="6" class="kosong">Belum ada booking.</td></tr><?php endif; ?>
    <?php foreach ($terbaru as $r): [$teks, $warna] = label_status($r['status']); ?>
    <tr>
      <td><b><?= kode_booking($r['id_pendaftaran']) ?></b></td>
      <td><?= e($r['pasien']) ?></td>
      <td><?= e($r['nama_dokter']) ?></td>
      <td><?= e(tgl_indo($r['tanggal'])) ?></td>
      <td><b><?= e(kode_antrian($r['kode_poli'], $r['no_antrian'])) ?></b></td>
      <td><span class="pil <?= $warna ?>"><?= $teks ?></span></td>
    </tr>
    <?php endforeach; ?>
  </table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
