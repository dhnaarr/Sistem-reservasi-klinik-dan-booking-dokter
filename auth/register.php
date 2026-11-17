<?php
require "../config/koneksi.php";

$error = "";
$sukses = "";


$nama          = trim($_POST['nama'] ?? '');
$email         = trim($_POST['email'] ?? '');
$password      = $_POST['password'] ?? '';
$konfirmasi    = $_POST['konfirmasi'] ?? '';
$nik           = trim($_POST['nik'] ?? '');
$tgl_lahir     = $_POST['tgl_lahir'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$no_hp         = trim($_POST['no_hp'] ?? '');
$alamat        = trim($_POST['alamat'] ?? '');
$no_bpjs       = trim($_POST['no_bpjs'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  
    if ($nama === '' || $email === '' || $password === '' || $nik === '' || $tgl_lahir === '' || $jenis_kelamin === '') {
        $error = "Semua kolom bertanda * wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak sama.";
    } elseif (!preg_match('/^\d{16}$/', $nik)) {
        $error = "NIK harus 16 digit angka.";
    } elseif (!in_array($jenis_kelamin, ['L', 'P'])) {
        $error = "Jenis kelamin tidak valid.";
    } else {

      
        $cekEmail = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $cekEmail->execute([$email]);

        $cekNik = $pdo->prepare("SELECT COUNT(*) FROM pasien WHERE nik = ?");
        $cekNik->execute([$nik]);

        if ($cekEmail->fetchColumn() > 0) {
            $error = "Email sudah terdaftar.";
        } elseif ($cekNik->fetchColumn() > 0) {
            $error = "NIK sudah terdaftar.";
        } else {


            try {
                $pdo->beginTransaction();

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare(
                    "INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'pasien')"
                );
                $stmt->execute([$nama, $email, $hash]);
                $id_user = $pdo->lastInsertId();

                $stmt = $pdo->prepare(
                    "INSERT INTO pasien (id_user, nik, tgl_lahir, jenis_kelamin, no_hp, alamat, no_bpjs)
                     VALUES (?, ?, ?, ?, ?, ?, ?)"
                );
                $stmt->execute([
                    $id_user,
                    $nik,
                    $tgl_lahir,
                    $jenis_kelamin,
                    $no_hp !== '' ? $no_hp : null,
                    $alamat !== '' ? $alamat : null,
                    $no_bpjs !== '' ? $no_bpjs : null
                ]);

                $pdo->commit();
                $sukses = "Registrasi berhasil. Silakan login.";


                $nama = $email = $nik = $tgl_lahir = $jenis_kelamin = $no_hp = $alamat = $no_bpjs = '';

            } catch (PDOException $e) {
                $pdo->rollBack();
                $error = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pasien</title>
</head>
<body>
    <h2>Registrasi Pasien</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($sukses): ?>
        <p style="color:green;"><?= htmlspecialchars($sukses) ?>
           <a href="login.php">Ke halaman login</a></p>
    <?php endif; ?>

    <form method="POST">
        <p>Nama lengkap *<br>
           <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>"></p>

        <p>Email *<br>
           <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"></p>

        <p>Password * (min. 6 karakter)<br>
           <input type="password" name="password"></p>

        <p>Konfirmasi password *<br>
           <input type="password" name="konfirmasi"></p>

        <p>NIK * (16 digit)<br>
           <input type="text" name="nik" maxlength="16" value="<?= htmlspecialchars($nik) ?>"></p>

        <p>Tanggal lahir *<br>
           <input type="date" name="tgl_lahir" value="<?= htmlspecialchars($tgl_lahir) ?>"></p>

        <p>Jenis kelamin *<br>
           <select name="jenis_kelamin">
               <option value="">-- pilih --</option>
               <option value="L" <?= $jenis_kelamin === 'L' ? 'selected' : '' ?>>Laki-laki</option>
               <option value="P" <?= $jenis_kelamin === 'P' ? 'selected' : '' ?>>Perempuan</option>
           </select></p>

        <p>No. HP<br>
           <input type="text" name="no_hp" value="<?= htmlspecialchars($no_hp) ?>"></p>

        <p>Alamat<br>
           <textarea name="alamat"><?= htmlspecialchars($alamat) ?></textarea></p>

        <p>No. BPJS (jika ada)<br>
           <input type="text" name="no_bpjs" value="<?= htmlspecialchars($no_bpjs) ?>"></p>

        <button type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login</a></p>
</body>
</html>
