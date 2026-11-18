<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$pasien = data_pasien();


$r = db_satu("SELECT p.*, j.tanggal, j.sesi, j.jam_mulai, j.jam_selesai,
        d.nama_dokter, d.spesialis, po.nama_poli, po.kode_poli, a.nama_asuransi
    FROM pendaftaran p
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN dokter d ON d.id_dokter = j.id_dokter
    JOIN poli po  ON po.id_poli = d.id_poli
    LEFT JOIN asuransi a ON a.id_asuransi = p.id_asuransi
    WHERE p.id_pendaftaran = ? AND p.id_pasien = ?", [(int)($_GET['id'] ?? 0), $pasien['id_pasien']]);

if (!$r) {
    set_pesan('error', 'Bukti booking tidak ditemukan.');
    redirect('pasien/riwayat.php');
}

$penjamin = ['umum' => 'Umum', 'bpjs' => 'BPJS Kesehatan', 'asuransi' => 'Asuransi - ' . $r['nama_asuransi']][$r['jenis_penjamin']];
[$status_teks] = label_status($r['status']);

$judul = 'Bukti Booking';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi sempit">
  <div class="struk">
    <h2>🏥 Klinik Sehat Bersama</h2>
    <p class="redup">Bukti pendaftaran booking</p>

    <div class="struk-nomor">
      <small>Nomor antrian</small>
      <strong><?= e(kode_antrian($r['kode_poli'], $r['no_antrian'])) ?></strong>
      <span>Kode booking: <?= kode_booking($r['id_pendaftaran']) ?></span>
    </div>

    <dl class="rincian">
      <dt>Nama pasien</dt><dd><?= e($_SESSION['nama']) ?></dd>
      <dt>NIK</dt><dd><?= e($pasien['nik']) ?></dd>
      <dt>Poli</dt><dd><?= e($r['nama_poli']) ?></dd>
      <dt>Dokter</dt><dd><?= e($r['nama_dokter']) ?> (<?= e($r['spesialis']) ?>)</dd>
      <dt>Tanggal</dt><dd><?= e(tgl_indo($r['tanggal'])) ?></dd>
      <dt>Jam</dt><dd><?= jam($r['jam_mulai']) ?> - <?= jam($r['jam_selesai']) ?></dd>
      <dt>Penjamin</dt><dd><?= e($penjamin) ?></dd>
      <dt>Status</dt><dd><?= $status_teks ?></dd>
    </dl>
    <p class="catatan">Tunjukkan bukti ini di loket pendaftaran 15 menit sebelum jam praktik dimulai.</p>
  </div>

  <p class="tengah tanpa-cetak" style="margin-top:1.2rem">
    <button class="tombol" onclick="window.print()">🖨️ Cetak / simpan PDF</button>
    <a class="tombol garis" href="<?= url('pasien/riwayat.php') ?>">Kembali</a>
  </p>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

