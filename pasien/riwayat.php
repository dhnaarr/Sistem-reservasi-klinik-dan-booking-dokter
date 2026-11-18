<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$pasien = data_pasien();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $hasil = db_jalankan("UPDATE pendaftaran p JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
        SET p.status = 'batal'
        WHERE p.id_pendaftaran = ? AND p.id_pasien = ? AND p.status = 'aktif' AND j.tanggal >= CURDATE()",
        [(int)$_POST['id'], $pasien['id_pasien']]);

    if ($hasil->rowCount()) set_pesan('sukses', 'Booking dibatalkan.');
    else                    set_pesan('error', 'Booking tidak bisa dibatalkan.');
    redirect('pasien/riwayat.php');
}

$riwayat = db_semua("SELECT p.*, j.tanggal, j.jam_mulai, j.jam_selesai, d.nama_dokter, po.nama_poli, po.kode_poli
    FROM pendaftaran p
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN dokter d ON d.id_dokter = j.id_dokter
    JOIN poli po  ON po.id_poli = d.id_poli
    WHERE p.id_pasien = ?
    ORDER BY j.tanggal DESC, p.id_pendaftaran DESC", [$pasien['id_pasien']]);

$judul = 'Riwayat Booking';
require __DIR__ . '/../includes/header.php';
?>
