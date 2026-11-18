<?php

define('BASE_URL', '/sistem-klinik-web');

$host = 'localhost';
$user = 'root';      
$pass = '';          
$db   = 'db_klinik';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Koneksi database gagal. Pastikan MySQL di XAMPP sudah Start dan db_klinik.sql sudah di-import.');
}
