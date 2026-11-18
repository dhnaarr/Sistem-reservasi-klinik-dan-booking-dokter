<?php
require_once __DIR__ . '/../includes/fungsi.php';
if (sudah_login()) redirect(halaman_awal($_SESSION['role']));

$errors = [];

$in = ['nama' => '', 'email' => '', 'nik' => '', 'tgl_lahir' => '', 'jenis_kelamin' => '', 'no_hp' => '', 'alamat' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($in as $k => $v) $in[$k] = trim($_POST[$k] ?? '');
    $password = $_POST['password'] ?? '';
    $ulangi   = $_POST['ulangi'] ?? '';

    // ---- Validasi ----
    if (strlen($in['nama']) < 3)                           $errors[] = 'Nama minimal 3 karakter.';
    if (!filter_var($in['email'], FILTER_VALIDATE_EMAIL))  $errors[] = 'Format email tidak valid.';
    if (!preg_match('/^[0-9]{16}$/', $in['nik']))          $errors[] = 'NIK harus 16 digit angka.';
    if ($in['tgl_lahir'] === '')                           $errors[] = 'Tanggal lahir wajib diisi.';
    if (!in_array($in['jenis_kelamin'], ['L', 'P']))       $errors[] = 'Pilih jenis kelamin.';
    if (!preg_match('/^[0-9+]{9,15}$/', $in['no_hp']))     $errors[] = 'No. HP harus 9-15 digit.';
    if (strlen($password) < 6)                             $errors[] = 'Password minimal 6 karakter.';
    if ($password !== $ulangi)                             $errors[] = 'Ulangi password tidak sama.';

 
    if (!$errors) {
        if (db_satu('SELECT 1 FROM users WHERE email = ?', [$in['email']])) $errors[] = 'Email sudah terdaftar.';
        if (db_satu('SELECT 1 FROM pasien WHERE nik = ?', [$in['nik']]))    $errors[] = 'NIK sudah terdaftar.';
    }


    if (!$errors) {
        db_jalankan("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'pasien')",
            [$in['nama'], $in['email'], $password]);
        $id_user = $pdo->lastInsertId();

        db_jalankan('INSERT INTO pasien (id_user, nik, tgl_lahir, jenis_kelamin, no_hp, alamat) VALUES (?, ?, ?, ?, ?, ?)',
            [$id_user, $in['nik'], $in['tgl_lahir'], $in['jenis_kelamin'], $in['no_hp'], $in['alamat']]);

        set_pesan('sukses', 'Pendaftaran berhasil. Silakan masuk.');
        redirect('auth/login.php');
    }
}

$judul = 'Daftar';
$halaman_auth = true;
require __DIR__ . '/../includes/header.php';
?>
<main class="auth-wrap satu">
  <section class="auth-kartu">
    <h2>Buat akun pasien</h2>
    <p class="redup">Data diri dipakai untuk pendaftaran berobat.</p>

    <?php if ($errors): ?>
      <div class="alert error"><ul><?php foreach ($errors as $m): ?><li><?= e($m) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <form method="post">
      <div class="dua-kolom">
        <div><label>Nama lengkap</label><input name="nama" value="<?= e($in['nama']) ?>" required></div>
        <div><label>Email</label><input type="email" name="email" value="<?= e($in['email']) ?>" required></div>
        <div><label>NIK (16 digit)</label><input name="nik" value="<?= e($in['nik']) ?>" maxlength="16" required></div>
        <div><label>No. HP</label><input name="no_hp" value="<?= e($in['no_hp']) ?>" required></div>
        <div><label>Tanggal lahir</label><input type="date" name="tgl_lahir" value="<?= e($in['tgl_lahir']) ?>" max="<?= date('Y-m-d') ?>" required></div>
        <div><label>Jenis kelamin</label>
          <select name="jenis_kelamin" required>
            <option value="">Pilih</option>
            <option value="L" <?= $in['jenis_kelamin'] === 'L' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="P" <?= $in['jenis_kelamin'] === 'P' ? 'selected' : '' ?>>Perempuan</option>
          </select></div>
      </div>
      <label>Alamat</label>
      <textarea name="alamat" rows="2"><?= e($in['alamat']) ?></textarea>
      <div class="dua-kolom">
        <div><label>Password</label><input type="password" name="password" required></div>
        <div><label>Ulangi password</label><input type="password" name="ulangi" required></div>
      </div>
      <button class="tombol penuh" style="margin-top:1.2rem">Daftar</button>
    </form>
    <p class="redup tengah">Sudah punya akun? <a href="<?= url('auth/login.php') ?>">Masuk</a></p>
  </section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
