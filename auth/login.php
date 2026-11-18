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
