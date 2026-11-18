<?php
require_once __DIR__ . '/../includes/fungsi.php';
wajib_role('admin');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['hapus'])) {
            db_jalankan('DELETE FROM poli WHERE id_poli = ?', [(int)$_POST['hapus']]);
            set_pesan('sukses', 'Poli dihapus.');
        } else {
            $id   = (int)$_POST['id'];                      
            $nama = trim($_POST['nama_poli']);
            $kode = strtoupper(trim($_POST['kode_poli']));

            if ($nama === '' || !preg_match('/^[A-Z]$/', $kode)) {
                set_pesan('error', 'Nama wajib diisi dan kode berupa 1 huruf.');
            } else {
                if ($id) db_jalankan('UPDATE poli SET nama_poli = ?, kode_poli = ? WHERE id_poli = ?', [$nama, $kode, $id]);
                else     db_jalankan('INSERT INTO poli (nama_poli, kode_poli) VALUES (?, ?)', [$nama, $kode]);
                set_pesan('sukses', 'Data poli disimpan.');
            }
        }
    } catch (PDOException $e) {
        set_pesan('error', 'Gagal: kode poli sudah dipakai, atau poli masih dipakai oleh dokter.');
    }
    redirect('admin/poli.php');
}


$edit = isset($_GET['edit']) ? db_satu('SELECT * FROM poli WHERE id_poli = ?', [(int)$_GET['edit']]) : null;
$rows = db_semua('SELECT p.*, (SELECT COUNT(*) FROM dokter d WHERE d.id_poli = p.id_poli) AS jml_dokter FROM poli p ORDER BY nama_poli');

$judul = 'Data Poli';
require __DIR__ . '/../includes/header.php';
?>
