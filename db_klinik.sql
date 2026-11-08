-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Nov 2026 pada 07.38
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `asuransi`
--

INSERT INTO `asuransi` (`id_asuransi`, `nama_asuransi`) VALUES
(1, 'Asuransi Sehat Sentosa'),
(2, 'Asuransi Medika Nusantara'),
(3, 'Asuransi Lindung Prima'),
(4, 'Asuransi Cipta Husada'),
(5, 'Asuransi Bina Waras');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL,
  `id_poli` int(11) NOT NULL,
  `nama_dokter` varchar(100) NOT NULL,
  `spesialis` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokter`
--

INSERT INTO `dokter` (`id_dokter`, `id_poli`, `nama_dokter`, `spesialis`, `foto`) VALUES
(1, 1, 'dr. Andi Prasetyo', 'Dokter Umum', NULL),
(2, 2, 'dr. Sinta Wulandari, Sp.A', 'Spesialis Anak', NULL),
(3, 2, 'dr. Bagas Mahendra, Sp.A', 'Spesialis Anak', NULL),
(4, 3, 'dr. Rina Kusuma, Sp.PD', 'Spesialis Penyakit Dalam', NULL),
(5, 4, 'drg. Maya Anggraini', 'Dokter Gigi', NULL),
(6, 1, 'dr. Dewi Lestari', 'Dokter Umum', NULL);

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
  `kuota` int(11) NOT NULL DEFAULT 10,
  `status` enum('aktif','cuti') NOT NULL DEFAULT 'aktif',
  `catatan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal_praktik`
--

INSERT INTO `jadwal_praktik` (`id_jadwal`, `id_dokter`, `tanggal`, `sesi`, `jam_mulai`, `jam_selesai`, `kuota`, `status`, `catatan`) VALUES
(1, 1, '2026-11-09', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(2, 2, '2026-11-09', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(3, 4, '2026-11-09', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(4, 6, '2026-11-09', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(5, 1, '2026-11-10', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(6, 3, '2026-11-10', 'sore', '16:00:00', '17:00:00', 10, 'aktif', NULL),
(7, 4, '2026-11-10', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(8, 5, '2026-11-10', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(9, 1, '2026-11-11', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(10, 2, '2026-11-11', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(11, 4, '2026-11-11', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(12, 6, '2026-11-11', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(13, 1, '2026-11-12', 'pagi', '09:00:00', '10:00:00', 10, 'cuti', 'Cuti'),
(14, 3, '2026-11-12', 'sore', '16:00:00', '17:00:00', 10, 'aktif', NULL),
(15, 4, '2026-11-12', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(16, 5, '2026-11-12', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(17, 1, '2026-11-13', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(18, 2, '2026-11-13', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(19, 4, '2026-11-13', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(20, 5, '2026-11-13', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(21, 6, '2026-11-13', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(22, 1, '2026-11-16', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(23, 2, '2026-11-16', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(24, 4, '2026-11-16', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(25, 6, '2026-11-16', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(26, 1, '2026-11-17', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(27, 3, '2026-11-17', 'sore', '16:00:00', '17:00:00', 10, 'aktif', NULL),
(28, 4, '2026-11-17', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(29, 5, '2026-11-17', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(30, 1, '2026-11-18', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(31, 2, '2026-11-18', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(32, 4, '2026-11-18', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(33, 6, '2026-11-18', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(34, 1, '2026-11-19', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(35, 3, '2026-11-19', 'sore', '16:00:00', '17:00:00', 10, 'aktif', NULL),
(36, 4, '2026-11-19', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(37, 5, '2026-11-19', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(38, 1, '2026-11-20', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(39, 2, '2026-11-20', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(40, 4, '2026-11-20', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(41, 5, '2026-11-20', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(42, 6, '2026-11-20', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(43, 1, '2026-11-23', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(44, 2, '2026-11-23', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(45, 4, '2026-11-23', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(46, 6, '2026-11-23', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(47, 1, '2026-11-24', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(48, 3, '2026-11-24', 'sore', '16:00:00', '17:00:00', 10, 'aktif', NULL),
(49, 4, '2026-11-24', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(50, 5, '2026-11-24', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(51, 1, '2026-11-25', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(52, 2, '2026-11-25', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(53, 4, '2026-11-25', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(54, 6, '2026-11-25', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(55, 1, '2026-11-26', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(56, 3, '2026-11-26', 'sore', '16:00:00', '17:00:00', 10, 'aktif', NULL),
(57, 4, '2026-11-26', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(58, 5, '2026-11-26', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(59, 1, '2026-11-27', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(60, 2, '2026-11-27', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(61, 4, '2026-11-27', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(62, 5, '2026-11-27', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(63, 6, '2026-11-27', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(64, 1, '2026-11-30', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(65, 2, '2026-11-30', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(66, 4, '2026-11-30', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(67, 6, '2026-11-30', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(68, 1, '2026-12-01', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(69, 3, '2026-12-01', 'sore', '16:00:00', '17:00:00', 10, 'aktif', NULL),
(70, 4, '2026-12-01', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(71, 5, '2026-12-01', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(72, 1, '2026-12-02', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(73, 2, '2026-12-02', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(74, 4, '2026-12-02', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(75, 6, '2026-12-02', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL),
(76, 1, '2026-12-03', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(77, 3, '2026-12-03', 'sore', '16:00:00', '17:00:00', 10, 'aktif', NULL),
(78, 4, '2026-12-03', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(79, 5, '2026-12-03', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(80, 1, '2026-12-04', 'pagi', '09:00:00', '10:00:00', 10, 'aktif', NULL),
(81, 2, '2026-12-04', 'siang', '13:00:00', '14:00:00', 8, 'aktif', NULL),
(82, 4, '2026-12-04', 'pagi', '08:00:00', '09:00:00', 12, 'aktif', NULL),
(83, 5, '2026-12-04', 'siang', '13:00:00', '15:00:00', 8, 'aktif', NULL),
(84, 6, '2026-12-04', 'sore', '15:00:00', '16:00:00', 10, 'aktif', NULL);

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
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_bpjs` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id_pendaftaran` int(11) NOT NULL,
  `kode_booking` varchar(10) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `jenis_penjamin` enum('umum','asuransi','bpjs') NOT NULL DEFAULT 'umum',
  `id_asuransi` int(11) DEFAULT NULL,
  `no_antrian` int(11) NOT NULL,
  `status` enum('aktif','sudah_registrasi','batal') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

CREATE TABLE `pesan` (
  `id_pesan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `kategori` enum('info','layanan_poli','promo') NOT NULL DEFAULT 'info',
  `judul` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `poli`
