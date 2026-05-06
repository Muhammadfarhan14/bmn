-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Bulan Mei 2026 pada 19.24
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
-- Database: `inventaris`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `kondisi` varchar(50) DEFAULT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id`, `nama_barang`, `jumlah`, `lokasi`, `kondisi`, `kode_barang`, `status`, `kategori`) VALUES
(7, 'Amplop Merpati 90-80Gr (Kode Lama)								', 59, 'Gudang', 'baik', '1010205999', 'Tersedia', 'ATK'),
(8, 'Max Staples 10-1M (Kode Lama)								', 9, 'Gudang', 'baik', '1010205999	', 'Tersedia', 'ATK'),
(9, 'Gunting Kecil (Kode Lama)								', 10, 'Gudang', 'baik', '1010205999	', 'Tersedia', 'ATK'),
(10, 'Lampu Philips (Kode Lama)								', 50, 'Gudang', 'baik', '1010205999	', 'Tersedia', 'ATK'),
(11, 'Lion Star Dust Bin 15Liter No.C-3 (Kode Lama)								', 2, 'Gudang', 'baik', '1010205999	', 'Tersedia', 'Peralatan'),
(12, 'Kenko Gunting SC-848N (Kode Lama)								', 10, 'Gudang', 'baik', '1010205999	', 'Tersedia', 'ATK'),
(13, 'Pulpen Faster								', 20, 'Gudang', 'baik', '1010205999	', 'Tersedia', 'ATK'),
(14, 'Spidol								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(15, 'Bolpoint								', 35, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(16, 'Snowman Spidol W.Board BG-12Black								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(17, 'Pulpen Tecnogrid								', 48, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(18, 'Materai Rp. 6000								', 12, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(19, 'Pen Dong-G								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(20, 'Pen Sign No Uniball								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(21, 'Pulpen Balliner Hijau								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(22, 'Materai 3000								', 12, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(23, 'Pulpen Pilot								', 36, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(24, 'Pulpen Balliner Hitam								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(25, 'Spidol Snowman Permanent								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(26, 'Pulpen Standard								', 36, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(27, 'Rautan Pensil								', 12, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(28, 'Stabilo								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(29, 'Pulpen Meja								', 12, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(30, 'Pulpen Kenko								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(31, 'E-Materai								', 12, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(32, 'Stabilo (Deli No. S621 - Blue)								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(33, 'Stabilo (Deli No. S621 - Yellow)								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(34, 'Stabilo (Deli No. S621 - Orange)								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(35, 'Pilot Pen Ball Liner BL-5M Black								', 36, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(36, 'Pilot Pen Ball Liner BL-5 Green								', 36, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(37, 'Standard Pen B Gel 0.5 Black -1728								', 36, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(38, 'Standard Pen AE-7 Tecno 0.38-2880								', 36, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(39, 'Standard Pen B Gel 0.5 Black--1728								', 36, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(40, 'Standard Pen AE-7 Tecno 0.38--2880								', 36, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(41, 'Deli Accent Highlighter No.S621 Yellow								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(42, 'Snowman Spidol Permanent G-12--720								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(43, 'Snowman Spidol W Board BG-12--720								', 24, 'Gudang', 'baik', '1010301001	', 'Tersedia', 'ATK'),
(44, 'Isi Tinta								', 5, 'Gudang', 'baik', '1010301002	', 'Tersedia', 'ATK'),
(45, 'Bak Stempel								', 9, 'Gudang', 'baik', '1010301002	', 'Tersedia', 'ATK'),
(46, 'Tinta Cap Keimigrasian Biru (Kode Baru)								', 10, 'Gudang', 'baik', '1010301002	', 'Tersedia', 'ATK'),
(47, 'Tinta Cap Keimigrasian Hijau (Kode Baru)								', 10, 'Gudang', 'baik', '1010301002	', 'Tersedia', 'ATK'),
(48, 'Tinta Cap Keimigrasian Merah (Kode Baru)								', 10, 'Gudang', 'baik', '1010301002	', 'Tersedia', 'ATK'),
(49, 'Isi Tinta Stamp Ink								', 12, 'Gudang', 'baik', '1010301002	', 'Tersedia', 'ATK'),
(50, 'Tinta Epson 003 Black								', 10, 'Gudang', 'baik', '1010301002	', 'Tersedia', 'ATK'),
(51, 'Deli Stick Up Sticky Notes 76x51 (656) A00253								', 15, 'Gudang', 'baik', '1010301002	', 'Tersedia', 'ATK'),
(52, 'Triagonal Clip No. 3								', 12, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(53, 'Binder Clip No.105								', 12, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(54, 'Binder Clip No. 155-240								', 12, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(55, 'Binder Clip No. 260								', 12, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(56, 'Big Triagonal Clips No. 3 PC-1030--288								', 12, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(57, 'EDS-ON Binder Clip No. 105--360								', 40, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(58, 'Linko Binder Clip No. 107								', 12, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(59, 'Big Trigonal Clips No. 1 PC-01-500								', 30, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(60, 'Deli Binder Clip+Tumb Tacks Set 78551								', 20, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(61, 'EDS-ON Binder Clip No.260--60								', 12, 'Gudang', 'baik', '1010301003	', 'Tersedia', 'ATK'),
(62, 'Retype Correction Set No. 9200								', 10, 'Gudang', 'baik', '1010301004	', 'Tersedia', 'ATK'),
(63, 'Correction Pen								', 10, 'Gudang', 'baik', '1010301004	', 'Tersedia', 'ATK'),
(64, 'Tinta Epson (008 Magenta)								', 8, 'Gudang', 'baik', '1010301004	', 'Tersedia', 'ATK'),
(65, 'Guangbo Corr Tape 5mmx8m HWH05017-1								', 10, 'Gudang', 'baik', '1010301004	', 'Tersedia', 'ATK'),
(66, 'Kenko Corr.Pen Ke-108--432								', 10, 'Gudang', 'baik', '1010301004	', 'Tersedia', 'ATK'),
(67, 'Nachi Cellotape 1/2 x25y--960								', 10, 'Gudang', 'baik', '1010301004	', 'Tersedia', 'ATK'),
(68, 'Nachi Double Tape 24mm (1) x 10Y--80								', 10, 'Gudang', 'baik', '1010301004	', 'Tersedia', 'ATK'),
(69, 'Nachi Double Tape 12mm (1/2) x 10--160								', 10, 'Gudang', 'baik', '1010301004	', 'Tersedia', 'ATK'),
(70, 'Buku Besar								', 6, 'Gudang', 'baik', '1010301005	', 'Tersedia', 'ATK'),
(71, 'Okey Buku Ekspedisi 100 624137 xx - 120								', 5, 'Gudang', 'baik', '1010301005	', 'Tersedia', 'ATK'),
(72, 'Buku Agenda Surat								', 6, 'Gudang', 'baik', '1010301005	', 'Tersedia', 'ATK'),
(73, 'Buku Catalog A4								', 6, 'Gudang', 'baik', '1010301005	', 'Tersedia', 'ATK'),
(74, 'Buku Tamu								', 6, 'Gudang', 'baik', '1010301005	', 'Tersedia', 'ATK'),
(75, 'Kiky Buku Expedisi 100 623701-80								', 6, 'Gudang', 'baik', '1010301005	', 'Tersedia', 'ATK'),
(76, 'Kiky Buku Folio 50								', 6, 'Gudang', 'baik', '1010301005	', 'Tersedia', 'ATK'),
(79, 'sfsd', 12, 'Gudang', 'baik', '123123', 'Tersedia', 'Elektronik'),
(80, 'kopi', 8, 'Gudang', 'baik', '21313', 'Tersedia', 'Persediaan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `catatan_user`
--

CREATE TABLE `catatan_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `isi_catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('baru','dibaca') DEFAULT 'baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `catatan_user`
--

INSERT INTO `catatan_user` (`id`, `user_id`, `isi_catatan`, `created_at`, `status`) VALUES
(5, 10, 'kopi', '2026-05-06 16:53:43', 'dibaca');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `barang_id` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `user_id`, `barang_id`, `jumlah`, `status`, `tanggal`, `created_at`, `catatan`) VALUES
(12, 7, 7, 12, 'disetujui', '2026-05-06 15:17:49', '2026-05-06 15:17:49', 'sda'),
(13, 2, 45, 1, 'disetujui', '2026-05-06 16:39:52', '2026-05-06 16:39:52', '-'),
(14, 10, 11, 2, 'disetujui', '2026-05-06 16:52:43', '2026-05-06 16:52:43', ' '),
(15, 10, 80, 12, 'disetujui', '2026-05-06 16:53:13', '2026-05-06 16:53:13', ' '),
(16, 10, 79, 2, 'ditolak', '2026-05-06 17:04:04', '2026-05-06 17:04:04', ' '),
(17, 10, 80, 1, 'ditolak', '2026-05-06 17:04:16', '2026-05-06 17:04:16', ' '),
(18, 11, 56, 3, 'ditolak', '2026-05-06 17:05:08', '2026-05-06 17:05:08', ' '),
(19, 10, 54, 1, 'ditolak', '2026-05-06 17:12:49', '2026-05-06 17:12:49', ' '),
(20, 11, 59, 12, 'ditolak', '2026-05-06 17:13:27', '2026-05-06 17:13:27', ' '),
(21, 10, 55, 3, 'ditolak', '2026-05-06 17:15:11', '2026-05-06 17:15:11', 'a'),
(22, 14, 51, 12, 'ditolak', '2026-05-06 17:15:43', '2026-05-06 17:15:43', ' ');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `ruangan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `ruangan`) VALUES
(1, 'Admin', 'admin', '123', 'admin', NULL),
(10, 'Tikkim', 'Tikkim', '123', 'user', 'Tikkim'),
(11, 'Inteldakim', 'Inteldakim', '123', 'user', 'Inteldakim'),
(12, 'Tata Usaha', 'Tata Usaha', '123', 'user', 'Tata Usaha'),
(13, 'Intaltuskim', 'Intaltuskim', '123', 'user', 'Intaltuskim'),
(14, 'Lantaskim', 'Lantaskim', '123', 'user', 'Lantaskim');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `catatan_user`
--
ALTER TABLE `catatan_user`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT untuk tabel `catatan_user`
--
ALTER TABLE `catatan_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
