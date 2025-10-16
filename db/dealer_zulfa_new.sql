-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 30, 2025 at 11:36 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `id_admin` int(11) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `email`, `password`) VALUES
(9, 'hemachandra@gmail.com', '$2y$10$YeZa5r26ehwVtin67KtOHObGwCyAwBZgYmi.rzUopm03cEFX7A8iW'),
(10, 'wulan@gmail.com', '$2y$10$pD9WMq5ySFdBuzZmp47mFepaY3BmxKuTvJy3la7A9kRSmxO7FpVGW'),
(11, 'jeano@gmail.com', '223'),
(12, 'jeano@gmail.com', '223');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_pengguna`, `id_produk`, `qty`, `tanggal`) VALUES
(25, 16, 38, 1, '2025-09-24 09:04:25'),
(26, 16, 36, 1, '2025-09-24 09:05:21'),
(27, 17, 36, 3, '2025-09-25 08:23:28');

-- --------------------------------------------------------

--
-- Table structure for table `merk`
--

CREATE TABLE `merk` (
  `id_merk` int(11) NOT NULL,
  `nama_merk` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merk`
--

INSERT INTO `merk` (`id_merk`, `nama_merk`) VALUES
(3, 'suzuki'),
(17, 'yamahaa'),
(18, 'honda');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `nama_pengguna` varchar(25) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` text NOT NULL,
  `no_hp` int(13) NOT NULL,
  `alamat` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `nama_pengguna`, `email`, `password`, `no_hp`, `alamat`) VALUES
(9, 'zuzuu', 'zulfaa@gmail.com', '', 2147483647, 'kp_bungaa'),
(14, 'hemachandra', 'hema@gmail.com', '', 2147483647, 'jl_aksara'),
(15, 'ikis', 'kisticantik@gmail.com', '', 2147483647, 'cikupa'),
(16, '', 'a@a', '$2y$10$tPjxxGzfrZFwRqmwoktV/uQSwhGK.HDSNok0JhsMPijvyqGNn17M6', 0, ''),
(17, '', 'wulan@gmail.com', '$2y$10$0iWVn9dd2V.eCUrC3KU.OeNYjTTJIZT.PZ18A7tmWaWh2XmGj2c1y', 0, ''),
(18, '', 'ulan@gmail.com', '$2y$10$WyUFjcdZfT2KCLn4laBE2uRTXjdgINuFpLzNu4/SMIkWYDTJf.Xvq', 0, ''),
(19, '', 'ullan@gmail.com', '$2y$10$/.J4b0KhmGjgzsy.eILZ9Oh7s4m2qeseov4eYZhfU7W96MQ8pcMFu', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `merk_id` int(11) NOT NULL,
  `nama_produk` varchar(25) NOT NULL,
  `harga` double NOT NULL,
  `deskripsi` varchar(150) NOT NULL,
  `stock` int(30) NOT NULL,
  `kategori` enum('classic','matic','','') NOT NULL,
  `photo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `merk_id`, `nama_produk`, `harga`, `deskripsi`, `stock`, `kategori`, `photo`) VALUES
(27, 14, 'vesmet', 1212000000, 'motor metic bagus dengan harga terjangkau', 10, 'matic', 'uploads/1757066129_vesss.jpg'),
(36, 14, 'staylo', 124560000000000, 'motor matic dengan tampilan terbaru ', -17, 'matic', 'uploads/1757065928_staylo.jpg'),
(38, 18, 'staylo', 1.23e15, 'motor matic dengan tampilan terbaru ', 1, 'matic', 'uploads/1757069971_staylo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `total_harga` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `pengguna_id`, `admin_id`, `produk_id`, `jumlah`, `tanggal_transaksi`, `total_harga`) VALUES
(1, 9, 0, 36, 1, '2025-09-09', 124560000000000),
(2, 9, 0, 36, 2, '2025-09-09', 249120000000000),
(3, 9, 0, 36, 1, '2025-09-09', 124560000000000),
(4, 9, 0, 36, 1, '2025-09-09', 124560000000000),
(5, 9, 0, 36, 1, '2025-09-09', 124560000000000),
(6, 14, 0, 36, 9, '2025-09-16', 1.12104e15);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id_detail` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id_detail`, `transaksi_id`, `produk_id`, `jumlah`, `harga`) VALUES
(1, 1, 36, 1, 9999999999999.99),
(2, 2, 36, 2, 9999999999999.99),
(3, 3, 36, 1, 9999999999999.99),
(4, 4, 36, 1, 9999999999999.99),
(5, 5, 36, 1, 9999999999999.99),
(6, 6, 36, 9, 9999999999999.99);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `merk`
--
ALTER TABLE `merk`
  MODIFY `id_merk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
