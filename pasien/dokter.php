<?php

require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$cari = trim($_GET['q'] ?? '');
$poli = (int)($_GET['poli'] ?? 0);


$sql = "SELECT d.*, p.nama_poli,
        (SELECT COUNT(*) FROM jadwal_praktik j
         WHERE j.id_dokter = d.id_dokter AND j.tanggal >= CURDATE() AND j.status = 'aktif') AS jml_jadwal
        FROM dokter d JOIN poli p ON p.id_poli = d.id_poli
        WHERE (d.nama_dokter LIKE ? OR d.spesialis LIKE ? OR p.nama_poli LIKE ?)";
$param = ["%$cari%", "%$cari%", "%$cari%"];
if ($poli) {
    $sql .= ' AND d.id_poli = ?';
    $param[] = $poli;
}
$sql .= ' ORDER BY p.nama_poli, d.nama_dokter';

$dokters = db_semua($sql, $param);
$semua_poli = db_semua('SELECT * FROM poli ORDER BY nama_poli');

$judul = 'Pilih Dokter';
require __DIR__ . '/../includes/header.php';
?>
