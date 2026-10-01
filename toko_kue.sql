-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 01, 2026 at 03:15 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_kue`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`, `created_at`) VALUES
(1, 'Administrator', 'admin', '$2y$10$98vbxh4xT65X0Rnc9/L33eWkPdxz8VL4hbvd338D7IILqDOqYj4e6', '2026-09-27 13:56:08');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar_sample` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `kategori`, `deskripsi`, `harga`, `stok`, `gambar`, `gambar_sample`, `created_at`) VALUES
(4, 'BLOKEES Fantastics Series - Sakura Miku', 'Figure', 'Keunggulan Produk\r\nFitur Unggulan:\r\nPermukaan Halus & Lembut (Gentle, Squishy-Smooth Finish)\r\nLapisan permukaan soft-touch memberikan sensasi premium saat disentuh, sekaligus menjaga ketahanan figur agar tetap awet.\r\nDesain Seamless Satu Bagian (Seamless One-Piece Design)\r\nKonstruksi menyatu tanpa garis sambungan yang terlihat, menciptakan tampilan yang rapi, bersih, dan profesional.\r\nSambungan Artikulasi Bi-Material (Bi-Material Articulated Joints)\r\nMenggunakan sambungan dual-tone injection molded yang memberikan fleksibilitas pose luar biasa dengan pergerakan yang stabil dan konsisten.\r\nSistem Sculpting Wajah yang Ditingkatkan (Enhanced Face Sculpting System)\r\nDilengkapi berbagai aksesori display seperti pohon sakura, perlengkapan piknik, makanan, kamera, ponsel, serta 6 set tangan yang dapat dipertukarkan.\r\nElemen Sakura yang Dapat Disesuaikan (Adjustable Sakura Elements)\r\nTersedia face plate yang dapat diganti dengan ekspresi tercetak dan decal water-transfer untuk memberikan lebih banyak pilihan kustomisasi sesuai selera.', 661000.00, 20, 'img_6abda64ee2f45.png', 'img_6abda64ee458e.png', '2026-10-01 00:16:14'),
(5, 'Teasing Master Takagi-san, Vol. 20 (Volume 20)', 'Manga', 'For as long as he’s known her, Nishikata has always been one step behind Takagi-san. He’s spent every waking moment dreaming of a win, hoping to finally get one over on the girl who teases him like it’s her life’s work. But contest after contest, loss after loss―Nishikata begins to realize he may have been chasing something more important this whole time…The final volume of their cat-and-mouse game promises romance and revelation as the summer festival approaches, bringing Nishikata and Takagi-san’s battle of cunning and youth to a close.', 185000.00, 42, 'img_6abdcc0353394.png', 'img_6abdcc0354f7a.png', '2026-10-01 02:57:07'),
(6, 'One Piece Anime Green Roronoa Zoro Jolly Roger T-Shirt', 'Apparel', 'One Piece Anime Merchandise design. Officially licensed One Piece anime merch. Join Luffy and the Straw Hat Crew on their pirate adventures. Show off your love of the long-running anime series in this cool gear from One Piece.\r\nPerfect graphic merchandise for fans of Pirates, Ships, Anime, Cartoons, Pirate Hunter Zoro, Luffy, Going Merry, Thousand Sunny, Devil Fruit, and Streetwear fashion. Our One Piece merch is for anime fans of all ages.\r\nLightweight, Classic fit, Double-needle sleeve and bottom hem', 128000.00, 125, 'img_6abdce65d5c90.jpg', 'img_6abdce65d62ed.jpg', '2026-10-01 03:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
