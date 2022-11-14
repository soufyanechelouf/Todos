-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2022 at 01:44 PM
-- Server version: 8.0.28
-- PHP Version: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todos`
--

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `ID` int NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `Ordering` int DEFAULT NULL,
  `Visibility` tinyint NOT NULL DEFAULT '1',
  `Due` date DEFAULT NULL,
  `UserID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `todos`
--

INSERT INTO `todos` (`ID`, `Name`, `Description`, `Ordering`, `Visibility`, `Due`, `UserID`) VALUES
(11, 'acheter un refrigerateur', 'refrigerateur tres puissant de haute gamme', 9, 1, '2022-11-25', 11),
(14, 'Lave-vaisselle', 'reparer Lave-vaisselle ', 1, 0, '2022-11-24', 4),
(16, 'home work', 'home work of physics last exo', 17, 0, '2022-11-16', 11),
(17, 'visit doctor', 'visiting the dentist in oran before weekend', 9, 0, '2022-11-18', 11),
(18, 'buy stuffs', 'visit the mall to buy clothes', 20, 0, '2022-11-18', 11),
(19, 'learning', 'learn chapter 5', 13, 1, '2022-11-10', 11),
(20, 'test ', 'test code ', 11, 1, '2020-09-11', 11),
(21, 'clean the house', 'clean the house', 3, 1, '2022-11-25', 4),
(22, 'todos1', 'todos 1', 10, 1, '2022-11-17', 4),
(23, 'task of karim', 'task karime desc', 10, 0, '2022-11-03', 4),
(25, 'home work', 'home work', 11, 1, '2022-11-15', 12),
(26, 'zezee', '', 12, 1, '2022-11-14', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int NOT NULL COMMENT 'to identify user',
  `Username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `GroupeID` int NOT NULL DEFAULT '0',
  `TrustStatus` int NOT NULL DEFAULT '0',
  `RegStatus` int NOT NULL DEFAULT '1',
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Email`, `FullName`, `GroupeID`, `TrustStatus`, `RegStatus`, `Date`) VALUES
(1, 'sofnet', 'sofnetby@gmail.com', 'chelouf soufyane', 1, 0, 1, '0000-00-00'),
(2, 'riad', 'riad@gmail.com', 'riad yamani', 0, 0, 1, '0000-00-00'),
(4, 'karim', 'karim@gmail.com', 'karim amin', 0, 0, 1, '0000-00-00'),
(11, 'sofiane', 'sofiane@gmail.com', 'sofiane chelouf', 0, 0, 1, '2022-11-12'),
(12, 'salah eddine', 'salah@gmail.com', NULL, 0, 0, 1, '2022-11-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserIdindex` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD KEY `UserIDindex` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT COMMENT 'to identify user', AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
