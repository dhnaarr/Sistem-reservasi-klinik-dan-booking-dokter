<?php

require_once __DIR__ . '/../config/koneksi.php';
session_start();




function db_semua($sql, $params = []) {
    global $pdo;
    $s = $pdo->prepare($sql);
    $s->execute($params);
    return $s->fetchAll();
}


function db_satu($sql, $params = []) {
    global $pdo;
    $s = $pdo->prepare($sql);
    $s->execute($params);
    return $s->fetch();
}


function db_jalankan($sql, $params = []) {
    global $pdo;
    $s = $pdo->prepare($sql);
    $s->execute($params);
    return $s;
}




function e($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES, 'UTF-8');
}

function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirect($path) {
    header('Location: ' . url($path));
    exit;
}


function set_pesan($jenis, $isi) {
    $_SESSION['pesan'] = ['jenis' => $jenis, 'isi' => $isi];
}



function sudah_login() {
    return isset($_SESSION['id_user']);
}


function halaman_awal($role) {
    if ($role === 'admin')  return 'admin/dashboard.php';
    if ($role === 'dokter') return 'dokter/dashboard.php';
    return 'pasien/dashboard.php';
}


function wajib_role($role) {
    if (!sudah_login()) {
        set_pesan('error', 'Silakan login terlebih dahulu.');
        redirect('auth/login.php');
    }
    if ($_SESSION['role'] !== $role) {
        redirect(halaman_awal($_SESSION['role']));
    }
}


function data_pasien() {
    return db_satu('SELECT * FROM pasien WHERE id_user = ?', [$_SESSION['id_user']]);
}


function data_dokter() {
    $d = db_satu('SELECT d.*, p.nama_poli, p.kode_poli FROM dokter d
                  JOIN poli p ON p.id_poli = d.id_poli WHERE d.id_user = ?', [$_SESSION['id_user']]);
    if (!$d) die('Akun ini belum terhubung dengan data dokter. Hubungi admin.');
    return $d;
}



function tgl_indo($tgl) {
    $hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    $t = strtotime($tgl);
    return $hari[date('w', $t)] . ', ' . date('j', $t) . ' ' . $bulan[date('n', $t)] . ' ' . date('Y', $t);
}

function jam($waktu) {            
    return substr($waktu, 0, 5);
}

function kode_antrian($kode_poli, $nomor) {   
    return $kode_poli . '-' . str_pad($nomor, 2, '0', STR_PAD_LEFT);
}

function kode_booking($id) {                  
    return 'BK' . str_pad($id, 5, '0', STR_PAD_LEFT);
}


function inisial($nama) {
    return strtoupper(substr(preg_replace('/^(drg|dr)\.?\s*/i', '', $nama), 0, 1));
}

function umur($tgl_lahir) {
    return (new DateTime($tgl_lahir))->diff(new DateTime('today'))->y;
}


function label_status($status) {
    $daftar = [
        'aktif'            => ['Aktif', 'biru'],
        'sudah_registrasi' => ['Sudah registrasi', 'hijau'],
        'batal'            => ['Batal', 'merah'],
    ];
    return $daftar[$status];
}

