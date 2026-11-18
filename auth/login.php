<?php
require_once __DIR__ . '/../includes/fungsi.php';


if (sudah_login()) redirect(halaman_awal($_SESSION['role']));

$error = '';
$email = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = db_satu('SELECT * FROM users WHERE email = ?', [$email]);

    if ($user && $password === $user['password']) {

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];
        redirect(halaman_awal($user['role']));
    }
    $error = 'Email atau password salah.';
}

$judul = 'Masuk';
$halaman_auth = true;
require __DIR__ . '/../includes/header.php';
?>
<main class="auth-wrap">
  <section class="auth-info">
    <div class="logo-besar">🏥</div>
    <h1>Booking dokter dari rumah, tanpa antre di loket.</h1>
    <p>Pilih poli, dokter, dan tanggal periksa. Nomor antrian dan kode booking langsung Anda terima.</p>
  </section>

  <section class="auth-kartu">
    <h2>Masuk ke akun</h2>
    <p class="redup">Pasien, dokter, dan admin masuk dari sini.</p>

    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

    <form method="post">
      <label>Email</label>
      <input type="email" name="email" value="<?= e($email) ?>" required autofocus>
      <label>Password</label>
      <input type="password" name="password" required>
      <button class="tombol penuh" style="margin-top:1.2rem">Masuk</button>
    </form>
    <p class="redup tengah">Belum punya akun? <a href="<?= url('auth/register.php') ?>">Daftar sebagai pasien</a></p>
  </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
