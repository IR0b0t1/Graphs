-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 05:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `temperature`
--

-- --------------------------------------------------------

--
-- Table structure for table `graphs`
--

CREATE TABLE `graphs` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UserID` int(11) NOT NULL,
  `GraphNo` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `graphs`
--

INSERT INTO `graphs` (`ID`, `Name`, `UserID`, `GraphNo`) VALUES
(1, 'AdminGraph', 1, 1),
(5, 'TestGraph', 1, 2),
(14, 'Boner', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `temperature`
--

CREATE TABLE `temperature` (
  `ID` int(11) NOT NULL,
  `GraphID` int(11) NOT NULL,
  `Day` int(11) NOT NULL,
  `Temperature` double NOT NULL,
  `isDone` tinyint(1) NOT NULL,
  `isIllness` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `temperature`
--

INSERT INTO `temperature` (`ID`, `GraphID`, `Day`, `Temperature`, `isDone`, `isIllness`) VALUES
(2, 1, 1, 36.5, 1, 0),
(3, 1, 2, 36.1, 1, 0),
(4, 1, 3, 36.6, 1, 0),
(5, 1, 4, 36, 1, 1),
(6, 1, 5, 36, 1, 1),
(7, 1, 6, 36, 1, 1),
(8, 1, 7, 36.4, 1, 0),
(9, 1, 8, 36.6, 1, 0),
(10, 1, 9, 36.5, 1, 0),
(11, 1, 10, 36.3, 1, 0),
(12, 1, 11, 36.2, 1, 0),
(13, 1, 12, 36.7, 1, 0),
(14, 1, 13, 36, 0, 0),
(15, 1, 14, 36, 0, 0),
(16, 1, 15, 36, 0, 0),
(17, 1, 16, 36.7, 1, 1),
(18, 1, 17, 36.7, 1, 1),
(19, 1, 18, 36.7, 1, 1),
(20, 1, 19, 36.5, 1, 1),
(182, 1, 20, 36, 0, 1),
(209, 1, 22, 36.6, 1, 0),
(208, 1, 21, 36.5, 1, 0),
(140, 5, 11, 36.7, 1, 1),
(139, 14, 15, 36, 0, 0),
(138, 14, 14, 36.5, 1, 0),
(137, 14, 13, 36.4, 1, 0),
(136, 14, 12, 36.7, 1, 0),
(44, 5, 1, 36.4, 1, 0),
(45, 5, 2, 36.1, 1, 0),
(46, 5, 3, 36.6, 1, 0),
(47, 5, 4, 36, 1, 1),
(48, 5, 5, 36, 1, 1),
(49, 5, 6, 36, 1, 1),
(50, 5, 7, 36.4, 1, 0),
(51, 5, 8, 36.8, 1, 0),
(52, 5, 9, 36.5, 1, 0),
(53, 5, 10, 36.3, 1, 0),
(135, 14, 11, 36.6, 1, 0),
(134, 14, 10, 36.7, 1, 0),
(133, 14, 9, 36.9, 1, 0),
(132, 14, 8, 36, 1, 1),
(131, 14, 7, 36, 1, 1),
(130, 14, 6, 36, 1, 1),
(129, 14, 5, 36, 1, 1),
(128, 14, 4, 36.7, 1, 0),
(127, 14, 3, 36.4, 1, 0),
(126, 14, 2, 36.5, 1, 0),
(141, 5, 12, 36, 0, 1),
(125, 14, 1, 36.7, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Login` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Login`, `Password`) VALUES
(1, 'admin@gmail.com', '$2y$10$J1gt4nqCBiWeyojna0Hmues4BgcUSN8eFnnNhZ8UeepjItE3/gHru'),
(2, 'bboner@egzorcysta.pl', '$2y$10$xHeidVrkyp8h0nO/8U.Wo.Dp/51hMUDTxlLEaFg10QajGfZZ/fx9m');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `graphs`
--
ALTER TABLE `graphs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_graphs_user` (`UserID`);

--
-- Indexes for table `temperature`
--
ALTER TABLE `temperature`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `graphs`
--
ALTER TABLE `graphs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `temperature`
--
ALTER TABLE `temperature`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=309;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
