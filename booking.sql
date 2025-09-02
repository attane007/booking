-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2025 at 10:43 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bw_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `reserve`
--

CREATE TABLE `reserve` (
  `id_table` varchar(5) COLLATE utf8mb4_bin NOT NULL,
  `name_table` varchar(3) COLLATE utf8mb4_bin NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `tel` int(10) UNSIGNED ZEROFILL NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `status` int(1) NOT NULL,
  `status_pay` int(1) NOT NULL,
  `date_buy` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `seller` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `payment` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `cookie_` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `date_del` varchar(20) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `reserve`
--

INSERT INTO `reserve` (`id_table`, `name_table`, `name`, `tel`, `email`, `status`, `status_pay`, `date_buy`, `seller`, `payment`, `cookie_`, `date_del`) VALUES
('T_18', '18', 'VIP', 0000000000, '', 4, 1, '2025-01-09 17:18:55', 'ส่วนกลาง', '', '', ''),
('T_19', '19', 'VIP', 0000000000, '', 4, 1, '2025-01-09 17:26:45', 'ส่วนกลาง', '', '', ''),
('T_30', '30', 'VIP', 0000000000, '', 4, 1, '2025-01-09 17:19:09', 'ส่วนกลาง', '', '', ''),
('T_31', '31', 'VIP', 0000000000, '', 4, 1, '2025-01-09 17:27:32', 'ส่วนกลาง', '', '', ''),
('T_44', '44', 'sdf', 0000000000, 'sdf@gmail.com', 4, 1, '2025-01-13 16:35:22', 'ระบบออนไลน์', 'bGpkenhZekxnUHdLUklEMjNwd3BqZz09OjpjtauTYD7uLRfO3UJUbzZxOjo=', '', ''),
('T_45', '45', 'sdf', 0000000000, 'sdf@gmail.com', 3, 1, '2025-01-13 16:35:22', 'ระบบออนไลน์', 'bGpkenhZekxnUHdLUklEMjNwd3BqZz09OjpjtauTYD7uLRfO3UJUbzZxOjo=', '', ''),
('T_46', '46', 'sdf', 0000000000, 'sdf@gmail.com', 3, 1, '2025-01-13 16:35:22', 'ระบบออนไลน์', 'bGpkenhZekxnUHdLUklEMjNwd3BqZz09OjpjtauTYD7uLRfO3UJUbzZxOjo=', '', ''),
('T_6', '6', 'VIP', 0000000000, '', 4, 1, '2025-01-09 17:05:24', 'ส่วนกลาง', '', '', ''),
('T_7', '7', 'VIP', 0000000000, '', 4, 1, '2025-01-09 17:19:29', 'ส่วนกลาง', '', '', ''),
('T_8', '8', 'คุณเจ', 0000000000, '', 2, 0, '2025-01-13 16:42:30', 'ส่วนกลาง', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reserve`
--
ALTER TABLE `reserve`
  ADD PRIMARY KEY (`id_table`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
