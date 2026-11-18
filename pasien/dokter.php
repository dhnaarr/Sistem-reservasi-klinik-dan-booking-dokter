<?php

require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$cari = trim($_GET['q'] ?? '');
$poli = (int)($_GET['poli'] ?? 0);


$sql = "SELECT d.*, p.nama_poli,
        (SELECT COUNT(*) FROM jadwal_praktik j
         WHERE j.id_dokter = d.id_dokter AND j.tanggal >= CURDATE() AND j.status = 'aktif') AS jml_jadwal
        FROM dokter d JOIN poli p ON p.id_poli = d.id_poli
        WHERE (d.nama_dokter LIKE ? OR d.spesialis LIKE ? OR p.nama_poli LIKE ?)";
$param = ["%$cari%", "%$cari%", "%$cari%"];
if ($poli) {
    $sql .= ' AND d.id_poli = ?';
    $param[] = $poli;
}
$sql .= ' ORDER BY p.nama_poli, d.nama_dokter';

$dokters = db_semua($sql, $param);
$semua_poli = db_semua('SELECT * FROM poli ORDER BY nama_poli');

$judul = 'Pilih Dokter';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1>Pilih poli / dokter</h1><p class="redup">Langkah 1 dari 3 — cari dokter, lalu pilih tanggal periksa.</p></div>
  </div>

  <form class="baris-cari" method="get">
    <input type="search" name="q" value="<?= e($cari) ?>" placeholder="🔍 Cari nama dokter atau poli">
    <select name="poli">
      <option value="0">Semua poli</option>
      <?php foreach ($semua_poli as $p): ?>
        <option value="<?= $p['id_poli'] ?>" <?= $poli == $p['id_poli'] ? 'selected' : '' ?>><?= e($p['nama_poli']) ?></option>
      <?php endforeach; ?>
    </select>
    <button class="tombol">Cari</button>
  </form>

  <?php if (!$dokters): ?><div class="kartu kosong">Dokter tidak ditemukan.</div><?php endif; ?>
  <div class="grid">
    <?php foreach ($dokters as $d): ?>
    <a class="kartu dokter-kartu" href="<?= url('pasien/jadwal.php?dokter=' . $d['id_dokter']) ?>">
      <div class="avatar"><?= e(inisial($d['nama_dokter'])) ?></div>
      <span class="pil biru"><?= e($d['nama_poli']) ?></span>
      <h3><?= e($d['nama_dokter']) ?></h3>
      <p class="redup"><?= e($d['spesialis']) ?></p>
      <p class="<?= $d['jml_jadwal'] ? 'hijau-teks' : 'redup' ?>">
        <?= $d['jml_jadwal'] ? $d['jml_jadwal'] . ' jadwal tersedia' : 'Belum ada jadwal' ?>
      </p>
    </a>
    <?php endforeach; ?>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
