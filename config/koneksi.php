<?php
define('BASE_URL', '/sistem-klinik-web');

$db_host = 'localhost';
$db_name = 'db_klinik';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $ex) {
    die('Koneksi database gagal. Pastikan MySQL di XAMPP aktif dan database db_klinik sudah di-import.');
}
