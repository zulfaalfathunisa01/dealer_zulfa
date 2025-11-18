-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 18, 2025 at 08:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dealer_zulfa`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `email` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `email`, `password`) VALUES
(15, 'admin@zulforce.com', '$2y$10$JGWBmaj7smlUKrcbTq0ZduvUzKx1IWH/Frm0TpriyQK83Yy21yxoC');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `id_produk` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_pengguna`, `id_produk`, `qty`, `tanggal`) VALUES
(70, 26, 47, 1, '2025-11-13 09:07:16'),
(71, 26, 36, 1, '2025-11-13 09:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `merk`
--

CREATE TABLE `merk` (
  `id_merk` int NOT NULL,
  `nama_merk` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merk`
--

INSERT INTO `merk` (`id_merk`, `nama_merk`) VALUES
(3, 'Suzuki'),
(17, 'Yamaha'),
(18, 'Honda'),
(20, 'Vespa'),
(21, 'Victory Motorcycles');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL,
  `nama_pengguna` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `email`, `password`, `no_hp`, `alamat`, `foto`) VALUES
(24, 'Zulfa Alfathunisa', 'zulfa01@gmail.com', '$2y$10$QN3nS.MrchSjd0xcSzF/5..sKi00MT86mUIfXGAHNrEPD3wfLPZ6W', '083890234555', 'Griya Dharma Pratama, Jl.Surya Kencana No.12 Kelurahan Citra Mandala, Kec.Wijaya Laksana, Kab.Purnama agung, Nusantara', '1763273932_naii.jpg'),
(26, 'Alam Pamungkas', 'Alam@gmail.com', '$2y$10$UTcPiQvg68lD07ghI/C0seAwsRXnu3BSdEVPLIsqs/m91OQ08iBa.', '082183029347', 'Lingkar Mandala Pertiwi, Jl. Jaya Utama No. 03, Desa Suka Dharma, Kecamatan Marga Loka, Kabupaten Surya Kencana', '1763027334_lucu.jpg'),
(27, 'Harugo Nubagja', 'ugo@gmail.com', '$2y$10$tZL60IwfV7xG32AL8uDsI.4bPh2i9nhDGUYP3DxMl6vZi3oLY28mq', '082129013465', 'Wisma Nirma Cakrawala, Jl. Angkasa Pustaka No.02, Desa. Kusuma Jaya, Kec.Pradana Cendekia Kota Dharma Pertiwi', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `merk_id` int NOT NULL,
  `nama_produk` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `harga` double NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stock` int NOT NULL,
  `kategori` enum('classic','matic') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `photo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `merk_id`, `nama_produk`, `harga`, `deskripsi`, `stock`, `kategori`, `photo`) VALUES
(36, 18, 'Yamaha Vinoora (Scooter Retro)', 32000000, 'Deskripsi: Skuter bergaya retro klasik ala Eropa dengan sentuhan modern. Lampu bulat kembar yang khas,jok kulit two-tone, dan desain unik menjadikannya motor lifestyle yang stylish untuk perkotaan.\n\nSpesifikasi: Mesin 124 cc, 4-stroke, SOHC, pendingin udara\r\nTenaga ± 8,3 PS\r\nTorsi ± 9,9 Nm\r\nTransmisi otomatis (CVT)\r\nRem depan cakram, belakang tromol\r\nBerat ± 94–98 kg\r\nKapasitas tangki ± 4,9 L', 0, 'matic', 'uploads/1759311715_vinoo.jpg'),
(38, 18, 'Yamaha Fazzio Hybrid-Connected', 22000000, 'Deskripsi: Skuter matik retro-modern dengan fitur hybrid, bisa terhubung kemartphone (Y-Connect), cocok untuk gaya hidup anak muda\n\nSpesifikasi: Mesin 125 cc, SOHC, FI, Hybrid Assist\r\nTenaga ± 8,4 PS @ 6.500 rpm\r\nTorsi ± 10,6 Nm @ 4.500 rpm\r\nBerat ± 95 kg, tangki 5,1 L\r\nBan 12 inci tubeless, rem depan cakram', 4, 'matic', 'uploads/1759310277_stylow.jpg'),
(40, 17, 'Yamaha DragStar 650 Classic – Maroon', 115000000, 'Deskripsi: Varian warna maroon dengan aksen krom yang kuat.Tampil lebih mewah dan maskulin, cocok untuk pengendara yang ingin tampil klasik namun tetap premium.\n\nSpesifikasi: Mesin 649 cc V-Twin, 4-tak\r\nPendingin udara, 5 percepatan\r\nRem cakram depan & tromol belakang\r\nSuspensi ganda belakang\r\nTangki 16 L', 14, 'classic', 'uploads/1759816992_classic1.jpg'),
(42, 17, 'Yamaha XVS 100 V-Star Classic ', 235000000, 'Deskripsi: Motor ini bergenre cruiser klasik desain rendah, jok mendekati tanah, stang agak lebar, dan gaya retro-krom yang kental. Cocok untuk pengendara yang ingin motor gaya santai, nyaman untuk jalan santai,bukan untuk kecepatan tinggi agresif.\n\nSpesifikasi: V-twin, pendingin udara (air-cooled)  \r\nKapasitas: ± 649 cc untuk varian “Classic” yang umum.  \r\nTenaga: ~ 40 hp @ 6.500 rpm  \r\nTorsi: ~ 51 Nm @ 3.000 rpm  \r\nKeluaran Tahun: 2000an \r\nDi Produksi Oleh : Yamaha Jepang', 5, 'classic', 'uploads/1762664906_Yamaha XVS 100 V-Star Classic.jpg'),
(43, 20, 'Vespa Primavera 150 i-get', 53900000, 'Deskripsi: Vespa Primavera 150 i-get adalah skuter retro modern dengan desain klasik khas Italia yang elegan. Dilengkapi teknologi mesin i-get 150cc yang irit bahan bakar, getaran halus, dan respons cepat. Cocok untuk penggunaan harian dengan tampilan stylish dan nyaman dikendarai di jalan perkotaan.\n\nSpesifikasi: Mesin 155 cc i-get, \r\n4-tak Pendingin udara, \r\ntransmisi otomatis (CVT) Rem cakram depan & tromol belakang     Suspensi ganda belakang Tangki 8 L', 9, 'matic', 'uploads/1762670824_vespa.jpg'),
(45, 20, 'Vespa Primavera 150 i-get ABS (2024)', 55700000, 'Deskripsi: Skuter modern bergaya retro dengan performa halus dan desain ikonik khas Vespa. Primavera  hadir dengan mesin i-get yang efisien dan sistem rem ABS untuk keamanan maksimal di jalan perkotaan.\n\nSpesifikasi: Mesin 154,8 cc i-get, 4-tak \r\nPendingin udara, injeksi bahan bakar\r\nTransmisi otomatis (CVT)\r\nRem cakram depan ABS & tromol belakang \r\nSuspensi depan single arm, belakang per keong tunggal \r\nTangki bahan bakar 7 L \r\nBerat 129 kg \r\nAsal produksi: Italia  \r\nTahun produksi: 2024', 9, 'matic', 'uploads/1762855549_vesmet1.jpg'),
(46, 21, 'Victory 1700 Gunner 2016', 380000000, 'Deskripsi: Victory Gunner adalah motor cruiser bergaya klasik dengan performa Tangguh dan desain minimalis. Ditenagai mesin V-Twin besar 1731 cc,motor ini menawarkan torsi besar dengan suara khas dan posisi berkendara yang nyaman untuk jarak jauh.\n\nSpesifikasi: Mesin 1731 cc V-Twin 4-tak SOHC\r\nPendingin udara\r\nTransmisi 6 percepatan\r\nSistem bahan bakar injeksi elektronik\r\nRem cakram depan & belakang\r\nSuspensi depan teleskopik, belakang ganda\r\nTangki bahan bakar 17 liter\r\nBerat sekitar 296 kg\r\nAsal produksi: Amerika Serikat 🇺🇸\r\nTahun produksi: 2016', 9, 'classic', 'uploads/1762856461_classicc.jpg'),
(47, 21, 'Victory Gunner 2016', 330000000, 'Deskripsi: Motor cruiser bergaya bobber klasik buatan Victory Motorcycles, dengan \r\ndesain gagah dan mesin bertenaga besar.\n\nSpesifikasi: Mesin 1731 cc V-Twin, 4-tak\r\nPendingin udara\r\nTransmisi 6 percepatan\r\nRem cakram depan & belakang\r\nTangki 17 L\r\nAsal produksi: Amerika Serikat\r\nTahun produksi: 2016', 5, 'classic', 'uploads/1762857799_clasic2.jpg'),
(49, 17, 'Yamaha Vinoora', 29000000, 'Deskripsi: Desain unik bergaya retro modern dengan performa halus dan efisien, cocok untuk penggunaan harian di kota.\n\nSpesifikasi: Mesin 125 cc, 4-tak, Fuel Injection\r\nPendingin udara\r\nTransmisi otomatis (CVT)\r\nRem cakram depan & tromol belakang\r\nTangki bahan bakar 4,4 L\r\nTahun produksi: 2020\r\nAsal produksi: Taiwan', 4, 'matic', 'uploads/1763023629_vinora.png');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `pengguna_id` int NOT NULL,
  `admin_id` int DEFAULT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `total_harga` double NOT NULL,
  `status` enum('proses','batal','kirim','selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `catatan_batal` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `pengguna_id`, `admin_id`, `tanggal_transaksi`, `total_harga`, `status`, `catatan_batal`) VALUES
(106, 24, 1, '2025-11-18 08:55:32', 22000000, 'batal', 'gak ada uang'),
(114, 24, 1, '2025-11-18 09:36:21', 115000000, 'batal', 'y'),
(115, 24, 1, '2025-11-18 09:42:33', 330000000, 'batal', 'gak punya uang');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id_detail` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `jumlah` bigint NOT NULL,
  `harga` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id_detail`, `transaksi_id`, `produk_id`, `jumlah`, `harga`) VALUES
(83, 106, 38, 1, '22000000.00'),
(91, 114, 40, 1, '115000000.00'),
(92, 115, 47, 1, '330000000.00');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id_wishlist` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `id_produk` int NOT NULL,
  `tanggal_ditambahkan` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id_wishlist`, `id_pengguna`, `id_produk`, `tanggal_ditambahkan`) VALUES
(5, 24, 1, '2025-10-31 11:50:06'),
(6, 24, 4, '2025-10-31 12:16:02'),
(8, 24, 7, '2025-10-31 12:25:32'),
(14, 24, 38, '2025-11-13 16:01:22'),
(16, 24, 47, '2025-11-17 10:51:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `id_pengguna` (`id_pengguna`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `merk`
--
ALTER TABLE `merk`
  ADD PRIMARY KEY (`id_merk`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`transaksi_id`),
  ADD KEY `id_produk` (`produk_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id_wishlist`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `merk`
--
ALTER TABLE `merk`
  MODIFY `id_merk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id_wishlist` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`),
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`);

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id_transaksi`),
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id_produk`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
