-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 12, 2024 at 05:55 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `CourseWork`

-- Table structure for table `Users`
CREATE TABLE `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `firstName` varchar(255) DEFAULT '',
  `lastName` varchar(255) DEFAULT '',
  `dateOfBirth` date DEFAULT '2000-01-01',
  `country` varchar(255) DEFAULT '',
  `street` varchar(255) DEFAULT '',
  `houseNumber` int(11) DEFAULT 0,
  `postcode` varchar(255) DEFAULT '',
  `city` varchar(255) DEFAULT '',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `email`, `passwordHash`, `firstName`, `lastName`, `dateOfBirth`, `country`, `street`, `houseNumber`, `postcode`, `city`) VALUES
(1, 'david.alesch@me.com', 'qwertzui', 'David', 'Alesch', '2001-10-01', 'London', '', 0, '', ''),
(2, 'verabpe@gmail.com', 'asdfghjkl', 'Vera', 'Eusebio', '2000-12-30', 'London', '', 0, '', ''),
(3, 'ewan.burns.24@ucl.ac.uk', 'yxcvbn', 'Ewan', 'Burns', '2002-03-12', 'London', '', 0, '', ''),
(4, 'paula.droeghoff.24@ucl.ac.uk', 'edcvfr', 'Paula', 'Droeghoff', '2000-06-16', 'London', '', 0, '', '');




-- Table structure for table `Bids`
CREATE TABLE `Bids` (
  `bidID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `amount` float NOT NULL,
  `bidTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`bidID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `BodyType`
CREATE TABLE `BodyType` (
  `bodyID` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`bodyID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `BodyType`
INSERT INTO `BodyType` (`bodyID`) VALUES
('Coupe'),
('Convertible'),
('SUV');


-- --------------------------------------------------------

--
-- Table structure for table `CarTypes`
--

CREATE TABLE `CarTypes` (
  `carTypeID` int(11) NOT NULL,
  `colour` text NOT NULL,
  `make` enum('Mercedes','BMW','Ford','Ferrari','Mini','Jaguar','Porsche','Fiat','Opel','Toyota','Tesla','Audi','Mazda','Aston Martin','Chevrolet','Chrysler','Bentley','Other') NOT NULL,
  `bodyType` enum('Convertible','SUV','Coupe','Hatchback','Shooting Brake','Sports','Sedan','Supermini','Van','Pickup','Cabriolet','Other') NOT NULL,
  `year` year(4) NOT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuelType` enum('Gasoline','Diesel','Electric','Hybrid') DEFAULT NULL,
  `transmission` enum('Manual','Automatic') DEFAULT NULL,
  PRIMARY KEY (`carTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CarTypes`
--

INSERT INTO `CarTypes` (`carTypeID`, `colour`, `make`, `bodyType`, `year`, `mileage`, `fuelType`, `transmission`) VALUES
(1, 'Black', 'Mercedes', 'Convertible', '1999', 100000, 'Gasoline', 'Manual'),
(2, 'Black', 'Fiat', 'Convertible', '1965', NULL, NULL, NULL),
(3, 'Black', 'Fiat', 'Convertible', '2000', NULL, NULL, NULL),
(4, 'Black', 'Fiat', 'Convertible', '2020', NULL, NULL, NULL),
(5, 'Black', 'Mercedes', 'Coupe', '2000', NULL, NULL, NULL),
(6, 'Red', 'Mercedes', 'Coupe', '2010', NULL, NULL, NULL),
(7, 'Black', 'Mercedes', 'Coupe', '2011', NULL, NULL, NULL),
(8, 'Navy', 'Fiat', 'Convertible', '1965', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Colour`
--

CREATE TABLE `Colour` (
  `colourID` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`colourID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Colour`
--

INSERT INTO `Colour` (`colourID`) VALUES
('Black'),
('Red'),
('White'),
('Navy');

-- --------------------------------------------------------

--
-- Table structure for table `Items`
--

CREATE TABLE `Items` (
  `itemID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `auctionTitle` text NOT NULL,
  `carTypeID` int(11) DEFAULT NULL,
  `image` longblob DEFAULT NULL,
  `startingPrice` float NOT NULL,
  `reservePrice` float NOT NULL,
  `endDate` datetime NOT NULL,
  `status` text DEFAULT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for table `Make`
--

CREATE TABLE `Make` (
  `makeID` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`makeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dumping data for table `Make`
--

INSERT INTO `Make` (`makeID`) VALUES
('Mercedes'),
('Fiat'),
('Audi'),
('Volkswagen'),
('Porsche'),
('Opel'),
('Toyota'),
('Ferrari'),
('Renault');

-- --------------------------------------------------------

--
-- Table structure for table `Ratings`
--

CREATE TABLE `Ratings` (
  `userID` int(11) NOT NULL,
  `rating` enum('1','2','3','4','5') NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`userID`, `rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `Watchlist`
--

CREATE TABLE `watchlist` (
  `userID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  PRIMARY KEY (`userID`,`itemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------
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

--ALTER TABLE `Buyers`
--  ADD UNIQUE KEY `userID` (`userID`) USING BTREE