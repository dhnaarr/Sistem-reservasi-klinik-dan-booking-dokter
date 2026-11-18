-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Nov 2026 pada 14.15
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_klinik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `asuransi`
--

CREATE TABLE `asuransi` (
  `id_asuransi` int(11) NOT NULL,
  `nama_asuransi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `asuransi`
--

INSERT INTO `asuransi` (`id_asuransi`, `nama_asuransi`) VALUES
(1, 'Asuransi Nusantara Sejahtera'),
(2, 'Asuransi Garuda Mandiri'),
(3, 'Asuransi Cendana Prima'),
(4, 'Asuransi Tirta Lindung'),
(5, 'Asuransi Bumi Raya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_poli` int(11) NOT NULL,
  `nama_dokter` varchar(100) NOT NULL,
  `spesialis` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dokter`
--

INSERT INTO `dokter` (`id_dokter`, `id_user`, `id_poli`, `nama_dokter`, `spesialis`) VALUES
(1, 2, 1, 'dr. Ayu Lestari', 'Dokter Umum'),
(2, 3, 1, 'dr. Bima Prakoso', 'Dokter Umum'),
(3, 4, 2, 'drg. Citra Maharani', 'Dokter Gigi'),
(4, 5, 3, 'dr. Dimas Wicaksono, Sp.A', 'Spesialis Anak'),
(5, 6, 4, 'dr. Eka Putri, Sp.OG', 'Spesialis Kandungan'),
(6, 7, 2, 'drg. Fajar Nugroho', 'Dokter Gigi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_praktik`
--

CREATE TABLE `jadwal_praktik` (
  `id_jadwal` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `sesi` enum('pagi','siang','sore') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `kuota` int(11) NOT NULL DEFAULT 20,
  `status` enum('aktif','cuti') NOT NULL DEFAULT 'aktif',
  `catatan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwal_praktik`
--

INSERT INTO `jadwal_praktik` (`id_jadwal`, `id_dokter`, `tanggal`, `sesi`, `jam_mulai`, `jam_selesai`, `kuota`, `status`, `catatan`) VALUES
(1, 1, '2026-11-18', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(2, 2, '2026-11-18', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(3, 3, '2026-11-18', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(4, 4, '2026-11-18', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(5, 5, '2026-11-18', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(6, 6, '2026-11-18', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(7, 1, '2026-11-19', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(8, 2, '2026-11-19', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(9, 3, '2026-11-19', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(10, 4, '2026-11-19', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(11, 5, '2026-11-19', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(12, 6, '2026-11-19', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(13, 1, '2026-11-20', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(14, 2, '2026-11-20', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(15, 3, '2026-11-20', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(16, 4, '2026-11-20', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(17, 5, '2026-11-20', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(18, 6, '2026-11-20', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(19, 1, '2026-11-21', 'pagi', '08:00:00', '12:00:00', 20, 'cuti', 'Dokter sedang cuti'),
(20, 2, '2026-11-21', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(21, 3, '2026-11-21', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(22, 4, '2026-11-21', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(23, 5, '2026-11-21', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(24, 6, '2026-11-21', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(25, 1, '2026-11-22', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(26, 2, '2026-11-22', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(27, 3, '2026-11-22', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(28, 4, '2026-11-22', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(29, 5, '2026-11-22', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(30, 6, '2026-11-22', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(31, 1, '2026-11-23', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(32, 2, '2026-11-23', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(33, 3, '2026-11-23', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(34, 4, '2026-11-23', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(35, 5, '2026-11-23', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(36, 6, '2026-11-23', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(37, 1, '2026-11-24', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(38, 2, '2026-11-24', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(39, 3, '2026-11-24', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(40, 4, '2026-11-24', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(41, 5, '2026-11-24', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(42, 6, '2026-11-24', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(43, 1, '2026-11-25', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(44, 2, '2026-11-25', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(45, 3, '2026-11-25', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(46, 4, '2026-11-25', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(47, 5, '2026-11-25', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(48, 6, '2026-11-25', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(49, 1, '2026-11-26', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(50, 2, '2026-11-26', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(51, 3, '2026-11-26', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(52, 4, '2026-11-26', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(53, 5, '2026-11-26', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(54, 6, '2026-11-26', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(55, 1, '2026-11-27', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(56, 2, '2026-11-27', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(57, 3, '2026-11-27', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(58, 4, '2026-11-27', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(59, 5, '2026-11-27', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(60, 6, '2026-11-27', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(61, 1, '2026-11-28', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(62, 2, '2026-11-28', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(63, 3, '2026-11-28', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(64, 4, '2026-11-28', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(65, 5, '2026-11-28', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(66, 6, '2026-11-28', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(67, 1, '2026-11-29', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(68, 2, '2026-11-29', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(69, 3, '2026-11-29', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(70, 4, '2026-11-29', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(71, 5, '2026-11-29', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(72, 6, '2026-11-29', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(73, 1, '2026-11-30', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(74, 2, '2026-11-30', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(75, 3, '2026-11-30', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(76, 4, '2026-11-30', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(77, 5, '2026-11-30', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(78, 6, '2026-11-30', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(79, 1, '2026-12-01', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(80, 2, '2026-12-01', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(81, 3, '2026-12-01', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL),
(82, 4, '2026-12-01', 'pagi', '08:00:00', '12:00:00', 20, 'aktif', NULL),
(83, 5, '2026-12-01', 'siang', '12:00:00', '15:00:00', 15, 'aktif', NULL),
(84, 6, '2026-12-01', 'sore', '15:00:00', '18:00:00', 15, 'aktif', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id_pasien` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id_pasien`, `id_user`, `nik`, `tgl_lahir`, `jenis_kelamin`, `no_hp`, `alamat`) VALUES
(1, 8, '3404010101000001', '2000-01-01', 'L', '081234567890', 'Yogyakarta'),
(3, 10, '9999999999999999', '0003-03-31', 'L', '9999999999', 'ffffff');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id_pendaftaran` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `jenis_penjamin` enum('umum','asuransi','bpjs') NOT NULL DEFAULT 'umum',
  `id_asuransi` int(11) DEFAULT NULL,
  `no_antrian` int(11) NOT NULL,
  `status` enum('aktif','sudah_registrasi','batal') NOT NULL DEFAULT 'aktif',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pendaftaran`
--

INSERT INTO `pendaftaran` (`id_pendaftaran`, `id_pasien`, `id_jadwal`, `jenis_penjamin`, `id_asuransi`, `no_antrian`, `status`, `created_at`) VALUES
(1, 3, 46, 'umum', NULL, 1, 'aktif', '2026-11-18 18:37:56');

-- --------------------------------------------------------

--
-- Struktur dari tabel `poli`
--

CREATE TABLE `poli` (
  `id_poli` int(11) NOT NULL,
  `nama_poli` varchar(100) NOT NULL,
  `kode_poli` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `poli`
--

INSERT INTO `poli` (`id_poli`, `nama_poli`, `kode_poli`) VALUES
(1, 'Poli Umum', 'U'),
(2, 'Poli Gigi', 'G'),
(3, 'Poli Anak', 'A'),
(4, 'Poli Kandungan', 'K');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id_rekam` int(11) NOT NULL,
  `id_pendaftaran` int(11) NOT NULL,
  `keluhan` text NOT NULL,
  `diagnosa` text NOT NULL,
  `tindakan` text DEFAULT NULL,
  `resep` text DEFAULT NULL,
  `tgl_periksa` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dokter','pasien') NOT NULL DEFAULT 'pasien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`) VALUES
(1, 'Administrator', 'admin@klinik.test', 'admin123', 'admin'),
(2, 'dr. Ayu Lestari', 'dokter1@klinik.test', 'dokter123', 'dokter'),
(3, 'dr. Bima Prakoso', 'dokter2@klinik.test', 'dokter123', 'dokter'),
(4, 'drg. Citra Maharani', 'dokter3@klinik.test', 'dokter123', 'dokter'),
(5, 'dr. Dimas Wicaksono, Sp.A', 'dokter4@klinik.test', 'dokter123', 'dokter'),
(6, 'dr. Eka Putri, Sp.OG', 'dokter5@klinik.test', 'dokter123', 'dokter'),
(7, 'drg. Fajar Nugroho', 'dokter6@klinik.test', 'dokter123', 'dokter'),
(8, 'Budi Santoso', 'pasien@klinik.test', 'pasien123', 'pasien'),
(10, 'DHANAR ROKHMAN FREDYANTO', 'dhnaarr@gmail.com', 'dhanar123', 'pasien');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `asuransi`
--
ALTER TABLE `asuransi`
  ADD PRIMARY KEY (`id_asuransi`);

--
-- Indeks untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dokter`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD KEY `id_poli` (`id_poli`);

--
-- Indeks untuk tabel `jadwal_praktik`
--
ALTER TABLE `jadwal_praktik`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD UNIQUE KEY `uq_jadwal` (`id_dokter`,`tanggal`,`sesi`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id_pasien`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indeks untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id_pendaftaran`),
  ADD UNIQUE KEY `uq_antrian` (`id_jadwal`,`no_antrian`),
  ADD KEY `id_pasien` (`id_pasien`),
  ADD KEY `id_asuransi` (`id_asuransi`);

--
-- Indeks untuk tabel `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id_poli`),
  ADD UNIQUE KEY `kode_poli` (`kode_poli`);

--
-- Indeks untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id_rekam`),
  ADD UNIQUE KEY `id_pendaftaran` (`id_pendaftaran`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `asuransi`
--
ALTER TABLE `asuransi`
  MODIFY `id_asuransi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jadwal_praktik`
--
ALTER TABLE `jadwal_praktik`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id_pasien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `poli`
--
ALTER TABLE `poli`
  MODIFY `id_poli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id_rekam` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `dokter_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL,
  ADD CONSTRAINT `dokter_ibfk_2` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id_poli`);

--
-- Ketidakleluasaan untuk tabel `jadwal_praktik`
--
ALTER TABLE `jadwal_praktik`
  ADD CONSTRAINT `jadwal_praktik_ibfk_1` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD CONSTRAINT `pasien_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_2` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_praktik` (`id_jadwal`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_3` FOREIGN KEY (`id_asuransi`) REFERENCES `asuransi` (`id_asuransi`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `rekam_medis_ibfk_1` FOREIGN KEY (`id_pendaftaran`) REFERENCES `pendaftaran` (`id_pendaftaran`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
