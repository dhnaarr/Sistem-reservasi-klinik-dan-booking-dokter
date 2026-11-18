<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('pasien');

$pasien = data_pasien();


$r = db_satu("SELECT p.*, j.tanggal, j.sesi, j.jam_mulai, j.jam_selesai,
        d.nama_dokter, d.spesialis, po.nama_poli, po.kode_poli, a.nama_asuransi
    FROM pendaftaran p
    JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
    JOIN dokter d ON d.id_dokter = j.id_dokter
    JOIN poli po  ON po.id_poli = d.id_poli
    LEFT JOIN asuransi a ON a.id_asuransi = p.id_asuransi
    WHERE p.id_pendaftaran = ? AND p.id_pasien = ?", [(int)($_GET['id'] ?? 0), $pasien['id_pasien']]);

if (!$r) {
    set_pesan('error', 'Bukti booking tidak ditemukan.');
    redirect('pasien/riwayat.php');
}

$penjamin = ['umum' => 'Umum', 'bpjs' => 'BPJS Kesehatan', 'asuransi' => 'Asuransi - ' . $r['nama_asuransi']][$r['jenis_penjamin']];
[$status_teks] = label_status($r['status']);

$judul = 'Bukti Booking';
require __DIR__ . '/../includes/header.php';
?>
