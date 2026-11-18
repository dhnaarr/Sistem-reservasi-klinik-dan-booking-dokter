<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    if (isset($_POST['hapus'])) {
        $d = db_satu('SELECT * FROM dokter WHERE id_dokter = ?', [(int)$_POST['hapus']]);
        if ($d) {
            db_jalankan('DELETE FROM dokter WHERE id_dokter = ?', [$d['id_dokter']]);
            if ($d['id_user']) db_jalankan('DELETE FROM users WHERE id_user = ?', [$d['id_user']]);
            set_pesan('sukses', 'Dokter beserta jadwalnya dihapus.');
        }
        redirect('admin/dokter.php');
    }

 
    $id       = (int)$_POST['id'];                      
    $id_poli  = (int)$_POST['id_poli'];
    $nama     = trim($_POST['nama_dokter']);
    $spesialis = trim($_POST['spesialis']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $lama = $id ? db_satu('SELECT * FROM dokter WHERE id_dokter = ?', [$id]) : null;
    $id_user_lama = $lama['id_user'] ?? 0;


    $dipakai = db_satu('SELECT 1 FROM users WHERE email = ? AND id_user <> ?', [$email, $id_user_lama]);

    if (!$id_poli || $nama === '' || $spesialis === '') {
        set_pesan('error', 'Poli, nama, dan spesialis wajib diisi.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_pesan('error', 'Email login tidak valid.');
    } elseif ($dipakai) {
        set_pesan('error', 'Email sudah dipakai akun lain.');
    } elseif (!$id_user_lama && strlen($password) < 6) {
        set_pesan('error', 'Password minimal 6 karakter untuk akun baru.');
    } elseif ($password !== '' && strlen($password) < 6) {
        set_pesan('error', 'Password minimal 6 karakter.');
    } else {
        if ($id_user_lama) {
            
            db_jalankan('UPDATE users SET nama = ?, email = ? WHERE id_user = ?', [$nama, $email, $id_user_lama]);
            if ($password !== '') {
                db_jalankan('UPDATE users SET password = ? WHERE id_user = ?', [$password, $id_user_lama]);
            }
            $id_user = $id_user_lama;
        } else {
           
            db_jalankan("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'dokter')",
                [$nama, $email, $password]);
            $id_user = $pdo->lastInsertId();
        }

        if ($id) db_jalankan('UPDATE dokter SET id_user = ?, id_poli = ?, nama_dokter = ?, spesialis = ? WHERE id_dokter = ?',
                             [$id_user, $id_poli, $nama, $spesialis, $id]);
        else     db_jalankan('INSERT INTO dokter (id_user, id_poli, nama_dokter, spesialis) VALUES (?, ?, ?, ?)',
                             [$id_user, $id_poli, $nama, $spesialis]);
        set_pesan('sukses', 'Data dokter disimpan.');
    }
    redirect('admin/dokter.php');
}


$edit = isset($_GET['edit'])
    ? db_satu('SELECT d.*, u.email FROM dokter d LEFT JOIN users u ON u.id_user = d.id_user WHERE d.id_dokter = ?', [(int)$_GET['edit']])
    : null;
$semua_poli = db_semua('SELECT * FROM poli ORDER BY nama_poli');
$rows = db_semua('SELECT d.*, p.nama_poli, u.email,
        (SELECT COUNT(*) FROM jadwal_praktik j WHERE j.id_dokter = d.id_dokter) AS jml_jadwal
    FROM dokter d JOIN poli p ON p.id_poli = d.id_poli LEFT JOIN users u ON u.id_user = d.id_user
    ORDER BY p.nama_poli, d.nama_dokter');

$judul = 'Data Dokter';
require __DIR__ . '/../includes/header.php';
?>
