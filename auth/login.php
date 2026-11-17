<?php
require "../config/koneksi.php";
require "../includes/auth.php";


if (isset($_SESSION['id_user'])) {
    header("Location: " . dashboard_url($_SESSION['role']));
    exit;
}

$error = "";
$email = trim($_POST['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = "Email dan password wajib diisi.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['role']    = $user['role'];

          
            if ($user['role'] === 'pasien') {
                $q = $pdo->prepare("SELECT id_pasien FROM pasien WHERE id_user = ?");
                $q->execute([$user['id_user']]);
                $_SESSION['id_pasien'] = $q->fetchColumn();
            } elseif ($user['role'] === 'dokter') {
                $q = $pdo->prepare("SELECT id_dokter FROM dokter WHERE id_user = ?");
                $q->execute([$user['id_user']]);
                $_SESSION['id_dokter'] = $q->fetchColumn();
            }

            header("Location: " . dashboard_url($user['role']));
            exit;
        } else {
       
            $error = "Email atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <p>Email<br>
           <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"></p>
        <p>Password<br>
           <input type="password" name="password"></p>
        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
</body>
</html>
