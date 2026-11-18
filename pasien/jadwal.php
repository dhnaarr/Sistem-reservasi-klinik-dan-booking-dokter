<?php

require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$id_dokter = (int)($_GET['dokter'] ?? 0);
$dokter = db_satu('SELECT d.*, p.nama_poli FROM dokter d JOIN poli p ON p.id_poli = d.id_poli WHERE d.id_dokter = ?', [$id_dokter]);
if (!$dokter) redirect('pasien/dokter.php');


$jadwal = db_semua("SELECT j.*,
        j.kuota - (SELECT COUNT(*) FROM pendaftaran x WHERE x.id_jadwal = j.id_jadwal AND x.status <> 'batal') AS sisa
    FROM jadwal_praktik j
    WHERE j.id_dokter = ? AND j.tanggal >= CURDATE()
    ORDER BY j.tanggal, j.jam_mulai", [$id_dokter]);

$judul = 'Pilih Tanggal';
require __DIR__ . '/../includes/header.php';
?>
