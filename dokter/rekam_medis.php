<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('dokter');

$dokter = data_dokter();
$id     = (int)($_GET['id'] ?? $_POST['id'] ?? 0);


if ($id) {
    
    $k = db_satu("SELECT p.*, u.nama AS nama_pasien, ps.id_pasien, ps.nik, ps.tgl_lahir, ps.jenis_kelamin, ps.no_hp, j.tanggal, j.jam_mulai
        FROM pendaftaran p
        JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
        JOIN pasien ps ON ps.id_pasien = p.id_pasien
        JOIN users u   ON u.id_user = ps.id_user
        WHERE p.id_pendaftaran = ? AND j.id_dokter = ? AND p.status <> 'batal'", [$id, $dokter['id_dokter']]);
    if (!$k) {
        set_pesan('error', 'Data kunjungan tidak ditemukan.');
        redirect('dokter/dashboard.php');
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $keluhan  = trim($_POST['keluhan']);
        $diagnosa = trim($_POST['diagnosa']);
        $tindakan = trim($_POST['tindakan']);
        $resep    = trim($_POST['resep']);

        if ($keluhan === '' || $diagnosa === '') {
            set_pesan('error', 'Keluhan dan diagnosa wajib diisi.');
        } else {
            
            if (db_satu('SELECT 1 FROM rekam_medis WHERE id_pendaftaran = ?', [$id])) {
                db_jalankan('UPDATE rekam_medis SET keluhan = ?, diagnosa = ?, tindakan = ?, resep = ? WHERE id_pendaftaran = ?',
                    [$keluhan, $diagnosa, $tindakan ?: null, $resep ?: null, $id]);
            } else {
                db_jalankan('INSERT INTO rekam_medis (id_pendaftaran, keluhan, diagnosa, tindakan, resep) VALUES (?, ?, ?, ?, ?)',
                    [$id, $keluhan, $diagnosa, $tindakan ?: null, $resep ?: null]);
            }
         
            db_jalankan("UPDATE pendaftaran SET status = 'sudah_registrasi' WHERE id_pendaftaran = ? AND status = 'aktif'", [$id]);
            set_pesan('sukses', 'Rekam medis disimpan.');
        }
        redirect('dokter/rekam_medis.php?id=' . $id);
    }


    $rm = db_satu('SELECT * FROM rekam_medis WHERE id_pendaftaran = ?', [$id])
          ?: ['keluhan' => '', 'diagnosa' => '', 'tindakan' => '', 'resep' => ''];

 
    $riwayat = db_semua("SELECT r.*, j.tanggal FROM rekam_medis r
        JOIN pendaftaran p ON p.id_pendaftaran = r.id_pendaftaran
        JOIN jadwal_praktik j ON j.id_jadwal = p.id_jadwal
        WHERE p.id_pasien = ? AND r.id_pendaftaran <> ?
        ORDER BY j.tanggal DESC LIMIT 10", [$k['id_pasien'], $id]);

    $judul = 'Rekam Medis';
    require __DIR__ . '/../includes/header.php';
?>
