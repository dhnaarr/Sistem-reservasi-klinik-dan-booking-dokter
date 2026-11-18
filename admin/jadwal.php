<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


function jumlah_terisi($id_jadwal) {
    return db_satu("SELECT COUNT(*) AS n FROM pendaftaran WHERE id_jadwal = ? AND status <> 'batal'", [$id_jadwal])['n'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

 
    if (isset($_POST['hapus'])) {
        $id = (int)$_POST['hapus'];
        if (jumlah_terisi($id) > 0) set_pesan('error', 'Jadwal sudah punya booking. Ubah statusnya menjadi cuti, jangan dihapus.');
        else {
            db_jalankan('DELETE FROM jadwal_praktik WHERE id_jadwal = ?', [$id]);
            set_pesan('sukses', 'Jadwal dihapus.');
        }
        redirect('admin/jadwal.php');
    }

    
    $id        = (int)$_POST['id'];                         
    $id_dokter = (int)$_POST['id_dokter'];
    $tanggal   = $_POST['tanggal'];
    $sesi      = $_POST['sesi'];
    $mulai     = $_POST['jam_mulai'];
    $selesai   = $_POST['jam_selesai'];
    $kuota     = (int)$_POST['kuota'];
    $status    = $_POST['status'];
    $catatan   = trim($_POST['catatan']);
    if ($status === 'cuti' && $catatan === '') $catatan = 'Cuti';

    if (!$id_dokter || $tanggal === '' || !in_array($sesi, ['pagi', 'siang', 'sore']) || !in_array($status, ['aktif', 'cuti'])) {
        set_pesan('error', 'Dokter, tanggal, sesi, dan status wajib diisi dengan benar.');
    } elseif ($mulai === '' || $selesai === '' || $selesai <= $mulai) {
        set_pesan('error', 'Jam selesai harus setelah jam mulai.');
    } elseif ($kuota < 1 || $kuota > 200) {
        set_pesan('error', 'Kuota harus antara 1 sampai 200.');
    } elseif ($id && $kuota < jumlah_terisi($id)) {
        set_pesan('error', 'Kuota tidak boleh lebih kecil dari jumlah booking yang sudah ada.');
    } else {
        try {
            if ($id) {
                db_jalankan('UPDATE jadwal_praktik SET id_dokter=?, tanggal=?, sesi=?, jam_mulai=?, jam_selesai=?, kuota=?, status=?, catatan=? WHERE id_jadwal=?',
                    [$id_dokter, $tanggal, $sesi, $mulai, $selesai, $kuota, $status, $catatan ?: null, $id]);
            } else {
                db_jalankan('INSERT INTO jadwal_praktik (id_dokter, tanggal, sesi, jam_mulai, jam_selesai, kuota, status, catatan) VALUES (?,?,?,?,?,?,?,?)',
                    [$id_dokter, $tanggal, $sesi, $mulai, $selesai, $kuota, $status, $catatan ?: null]);
            }
            set_pesan('sukses', 'Jadwal disimpan.');
        } catch (PDOException $e) {
            set_pesan('error', 'Jadwal dokter pada tanggal dan sesi tersebut sudah ada.');
        }
    }
    redirect('admin/jadwal.php');
}


$edit = isset($_GET['edit']) ? db_satu('SELECT * FROM jadwal_praktik WHERE id_jadwal = ?', [(int)$_GET['edit']]) : null;
$semua_dokter = db_semua('SELECT d.id_dokter, d.nama_dokter, p.nama_poli FROM dokter d JOIN poli p ON p.id_poli = d.id_poli ORDER BY d.nama_dokter');


$filter = (int)($_GET['dokter'] ?? 0);
$sql = "SELECT j.*, d.nama_dokter, p.nama_poli,
        (SELECT COUNT(*) FROM pendaftaran x WHERE x.id_jadwal = j.id_jadwal AND x.status <> 'batal') AS terisi
    FROM jadwal_praktik j
    JOIN dokter d ON d.id_dokter = j.id_dokter
    JOIN poli p ON p.id_poli = d.id_poli
    WHERE j.tanggal >= CURDATE()";
$param = [];
if ($filter) { $sql .= ' AND j.id_dokter = ?'; $param[] = $filter; }
$sql .= ' ORDER BY j.tanggal, j.jam_mulai LIMIT 100';
$rows = db_semua($sql, $param);


$f = $edit ?: ['id_jadwal' => 0, 'id_dokter' => 0, 'tanggal' => '', 'sesi' => 'pagi', 'jam_mulai' => '08:00', 'jam_selesai' => '12:00',
               'kuota' => 20, 'status' => 'aktif', 'catatan' => ''];

$judul = 'Jadwal Praktik';
require __DIR__ . '/../includes/header.php';
?>
