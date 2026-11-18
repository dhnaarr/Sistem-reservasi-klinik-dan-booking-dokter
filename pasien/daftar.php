<?php

require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$pasien    = data_pasien();
$id_jadwal = (int)($_GET['jadwal'] ?? $_POST['jadwal'] ?? 0);


$j = db_satu("SELECT j.*, d.nama_dokter, d.spesialis, po.nama_poli, po.kode_poli,
        j.kuota - (SELECT COUNT(*) FROM pendaftaran x WHERE x.id_jadwal = j.id_jadwal AND x.status <> 'batal') AS sisa
    FROM jadwal_praktik j
    JOIN dokter d ON d.id_dokter = j.id_dokter
    JOIN poli po  ON po.id_poli = d.id_poli
    WHERE j.id_jadwal = ?", [$id_jadwal]);

if (!$j || $j['status'] !== 'aktif' || $j['tanggal'] < date('Y-m-d')) {
    set_pesan('error', 'Jadwal tidak tersedia.');
    redirect('pasien/dokter.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis      = $_POST['jenis_penjamin'] ?? 'umum';
    $id_asuransi = (int)($_POST['id_asuransi'] ?? 0);

   
    $sudah = db_satu("SELECT 1 FROM pendaftaran WHERE id_pasien = ? AND id_jadwal = ? AND status <> 'batal'",
        [$pasien['id_pasien'], $id_jadwal]);

    if ($jenis === 'bpjs') {
        $error = 'Jaminan BPJS dilayani lewat aplikasi Mobile JKN.';
    } elseif (!in_array($jenis, ['umum', 'asuransi'])) {
        $error = 'Jenis penjamin tidak valid.';
    } elseif ($jenis === 'asuransi' && !$id_asuransi) {
        $error = 'Pilih perusahaan asuransi terlebih dahulu.';
    } elseif ($sudah) {
        $error = 'Anda sudah booking di jadwal ini.';
    } elseif ($j['sisa'] <= 0) {
        $error = 'Kuota jadwal ini sudah penuh.';
    } else {
        if ($jenis === 'umum') $id_asuransi = null;

      
        $no = db_satu('SELECT COALESCE(MAX(no_antrian), 0) + 1 AS berikutnya FROM pendaftaran WHERE id_jadwal = ?', [$id_jadwal])['berikutnya'];

        try {
            db_jalankan('INSERT INTO pendaftaran (id_pasien, id_jadwal, jenis_penjamin, id_asuransi, no_antrian) VALUES (?, ?, ?, ?, ?)',
                [$pasien['id_pasien'], $id_jadwal, $jenis, $id_asuransi, $no]);
            set_pesan('sukses', 'Booking berhasil! Nomor antrian Anda ' . kode_antrian($j['kode_poli'], $no) . '.');
            redirect('pasien/riwayat.php');
        } catch (PDOException $e) {
            $error = 'Booking gagal disimpan, silakan coba lagi.';
        }
    }
}

$asuransi = db_semua('SELECT * FROM asuransi ORDER BY nama_asuransi');

$judul = 'Konfirmasi Booking';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi sempit">
  <div class="judul-halaman">
    <div><h1>Konfirmasi booking</h1>
      <p class="redup">Langkah 3 dari 3 — <a href="<?= url('pasien/jadwal.php?dokter=' . $j['id_dokter']) ?>">‹ ganti tanggal</a></p></div>
  </div>

  <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

  <div class="kartu">
    <h3>Ringkasan</h3>
    <dl class="rincian">
      <dt>Poli</dt><dd><?= e($j['nama_poli']) ?></dd>
      <dt>Dokter</dt><dd><?= e($j['nama_dokter']) ?> (<?= e($j['spesialis']) ?>)</dd>
      <dt>Tanggal</dt><dd><?= e(tgl_indo($j['tanggal'])) ?></dd>
      <dt>Jam</dt><dd><?= jam($j['jam_mulai']) ?> - <?= jam($j['jam_selesai']) ?> (sesi <?= e($j['sesi']) ?>)</dd>
      <dt>Sisa kuota</dt><dd><?= $j['sisa'] ?> slot</dd>
    </dl>
  </div>

  <form method="post" class="kartu" style="margin-top:1rem">
    <input type="hidden" name="jadwal" value="<?= $id_jadwal ?>">
    <h3>Penjamin</h3>
    <label class="pilihan"><input type="radio" name="jenis_penjamin" value="umum" checked> Umum</label>
    <label class="pilihan"><input type="radio" name="jenis_penjamin" value="asuransi"> Asuransi</label>
    <label class="pilihan"><input type="radio" name="jenis_penjamin" value="bpjs"> BPJS (dilayani lewat Mobile JKN)</label>

    <div id="kotak-asuransi" hidden>
      <label>Pilih perusahaan asuransi</label>
      <select name="id_asuransi">
        <option value="0">-- Pilih --</option>
        <?php foreach ($asuransi as $a): ?>
          <option value="<?= $a['id_asuransi'] ?>"><?= e($a['nama_asuransi']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button class="tombol penuh" style="margin-top:1.2rem">Booking sekarang</button>
  </form>
</main>

<script>

var radios = document.querySelectorAll('input[name=jenis_penjamin]');
var kotak = document.getElementById('kotak-asuransi');
function cek() { kotak.hidden = document.querySelector('input[name=jenis_penjamin]:checked').value !== 'asuransi'; }
radios.forEach(function (r) { r.addEventListener('change', cek); });
cek();
</script>
<?php require __DIR__ . '/../includes/footer.php'; ?>
