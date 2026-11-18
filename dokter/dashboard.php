<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('dokter');

$dokter  = data_dokter();
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $hasil = db_jalankan("UPDATE pendaftaran p JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
        SET p.status = 'sudah_registrasi'
        WHERE p.id_pendaftaran = ? AND j.id_dokter = ? AND p.status = 'aktif'",
        [(int)$_POST['id'], $dokter['id_dokter']]);

    if ($hasil->rowCount()) set_pesan('sukses', 'Pasien ditandai sudah registrasi.');
    else                    set_pesan('error', 'Status tidak bisa diubah.');
    redirect('dokter/dashboard.php?tanggal=' . $tanggal);
}


$pasien = db_semua("SELECT p.*, u.nama AS nama_pasien, ps.tgl_lahir, ps.jenis_kelamin, j.sesi, j.jam_mulai,
        a.nama_asuransi,
        (SELECT COUNT(*) FROM rekam_medis r WHERE r.id_pendaftaran = p.id_pendaftaran) AS sudah_diperiksa
    FROM pendaftaran p
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN pasien ps ON ps.id_pasien = p.id_pasien
    JOIN users u   ON u.id_user = ps.id_user
    LEFT JOIN asuransi a ON a.id_asuransi = p.id_asuransi
    WHERE j.id_dokter = ? AND j.tanggal = ? AND p.status <> 'batal'
    ORDER BY j.jam_mulai, p.no_antrian", [$dokter['id_dokter'], $tanggal]);

$total   = count($pasien);
$selesai = count(array_filter($pasien, fn($p) => $p['sudah_diperiksa']));
$menunggu = count(array_filter($pasien, fn($p) => $p['status'] === 'sudah_registrasi' && !$p['sudah_diperiksa']));

$judul = 'Pasien Saya';
require __DIR__ . '/../includes/header.php';
?>
