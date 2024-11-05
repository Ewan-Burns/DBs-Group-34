-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 30, 2024 at 12:24 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CourseWork`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admins`
--

CREATE TABLE `Admins` (
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Bids`
--

CREATE TABLE `Bids` (
  `bidID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `amount` float NOT NULL,
  `bidTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Buyers`
--

CREATE TABLE `Buyers` (
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Buyers`
--

INSERT INTO `Buyers` (`userID`) VALUES
(3),
(4);

-- --------------------------------------------------------

--
-- Table structure for table `CarTypes`
--

CREATE TABLE `CarTypes` (
  `carTypeID` int(11) NOT NULL,
  `colour` enum('Black','White','Red','Blue','Grey','Green','Pink','Yellow','Bronze','Silver','Other') NOT NULL,
  `make` enum('Mercedes','BMW','Ford','Ferrari','Mini','Jaguar','Porsche','Fiat','Opel','Toyota','Tesla','Audi','Mazda','Aston Martin','Chevrolet','Chrysler','Bentley','Other') NOT NULL,
  `bodyStyle` enum('Convertible','SUV','Coupe','Hatchback','Shooting Brake','Sports','Sedan','Supermini','Van','Pickup','Cabriolet','Other') NOT NULL,
  `year` year(4) NOT NULL,
  `mileage` int(11) NOT NULL,
  `fuelType` enum('Gasoline','Diesel','Electric','Hybrid') NOT NULL,
  `transmission` enum('Manual','Automatic') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CarTypes`
--

INSERT INTO `CarTypes` (`carTypeID`, `colour`, `make`, `bodyStyle`, `year`, `mileage`, `fuelType`, `transmission`) VALUES
(1, 'Black', 'Mercedes', 'Convertible', '1999', 100000, 'Gasoline', 'Manual');

-- --------------------------------------------------------

--
-- Table structure for table `Items`
--

CREATE TABLE `Items` (
  `itemID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
<<<<<<< HEAD
  `auctionTitle` text NOT NULL,
=======
  'auctionTitle' varchar(100) NOT NULL,
>>>>>>> 9c55ba6a0caf3ab5f5b4d4fd3e30785b4cd1e319
  `cartypeID` int(11) NOT NULL,
  `image` longblob NOT NULL,
  `startingPrice` float NOT NULL,
  `reservePrice` float NOT NULL,
  `endDate` datetime NOT NULL,
  `status` enum('Future','Open','Retracted','Bidding Closed','Cancelled','Paused','Completed','Extended') NOT NULL,
  `description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Ratings`
--

CREATE TABLE `Ratings` (
  `userID` int(11) NOT NULL,
  `rating` enum('1','2','3','4','5') NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Sellers`
--

CREATE TABLE `Sellers` (
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Sellers`
--

INSERT INTO `Sellers` (`userID`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `userID` int(11) NOT NULL,
  `email` text NOT NULL,
  `passwordHash` text NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `dateOfBirth` date NOT NULL,
  `country` text NOT NULL,
  `street` text NOT NULL,
  `houseNumber` int(11) NOT NULL,
  `postcode` text NOT NULL,
  `city` text NOT NULL
) ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `email`, `passwordHash`, `firstName`, `lastName`, `dateOfBirth`, `country`, `street`, `houseNumber`, `postcode`, `city`) VALUES
(1, 'david.alesch@me.com', 'qwertzui', 'David', 'Alesch', '2001-10-01', 'London', '', 0, '', ''),
(2, 'verabpe@gmail.com', 'asdfghjkl', 'Vera', 'Eusebio', '2000-12-30', 'London', '', 0, '', ''),
(3, 'ewan.burns.24@ucl.ac.uk', 'yxcvbn', 'Ewan', 'Burns', '2002-03-12', 'London', '', 0, '', ''),
(4, 'paula.droeghoff.24@ucl.ac.uk', 'edcvfr', 'Paula', 'Droeghoff', '2000-06-16', 'London', '', 0, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Bids`
--
ALTER TABLE `Bids`
  ADD PRIMARY KEY (`bidID`);

--
-- Indexes for table `Buyers`
--
ALTER TABLE `Buyers`
  ADD UNIQUE KEY `userID` (`userID`) USING BTREE;

--
-- Indexes for table `CarTypes`
--
ALTER TABLE `CarTypes`
  ADD PRIMARY KEY (`carTypeID`);

--
-- Indexes for table `Items`
--
ALTER TABLE `Items`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `Sellers`
--
ALTER TABLE `Sellers`
  ADD UNIQUE KEY `userID` (`userID`) USING BTREE;

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Bids`
--
ALTER TABLE `Bids`
  MODIFY `bidID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `CarTypes`
--
ALTER TABLE `CarTypes`
  MODIFY `carTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Items`
--
ALTER TABLE `Items`
  MODIFY `itemID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;