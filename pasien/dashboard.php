<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$pasien = data_pasien();


$aktif = db_semua("SELECT p.*, j.tanggal, j.jam_mulai, j.jam_selesai, d.nama_dokter, po.nama_poli, po.kode_poli
    FROM pendaftaran p
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN dokter d  ON d.id_dokter = j.id_dokter
    JOIN poli po   ON po.id_poli = d.id_poli
    WHERE p.id_pasien = ? AND p.status = 'aktif' AND j.tanggal >= CURDATE()
    ORDER BY j.tanggal, j.jam_mulai LIMIT 6", [$pasien['id_pasien']]);

$judul = 'Beranda';
require __DIR__ . '/../includes/header.php';
?>
