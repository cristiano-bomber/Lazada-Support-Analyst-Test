-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2017 at 10:49 AM
-- Server version: 5.6.32
-- PHP Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lazada_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `Location`
--

CREATE TABLE `Location` (
  `Id` int(11) NOT NULL,
  `PostalCode` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Location`
--

INSERT INTO `Location` (`Id`, `PostalCode`) VALUES
(1, 700000),
(2, 710000),
(3, 720000);

-- --------------------------------------------------------

--
-- Table structure for table `Location-Shipping-Fee`
--

CREATE TABLE `Location-Shipping-Fee` (
  `SourceLocation` int(11) UNSIGNED NOT NULL,
  `DestinationLocation` int(11) UNSIGNED NOT NULL,
  `ShippingFee` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Location-Shipping-Fee`
--

INSERT INTO `Location-Shipping-Fee` (`SourceLocation`, `DestinationLocation`, `ShippingFee`) VALUES
(700000, 700000, 0),
(700000, 710000, 5),
(700000, 720000, 7),
(710000, 700000, 5),
(710000, 710000, 0),
(710000, 720000, 5),
(720000, 700000, 7),
(720000, 710000, 5),
(720000, 720000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Product`
--

CREATE TABLE `Product` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Name` text NOT NULL,
  `Price` double NOT NULL,
  `Weight` double NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Product`
--

INSERT INTO `Product` (`Id`, `Name`, `Price`, `Weight`, `Quantity`) VALUES
(1, 'T-Shirt short sleeve', 30.5, 100, 100),
(2, 'T-Shirt long sleeve', 30.5, 100, 100),
(3, 'Jeans Jacket Long Sleev', 90, 200, 100),
(4, 'Skinny Stretch Jeans', 79.5, 200, 100),
(5, 'Slim Fit Stretch Jeans', 79.5, 180, 100),
(6, 'Men High Boots', 120, 1200, 100),
(7, 'Fullface Helmet', 150, 1300, 100);

-- --------------------------------------------------------

--
-- Table structure for table `Supplier`
--

CREATE TABLE `Supplier` (
  `Id` int(10) UNSIGNED NOT NULL,
  `Name` text NOT NULL,
  `PostalCode` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Supplier`
--

INSERT INTO `Supplier` (`Id`, `Name`, `PostalCode`) VALUES
(1, 'Levi\'s', 700000),
(2, 'Calvin Klein', 710000),
(3, 'Guess', 720000),
(4, 'Timberland', 700000),
(5, 'Dr.Martens', 710000),
(6, 'Clark', 720000),
(7, 'Andes', 700000),
(8, 'Index', 710000),
(9, 'Red Bull', 720000);

-- --------------------------------------------------------

--
-- Table structure for table `Supplier-Product`
--

CREATE TABLE `Supplier-Product` (
  `SupplierId` int(10) UNSIGNED NOT NULL,
  `ProductId` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Supplier-Product`
--

INSERT INTO `Supplier-Product` (`SupplierId`, `ProductId`) VALUES
(1, 4),
(1, 5),
(2, 1),
(2, 2),
(2, 3),
(3, 3),
(3, 4),
(4, 6),
(5, 6),
(6, 6),
(7, 7),
(8, 7),
(9, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Location`
--
ALTER TABLE `Location`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `PostalCode` (`PostalCode`);

--
-- Indexes for table `Location-Shipping-Fee`
--
ALTER TABLE `Location-Shipping-Fee`
  ADD KEY `SourceLocation` (`SourceLocation`,`DestinationLocation`),
  ADD KEY `FK_DestinationLocation_Location` (`DestinationLocation`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `Supplier`
--
ALTER TABLE `Supplier`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `PostalCode` (`PostalCode`);

--
-- Indexes for table `Supplier-Product`
--
ALTER TABLE `Supplier-Product`
  ADD KEY `SupplierId` (`SupplierId`,`ProductId`),
  ADD KEY `FK_SupPro_Product` (`ProductId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Location`
--
ALTER TABLE `Location`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `Supplier`
--
ALTER TABLE `Supplier`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Location-Shipping-Fee`
--
ALTER TABLE `Location-Shipping-Fee`
  ADD CONSTRAINT `FK_DestinationLocation_Location` FOREIGN KEY (`DestinationLocation`) REFERENCES `Location` (`PostalCode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_SourceLocation_Location` FOREIGN KEY (`SourceLocation`) REFERENCES `Location` (`PostalCode`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Supplier`
--
ALTER TABLE `Supplier`
  ADD CONSTRAINT `FK_Supplier_Location` FOREIGN KEY (`PostalCode`) REFERENCES `Location` (`PostalCode`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Supplier-Product`
--
ALTER TABLE `Supplier-Product`
  ADD CONSTRAINT `FK_SupPro_Product` FOREIGN KEY (`ProductId`) REFERENCES `Product` (`Id`),
  ADD CONSTRAINT `FK_SupPro_Supplier` FOREIGN KEY (`SupplierId`) REFERENCES `Supplier` (`Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
