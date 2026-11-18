<?php
require_once __DIR__ . '/../config/koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}


function e($t) { return htmlspecialchars((string)$t, ENT_QUOTES, 'UTF-8'); }          
function url($p = '') { return BASE_URL . '/' . ltrim($p, '/'); }
function redirect($p) { header('Location: ' . url($p)); exit; }

function flash($k, $v = null) {                                                      
    if ($v !== null) { $_SESSION['flash'][$k] = $v; return; }
    $x = $_SESSION['flash'][$k] ?? null;
    unset($_SESSION['flash'][$k]);
    return $x;
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function csrf_valid() {
    return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

function tgl_indo($tgl) {
    $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $bln  = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $t = strtotime($tgl);
    return $hari[(int)date('w', $t)] . ', ' . date('j', $t) . ' ' . $bln[(int)date('n', $t)] . ' ' . date('Y', $t);
}
function jam($t) { return substr($t, 0, 5); }
function ikon_sesi($s) { return ['pagi' => '☀️', 'siang' => '🌤️', 'sore' => '🌇'][$s] ?? ''; }
function kode_antrian($kodePoli, $no) { return $kodePoli . '-' . str_pad((string)$no, 2, '0', STR_PAD_LEFT); }
function umur($tgl) { return (new DateTime($tgl))->diff(new DateTime('today'))->y; }

function kirim_pesan(PDO $pdo, $id_user, $kategori, $judul, $isi) {
    $pdo->prepare('INSERT INTO pesan (id_user, kategori, judul, isi, dibaca, created_at) VALUES (?,?,?,?,0,NOW())')
        ->execute([$id_user, $kategori, $judul, $isi]);
}


function foto_url($f) {
    if ($f && is_file(__DIR__ . '/../uploads/' . basename($f))) return url('uploads/' . basename($f));
    return 'data:image/svg+xml;utf8,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><circle cx="60" cy="44" r="22" fill="#f2cdb6"/>'
        . '<path d="M14 120c0-32 21-50 46-50s46 18 46 50z" fill="#fff"/><path d="M38 42c0-19 10-28 22-28s22 9 22 28c-6-11-14-13-22-13s-16 2-22 13z" fill="#4a4a4a"/>'
        . '<path d="M44 84c-8 0-8 12 2 12M76 84c8 0 8 12-2 12" stroke="#9aa7ad" stroke-width="3" fill="none"/></svg>'
    );
}

function hapus_foto($nama) {
    $p = __DIR__ . '/../uploads/' . basename((string)$nama);
    if ($nama && is_file($p)) unlink($p);
}


function upload_foto($f, $lama = null) {
    if (!$f || $f['error'] === UPLOAD_ERR_NO_FILE) return $lama;
    if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload foto gagal.');
    if ($f['size'] > 2 * 1024 * 1024) throw new RuntimeException('Ukuran foto maksimal 2 MB.');
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext  = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if (!$ext) throw new RuntimeException('Format foto harus JPG, PNG, atau WebP.');
    $dir = __DIR__ . '/../uploads/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $nama = bin2hex(random_bytes(8)) . '.' . $ext;
    if (!move_uploaded_file($f['tmp_name'], $dir . $nama)) throw new RuntimeException('Foto tidak bisa disimpan.');
    if ($lama) hapus_foto($lama);
    return $nama;
}


function sudah_login() { return isset($_SESSION['id_user']); }

function mulai_session(array $u) {
    session_regenerate_id(true);                      
    $_SESSION['id_user'] = $u['id_user'];
    $_SESSION['nama']    = $u['nama'];
    $_SESSION['role']    = $u['role'];
}

function halaman_awal($role) {
    return ['admin' => 'admin/dashboard.php', 'dokter' => 'dokter/dashboard.php'][$role] ?? 'pasien/dashboard.php';
}

function id_pasien(PDO $pdo) {
    static $id = null;
    if ($id === null) {
        $s = $pdo->prepare('SELECT id_pasien FROM pasien WHERE id_user = ?');
        $s->execute([$_SESSION['id_user']]);
        $id = (int)$s->fetchColumn();
    }
    return $id;
}


function set_remember(PDO $pdo, $id_user) {
    $token = bin2hex(random_bytes(32));
    $pdo->prepare('UPDATE users SET remember_token = ? WHERE id_user = ?')->execute([hash('sha256', $token), $id_user]);
    setcookie('remember', $id_user . ':' . $token, [
        'expires' => time() + 60 * 60 * 24 * 30, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax',
    ]);
}

function hapus_remember(PDO $pdo, $id_user = null) {
    if ($id_user) $pdo->prepare('UPDATE users SET remember_token = NULL WHERE id_user = ?')->execute([$id_user]);
    setcookie('remember', '', time() - 3600, '/');
}

function cek_remember(PDO $pdo) {
    if (sudah_login() || empty($_COOKIE['remember'])) return;
    $b = explode(':', $_COOKIE['remember'], 2);
    if (count($b) !== 2) return;
    $s = $pdo->prepare('SELECT * FROM users WHERE id_user = ?');
    $s->execute([(int)$b[0]]);
    $u = $s->fetch();
    if ($u && $u['remember_token'] && hash_equals($u['remember_token'], hash('sha256', $b[1]))) mulai_session($u);
}
cek_remember($pdo);


function wajib_login() {
    if (!sudah_login()) {
        flash('error', 'Silakan login terlebih dahulu.');
        redirect('auth/login.php');
    }
}

function wajib_role($roles) {
    wajib_login();
    if (!in_array($_SESSION['role'], (array)$roles, true)) redirect(halaman_awal($_SESSION['role']));
}


function wajib_migrasi(PDO $pdo) {
    if (!$pdo->query("SHOW COLUMNS FROM dokter LIKE 'id_user'")->fetch()) {
        http_response_code(500);
        die('<p style="font-family:sans-serif;padding:2rem">Fitur dokter belum aktif. Jalankan file <b>update_dokter.sql</b> sekali di phpMyAdmin (tab Import), lalu muat ulang halaman ini.</p>');
    }
}
