<?php

$judul        = $judul ?? 'Klinik Sehat Bersama';
$halaman_auth = $halaman_auth ?? false;
$role         = $_SESSION['role'] ?? '';


$semua_menu = [
    'admin'  => ['admin/dashboard.php' => 'Dashboard', 'admin/poli.php' => 'Poli', 'admin/dokter.php' => 'Dokter',
                 'admin/jadwal.php' => 'Jadwal', 'admin/asuransi.php' => 'Asuransi'],
    'dokter' => ['dokter/dashboard.php' => 'Pasien Saya', 'dokter/rekam_medis.php' => 'Rekam Medis'],
    'pasien' => ['pasien/dashboard.php' => 'Beranda', 'pasien/dokter.php' => 'Booking', 'pasien/riwayat.php' => 'Riwayat'],
];
$menu = $semua_menu[$role] ?? [];
$halaman_ini = ltrim(substr($_SERVER['SCRIPT_NAME'], strlen(BASE_URL)), '/');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($judul) ?> | Klinik Sehat Bersama</title>
  <link rel="stylesheet" href="<?= url('assets/style.css') ?>">
</head>
<body class="<?= $halaman_auth ? 'auth' : '' ?>">

<?php if (sudah_login()): ?>
<header class="navbar">
  <div class="navbar-isi">
    <a class="brand" href="<?= url(halaman_awal($role)) ?>">🏥 Sehat Bersama</a>
    <nav>
      <?php foreach ($menu as $path => $label): ?>
        <a href="<?= url($path) ?>" class="<?= $path === $halaman_ini ? 'aktif' : '' ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="user">
      <span><?= e($_SESSION['nama']) ?></span>
      <a class="tombol kecil garis" href="<?= url('auth/logout.php') ?>">Keluar</a>
    </div>
  </div>
</header>
<?php endif; ?>

<?php

if (isset($_SESSION['pesan'])) {
    $p = $_SESSION['pesan'];
    unset($_SESSION['pesan']);
    echo '<div class="kotak-pesan"><div class="alert ' . e($p['jenis']) . '">' . e($p['isi']) . '</div></div>';
}
?>
