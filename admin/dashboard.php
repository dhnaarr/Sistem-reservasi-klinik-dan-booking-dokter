<?php
require_once __DIR__ . '/../includes/auth.php';
wajib_role('admin');

$total = [];
foreach (['poli', 'dokter', 'jadwal_praktik', 'pasien', 'pendaftaran'] as $t) {
    $total[$t] = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
}

$judul = 'Dashboard Admin';
require __DIR__ . '/../includes/header.php';
?>
<main class="container">
  <section class="welcome-banner">
    <h1>Dashboard Admin</h1>
    <p>Ringkasan aktivitas dan pengelolaan data Klinik Sehat Bersama.</p>
  </section>
  <div class="page-heading"><div><h1>Ringkasan klinik</h1><p>Pantau data operasional klinik dalam satu tempat.</p></div></div>
  <div class="stats">
    <div class="card stat"><strong><?= $total['poli'] ?></strong><span>Poli</span></div>
    <div class="card stat"><strong><?= $total['dokter'] ?></strong><span>Dokter</span></div>
    <div class="card stat"><strong><?= $total['jadwal_praktik'] ?></strong><span>Jadwal praktik</span></div>
    <div class="card stat"><strong><?= $total['pasien'] ?></strong><span>Pasien</span></div>
    <div class="card stat"><strong><?= $total['pendaftaran'] ?></strong><span>Booking</span></div>
  </div>

  <div class="section-title"><h2>Kelola data</h2></div>
  <div class="menu">
    <div class="card menu-item disabled">Poli<small>CRUD segera dibuat</small></div>
    <div class="card menu-item disabled">Dokter<small>CRUD + upload foto</small></div>
    <div class="card menu-item disabled">Jadwal praktik<small>CRUD segera dibuat</small></div>
    <div class="card menu-item disabled">Asuransi<small>CRUD segera dibuat</small></div>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
