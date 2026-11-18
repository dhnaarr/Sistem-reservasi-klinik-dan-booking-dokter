<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('dokter');

$dokter  = data_dokter();
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $hasil = db_jalankan("UPDATE pendaftaran p JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
        SET p.status = 'sudah_registrasi'
        WHERE p.id_pendaftaran = ? AND j.id_dokter = ? AND p.status = 'aktif'",
        [(int)$_POST['id'], $dokter['id_dokter']]);

    if ($hasil->rowCount()) set_pesan('sukses', 'Pasien ditandai sudah registrasi.');
    else                    set_pesan('error', 'Status tidak bisa diubah.');
    redirect('dokter/dashboard.php?tanggal=' . $tanggal);
}


$pasien = db_semua("SELECT p.*, u.nama AS nama_pasien, ps.tgl_lahir, ps.jenis_kelamin, j.sesi, j.jam_mulai,
        a.nama_asuransi,
        (SELECT COUNT(*) FROM rekam_medis r WHERE r.id_pendaftaran = p.id_pendaftaran) AS sudah_diperiksa
    FROM pendaftaran p
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN pasien ps ON ps.id_pasien = p.id_pasien
    JOIN users u   ON u.id_user = ps.id_user
    LEFT JOIN asuransi a ON a.id_asuransi = p.id_asuransi
    WHERE j.id_dokter = ? AND j.tanggal = ? AND p.status <> 'batal'
    ORDER BY j.jam_mulai, p.no_antrian", [$dokter['id_dokter'], $tanggal]);

$total   = count($pasien);
$selesai = count(array_filter($pasien, fn($p) => $p['sudah_diperiksa']));
$menunggu = count(array_filter($pasien, fn($p) => $p['status'] === 'sudah_registrasi' && !$p['sudah_diperiksa']));

$judul = 'Pasien Saya';
require __DIR__ . '/../includes/header.php';
?>
<main class="isi">
  <div class="judul-halaman">
    <div><h1><?= e($dokter['nama_dokter']) ?></h1><p class="redup"><?= e($dokter['spesialis']) ?> · <?= e($dokter['nama_poli']) ?></p></div>
  </div>

  <form method="get" class="baris-cari">
    <label style="margin:0">Tanggal praktik</label>
    <input type="date" name="tanggal" value="<?= e($tanggal) ?>" style="max-width:200px">
    <button class="tombol">Tampilkan</button>
    <a class="tombol garis" href="<?= url('dokter/dashboard.php') ?>">Hari ini</a>
  </form>

  <div class="statistik">
    <div class="stat"><strong><?= $total ?></strong><span>Total pasien</span></div>
    <div class="stat"><strong><?= $menunggu ?></strong><span>Menunggu diperiksa</span></div>
    <div class="stat"><strong><?= $selesai ?></strong><span>Sudah diperiksa</span></div>
  </div>

  <h2 class="sub-judul"><?= e(tgl_indo($tanggal)) ?></h2>
  <div class="tabel-bungkus"><table>
    <tr><th>Antrian</th><th>Pasien</th><th>Jam</th><th>Penjamin</th><th>Status</th><th></th></tr>
    <?php if (!$pasien): ?><tr><td colspan="6" class="kosong">Tidak ada pasien pada tanggal ini.</td></tr><?php endif; ?>
    <?php foreach ($pasien as $p): ?>
    <tr>
      <td><b><?= e(kode_antrian($dokter['kode_poli'], $p['no_antrian'])) ?></b></td>
      <td><b><?= e($p['nama_pasien']) ?></b><br>
        <small class="redup"><?= umur($p['tgl_lahir']) ?> th · <?= $p['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></small></td>
      <td><?= jam($p['jam_mulai']) ?></td>
      <td><?= $p['jenis_penjamin'] === 'asuransi' ? e($p['nama_asuransi']) : ucfirst($p['jenis_penjamin']) ?></td>
      <td>
        <?php if ($p['sudah_diperiksa']): ?><span class="pil hijau">Selesai</span>
        <?php elseif ($p['status'] === 'sudah_registrasi'): ?><span class="pil kuning">Menunggu periksa</span>
        <?php else: ?><span class="pil biru">Belum hadir</span><?php endif; ?>
      </td>
      <td class="aksi">
        <?php if ($p['status'] === 'aktif'): ?>
          <form method="post">
            <input type="hidden" name="id" value="<?= $p['id_pendaftaran'] ?>">
            <button class="tombol kecil garis">Tandai hadir</button>
          </form>
        <?php endif; ?>
        <a class="tombol kecil" href="<?= url('dokter/rekam_medis.php?id=' . $p['id_pendaftaran']) ?>">
          <?= $p['sudah_diperiksa'] ? 'Lihat rekam medis' : 'Isi rekam medis' ?></a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table></div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
