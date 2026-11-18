<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


$jml_pasien  = db_satu('SELECT COUNT(*) AS n FROM pasien')['n'];
$jml_dokter  = db_satu('SELECT COUNT(*) AS n FROM dokter')['n'];
$jml_jadwal  = db_satu("SELECT COUNT(*) AS n FROM jadwal_praktik WHERE tanggal >= CURDATE() AND status = 'aktif'")['n'];
$jml_hari_ini = db_satu("SELECT COUNT(*) AS n FROM pendaftaran p JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
                         WHERE j.tanggal = CURDATE() AND p.status <> 'batal'")['n'];


$terbaru = db_semua("SELECT p.id_pendaftaran, p.status, p.no_antrian, u.nama AS pasien, d.nama_dokter, j.tanggal, po.kode_poli
    FROM pendaftaran p
    JOIN pasien ps ON ps.id_pasien = p.id_pasien
    JOIN users u   ON u.id_user = ps.id_user
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN dokter d  ON d.id_dokter = j.id_dokter
    JOIN poli po   ON po.id_poli = d.id_poli
    ORDER BY p.id_pendaftaran DESC LIMIT 8");

$judul = 'Dashboard Admin';
require __DIR__ . '/../includes/header.php';
?>
