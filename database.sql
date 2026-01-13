-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql300.infinityfree.com
-- Czas generowania: 13 Sty 2026, 05:39
-- Wersja serwera: 11.4.9-MariaDB
-- Wersja PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `if0_39854076_wykresy_database`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Graphs`
--

CREATE TABLE `Graphs` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Zrzut danych tabeli `Graphs`
--

INSERT INTO `Graphs` (`ID`, `Name`, `UserID`) VALUES
(1, 'AdminGraph', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Temperature`
--

CREATE TABLE `Temperature` (
  `ID` int(11) NOT NULL,
  `GraphID` int(11) NOT NULL,
  `Day` int(11) NOT NULL,
  `Temperature` double NOT NULL,
  `isDone` tinyint(1) NOT NULL,
  `isIllness` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Zrzut danych tabeli `Temperature`
--

INSERT INTO `Temperature` (`ID`, `GraphID`, `Day`, `Temperature`, `isDone`, `isIllness`) VALUES
(2, 1, 1, 36.6, 1, 0),
(3, 1, 2, 36.4, 1, 0),
(4, 1, 3, 36.5, 1, 0),
(5, 1, 4, 36.3, 1, 0),
(6, 1, 5, 36.7, 1, 0),
(7, 1, 6, 36.2, 1, 0),
(8, 1, 7, 36.4, 1, 0),
(9, 1, 8, 36, 1, 1),
(10, 1, 9, 36.7, 1, 1),
(11, 1, 10, 36.7, 1, 1),
(12, 1, 11, 36.4, 1, 0),
(13, 1, 12, 36.7, 1, 0),
(14, 1, 13, 36, 0, 0),
(15, 1, 14, 36, 0, 0),
(16, 1, 15, 36, 0, 0),
(17, 1, 16, 36.7, 1, 1),
(18, 1, 17, 36.7, 1, 1),
(19, 1, 18, 36.7, 1, 1),
(20, 1, 19, 36.5, 1, 0),
(21, 1, 20, 36.7, 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `Users`
--

CREATE TABLE `Users` (
  `ID` int(11) NOT NULL,
  `Login` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Zrzut danych tabeli `Users`
--

INSERT INTO `Users` (`ID`, `Login`, `Password`) VALUES
(1, 'admin@gmail.com', '$2y$10$J1gt4nqCBiWeyojna0Hmues4BgcUSN8eFnnNhZ8UeepjItE3/gHru');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `Graphs`
--
ALTER TABLE `Graphs`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_graphs_user` (`UserID`);

--
-- Indeksy dla tabeli `Temperature`
--
ALTER TABLE `Temperature`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `Graphs`
--
ALTER TABLE `Graphs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `Temperature`
--
ALTER TABLE `Temperature`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT dla tabeli `Users`
--
ALTER TABLE `Users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
