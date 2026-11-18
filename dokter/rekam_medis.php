<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('dokter');

$dokter = data_dokter();
$id     = (int)($_GET['id'] ?? $_POST['id'] ?? 0);


if ($id) {
    
    $k = db_satu("SELECT p.*, u.nama AS nama_pasien, ps.id_pasien, ps.nik, ps.tgl_lahir, ps.jenis_kelamin, ps.no_hp, j.tanggal, j.jam_mulai
        FROM pendaftaran p
        JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
        JOIN pasien ps ON ps.id_pasien = p.id_pasien
        JOIN users u   ON u.id_user = ps.id_user
        WHERE p.id_pendaftaran = ? AND j.id_dokter = ? AND p.status <> 'batal'", [$id, $dokter['id_dokter']]);
    if (!$k) {
        set_pesan('error', 'Data kunjungan tidak ditemukan.');
        redirect('dokter/dashboard.php');
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $keluhan  = trim($_POST['keluhan']);
        $diagnosa = trim($_POST['diagnosa']);
        $tindakan = trim($_POST['tindakan']);
        $resep    = trim($_POST['resep']);

        if ($keluhan === '' || $diagnosa === '') {
            set_pesan('error', 'Keluhan dan diagnosa wajib diisi.');
        } else {
            
            if (db_satu('SELECT 1 FROM rekam_medis WHERE id_pendaftaran = ?', [$id])) {
                db_jalankan('UPDATE rekam_medis SET keluhan = ?, diagnosa = ?, tindakan = ?, resep = ? WHERE id_pendaftaran = ?',
                    [$keluhan, $diagnosa, $tindakan ?: null, $resep ?: null, $id]);
            } else {
                db_jalankan('INSERT INTO rekam_medis (id_pendaftaran, keluhan, diagnosa, tindakan, resep) VALUES (?, ?, ?, ?, ?)',
                    [$id, $keluhan, $diagnosa, $tindakan ?: null, $resep ?: null]);
            }
         
            db_jalankan("UPDATE pendaftaran SET status = 'sudah_registrasi' WHERE id_pendaftaran = ? AND status = 'aktif'", [$id]);
            set_pesan('sukses', 'Rekam medis disimpan.');
        }
        redirect('dokter/rekam_medis.php?id=' . $id);
    }


    $rm = db_satu('SELECT * FROM rekam_medis WHERE id_pendaftaran = ?', [$id])
          ?: ['keluhan' => '', 'diagnosa' => '', 'tindakan' => '', 'resep' => ''];

 
    $riwayat = db_semua("SELECT r.*, j.tanggal FROM rekam_medis r
        JOIN pendaftaran p ON p.id_pendaftaran = r.id_pendaftaran
        JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
        WHERE p.id_pasien = ? AND r.id_pendaftaran <> ?
        ORDER BY j.tanggal DESC LIMIT 10", [$k['id_pasien'], $id]);

    $judul = 'Rekam Medis';
    require __DIR__ . '/../includes/header.php';
?>
<main class="isi sempit">
  <div class="judul-halaman">
    <div><h1>Rekam medis</h1>
      <p class="redup"><a href="<?= url('dokter/dashboard.php?tanggal=' . $k['tanggal']) ?>">‹ Kembali ke daftar pasien</a></p></div>
  </div>

  <div class="kartu">
    <h3><?= e($k['nama_pasien']) ?></h3>
    <p class="redup"><?= umur($k['tgl_lahir']) ?> tahun · <?= $k['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
      · NIK <?= e($k['nik']) ?> · HP <?= e($k['no_hp']) ?></p>
    <p>Kunjungan: <?= e(tgl_indo($k['tanggal'])) ?>, jam <?= jam($k['jam_mulai']) ?></p>
  </div>

  <form method="post" class="kartu" style="margin-top:1rem">
    <input type="hidden" name="id" value="<?= $id ?>">
    <label>Keluhan</label><textarea name="keluhan" rows="3" required><?= e($rm['keluhan']) ?></textarea>
    <label>Diagnosa</label><textarea name="diagnosa" rows="3" required><?= e($rm['diagnosa']) ?></textarea>
    <label>Tindakan</label><textarea name="tindakan" rows="2"><?= e($rm['tindakan']) ?></textarea>
    <label>Resep</label><textarea name="resep" rows="3"><?= e($rm['resep']) ?></textarea>
    <button class="tombol" style="margin-top:1rem">Simpan rekam medis</button>
  </form>

  <h2 class="sub-judul">Riwayat kunjungan sebelumnya</h2>
  <?php if (!$riwayat): ?><div class="kartu kosong">Belum ada rekam medis sebelumnya untuk pasien ini.</div><?php endif; ?>
  <?php foreach ($riwayat as $r): ?>
    <div class="kartu" style="margin-bottom:.7rem">
      <b><?= e(tgl_indo($r['tanggal'])) ?></b>
      <p><b>Keluhan:</b> <?= e($r['keluhan']) ?><br>
         <b>Diagnosa:</b> <?= e($r['diagnosa']) ?>
         <?php if ($r['tindakan']): ?><br><b>Tindakan:</b> <?= e($r['tindakan']) ?><?php endif; ?>
         <?php if ($r['resep']): ?><br><b>Resep:</b> <?= e($r['resep']) ?><?php endif; ?></p>
    </div>
  <?php endforeach; ?>
</main>
<?php
    require __DIR__ . '/../includes/footer.php';
    exit;
}


$rows = db_semua("SELECT r.*, p.id_pendaftaran, u.nama AS nama_pasien, j.tanggal
    FROM rekam_medis r
    JOIN pendaftaran p ON p.id_pendaftaran = r.id_pendaftaran
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN pasien ps ON ps.id_pasien = p.id_pasien
    JOIN users u   ON u.id_user = ps.id_user
    WHERE j.id_dokter = ?
    ORDER BY j.tanggal DESC, r.id_rekam DESC LIMIT 100", [$dokter['id_dokter']]);

$judul = 'Rekam Medis';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1>Rekam medis</h1><p class="redup">Semua rekam medis yang pernah Anda isi.</p></div>
  </div>

  <div class="tabel-bungkus"><table>
    <tr><th>Tanggal</th><th>Pasien</th><th>Keluhan</th><th>Diagnosa</th><th></th></tr>
    <?php if (!$rows): ?>
      <tr><td colspan="5" class="kosong">Belum ada rekam medis. Isi dari menu <a href="<?= url('dokter/dashboard.php') ?>">Pasien Saya</a>.</td></tr>
    <?php endif; ?>
    <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= e(tgl_indo($r['tanggal'])) ?></td>
      <td><b><?= e($r['nama_pasien']) ?></b></td>
      <td><?= e(mb_strimwidth($r['keluhan'], 0, 50, '...')) ?></td>
      <td><?= e(mb_strimwidth($r['diagnosa'], 0, 50, '...')) ?></td>
      <td class="aksi"><a class="tombol kecil garis" href="<?= url('dokter/rekam_medis.php?id=' . $r['id_pendaftaran']) ?>">Buka</a></td>
    </tr>
    <?php endforeach; ?>
  </table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
