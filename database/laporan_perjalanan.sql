-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2023 at 05:20 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laporan_perjalanan`
--

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `alamat_awal` varchar(255) NOT NULL,
  `alamat_tujuan` varchar(255) NOT NULL,
  `km_awal` int(255) NOT NULL,
  `km_akhir` int(255) NOT NULL,
  `no_polisi` varchar(255) NOT NULL,
  `tipe_mobil` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `foto2` varchar(255) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `lampu_depan` enum('berfungsi','rusak') NOT NULL,
  `lampu_sen_depan` enum('berfungsi','rusak') NOT NULL,
  `lampu_sen_belakang` enum('berfungsi','rusak') NOT NULL,
  `lampu_rem` enum('berfungsi','rusak') NOT NULL,
  `lampu_mundur` enum('berfungsi','rusak') NOT NULL,
  `bodi` enum('baik','rusak') NOT NULL,
  `ban` enum('baik','rusak') NOT NULL,
  `pedal` enum('berfungsi','rusak') NOT NULL,
  `kopling` enum('berfungsi','rusak') NOT NULL,
  `gas_rem` enum('berfungsi','rusak') NOT NULL,
  `klakson` enum('baik','rusak') NOT NULL,
  `weaper` enum('berfungsi','rusak') NOT NULL,
  `air_weaper` enum('terisi','kosong') NOT NULL,
  `air_radiator` enum('terisi','kosong') NOT NULL,
  `oli_mesin` enum('terisi','kosong') NOT NULL,
  `note` varchar(255) NOT NULL,
  `status_lap` enum('approved','pending','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `user_id`, `alamat_awal`, `alamat_tujuan`, `km_awal`, `km_akhir`, `no_polisi`, `tipe_mobil`, `foto`, `foto2`, `tanggal`, `lampu_depan`, `lampu_sen_depan`, `lampu_sen_belakang`, `lampu_rem`, `lampu_mundur`, `bodi`, `ban`, `pedal`, `kopling`, `gas_rem`, `klakson`, `weaper`, `air_weaper`, `air_radiator`, `oli_mesin`, `note`, `status_lap`) VALUES
(19, 35, 'palembang', 'bekasi', 23, 43, 'b 232 kl', 'veloz', 'git.JPG', '', '2023-06-23', 'berfungsi', 'rusak', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'baik', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'berfungsi', 'terisi', 'kosong', 'kosong', '', 'pending'),
(20, 35, 'bengkulu', 'jambi', 43, 56, 'b 563 kj', 'inova', 'bio.JPG', '', '2023-06-20', 'berfungsi', 'rusak', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'baik', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'rusak', 'kosong', 'kosong', 'terisi', '', 'pending'),
(25, 2, 'Bandar lampung', 'Palembang', 85, 120, 'be 432 kl', 'avanza', '16883669241438787827858740041804.jpg', '', '2023-07-03', 'berfungsi', 'rusak', 'berfungsi', 'rusak', 'berfungsi', 'baik', 'baik', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'berfungsi', 'terisi', 'terisi', 'kosong', '', 'pending'),
(26, 2, 'Bandar lampung', 'Bandung', 58, 69, 'b 563 kj', 'inova', '16883690349872936757379825971594.jpg', '', '2023-07-03', 'berfungsi', 'rusak', 'rusak', 'berfungsi', 'berfungsi', 'baik', 'baik', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'berfungsi', 'terisi', 'terisi', 'kosong', '', 'pending'),
(27, 39, 'bandar lampung', 'aceh', 23, 43, 'b 563 kj', 'inova', '1587545625547.jpg', '', '2023-07-06', 'rusak', 'berfungsi', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'baik', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'berfungsi', 'terisi', 'terisi', 'kosong', '', 'pending'),
(40, 2, 'dasfa', 'fasfas', 43, 65, 'be 432 kl', 'avanza', '307883505_595025065489739_371558273912841970_n.jpg', '', '2023-07-26', 'berfungsi', 'rusak', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'baik', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'rusak', 'kosong', 'kosong', 'kosong', 'tes tes', 'pending'),
(41, 2, 'dasd', 'dsad', 65, 97, 'fsfs', 'fsfsd', '1587545625547.jpg', '', '2023-07-26', 'berfungsi', 'berfungsi', 'berfungsi', 'rusak', 'rusak', 'baik', 'baik', 'berfungsi', 'berfungsi', 'berfungsi', 'baik', 'berfungsi', 'terisi', 'kosong', 'kosong', '', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `status`) VALUES
(1, 'admin', 'admin', 'admin', 'approved'),
(2, 'bagas', '2345', 'user', 'approved'),
(35, 'dilan', '12345', 'user', 'approved'),
(39, 'zointa', '12345', 'user', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan`
--
ALTER TABLE `laporan`
  ADD CONSTRAINT `laporan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
