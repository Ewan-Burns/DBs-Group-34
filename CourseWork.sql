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

--
-- Database: `CourseWork`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admins`
--

CREATE TABLE `Admins` (
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`userID`)
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
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`bidID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `BodyType`
--

CREATE TABLE `BodyType` (
  `bodyID` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`bodyID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `BodyType`
--

INSERT INTO `BodyType` (`bodyID`) VALUES
('Coupe'),
('Convertible'),
('SUV');

-- --------------------------------------------------------

--
-- Table structure for table `Buyers`
--

CREATE TABLE `Buyers` (
  `userID` int(11) NOT NULL,
  UNIQUE KEY `userID` (`userID`) USING BTREE
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

--Dumping data for table `Items`

INSERT INTO `Items` (`itemID`, `userID`, `auctionTitle`, `carTypeID`, `image`, `startingPrice`, `reservePrice`, `endDate`, `status`, `description`) VALUES
(1, NULL, 'Toyota Corolla', 1, NULL, 5000, 7000, '2024-11-30 18:00:00', 'Auction Open', 'Reliable compact car with excellent fuel efficiency'),
(2, NULL, 'Honda Civic', 1, NULL, 5500, 8000, '2024-12-01 12:00:00', 'Auction Open', 'Popular sedan known for its longevity and low maintenance costs'),
(3, NULL, 'Ford Mustang', 2, NULL, 15000, 20000, '2024-12-03 15:00:00', 'Auction Open', 'Classic American muscle car with a powerful V8 engine'),
(4, NULL, 'Chevrolet Camaro', 2, NULL, 14000, 19000, '2024-12-05 14:30:00', 'Auction Open', 'Sporty coupe with a strong performance and sleek design'),
(5, NULL, 'BMW 3 Series', 3, NULL, 12000, 15000, '2024-12-07 16:00:00', 'Auction Open', 'Luxury sedan with advanced features and impressive handling'),
(6, NULL, 'Mercedes-Benz C-Class', 3, NULL, 13000, 17000, '2024-12-09 19:00:00', 'Auction Open', 'Elegant luxury car with premium interior and smooth ride'),
(7, NULL, 'Volkswagen Golf', 1, NULL, 6000, 8000, '2024-12-11 11:00:00', 'Auction Open', 'Compact hatchback with a comfortable interior and efficient performance'),
(8, NULL, 'Tesla Model 3', 4, NULL, 30000, 35000, '2024-12-13 13:30:00', 'Auction Open', 'Electric car with impressive range and cutting-edge technology'),
(9, NULL, 'Audi A4', 3, NULL, 11000, 14000, '2024-12-15 10:00:00', 'Auction Open', 'Premium sedan with a refined interior and advanced features'),
(10, NULL, 'Nissan Altima', 1, NULL, 7000, 9000, '2024-12-17 17:00:00', 'Auction Open', 'Midsize sedan with excellent fuel efficiency and reliability'),
(11, NULL, 'Subaru Outback', 5, NULL, 8000, 10000, '2024-12-19 14:00:00', 'Auction Open', 'Versatile crossover with all-wheel drive and ample cargo space'),
(12, NULL, 'Jeep Wrangler', 5, NULL, 16000, 21000, '2024-12-21 15:00:00', 'Auction Open', 'Off-road capable SUV with a rugged design and powerful engine'),
(13, NULL, 'Mazda CX-5', 5, NULL, 9000, 11000, '2024-12-23 18:00:00', 'Auction Open', 'Compact SUV with a sporty feel and upscale interior'),
(14, NULL, 'Hyundai Elantra', 1, NULL, 5500, 7500, '2024-12-25 11:30:00', 'Auction Open', 'Affordable sedan with a comfortable ride and good fuel economy'),
(15, NULL, 'Porsche 911', 2, NULL, 50000, 60000, '2024-12-27 20:00:00', 'Auction Open', 'High-performance sports car with exceptional handling and power');


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

-- --------------------------------------------------------

--
-- Table structure for table `Sellers`
--

CREATE TABLE `Sellers` (
  `userID` int(11) NOT NULL,
  UNIQUE KEY `userID` (`userID`) USING BTREE
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
  `city` text NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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