--

CREATE TABLE `poli` (
  `id_poli` int(11) NOT NULL,
  `nama_poli` varchar(100) NOT NULL,
  `kode_poli` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `poli`
--

INSERT INTO `poli` (`id_poli`, `nama_poli`, `kode_poli`) VALUES
(1, 'Klinik Umum', 'U'),
(2, 'Klinik Anak', 'A'),
(3, 'Klinik Penyakit Dalam', 'D'),
(4, 'Klinik Gigi', 'G');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pasien') NOT NULL DEFAULT 'pasien',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `fk_dokter_poli` (`id_poli`);

--
-- Indeks untuk tabel `jadwal_praktik`
--
ALTER TABLE `jadwal_praktik`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD UNIQUE KEY `uq_dokter_tanggal_sesi` (`id_dokter`,`tanggal`,`sesi`);

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
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD UNIQUE KEY `uq_pasien_jadwal` (`id_pasien`,`id_jadwal`),
  ADD KEY `fk_daftar_jadwal` (`id_jadwal`),
  ADD KEY `fk_daftar_asuransi` (`id_asuransi`);

--
-- Indeks untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id_pesan`),
  ADD KEY `fk_pesan_user` (`id_user`);

--
-- Indeks untuk tabel `poli`
--
ALTER TABLE `poli`
  ADD PRIMARY KEY (`id_poli`),
  ADD UNIQUE KEY `kode_poli` (`kode_poli`);

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
  MODIFY `id_pasien` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_pendaftaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `poli`
--
ALTER TABLE `poli`
  MODIFY `id_poli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD CONSTRAINT `fk_dokter_poli` FOREIGN KEY (`id_poli`) REFERENCES `poli` (`id_poli`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_praktik`
--
ALTER TABLE `jadwal_praktik`
  ADD CONSTRAINT `fk_jadwal_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD CONSTRAINT `fk_pasien_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `fk_daftar_asuransi` FOREIGN KEY (`id_asuransi`) REFERENCES `asuransi` (`id_asuransi`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_daftar_jadwal` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal_praktik` (`id_jadwal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_daftar_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD CONSTRAINT `fk_pesan_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
