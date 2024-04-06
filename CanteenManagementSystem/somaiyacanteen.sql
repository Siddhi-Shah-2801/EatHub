-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2023 at 12:26 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `somaiyacanteen`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `canteenAllTimeRevenue` (IN `canteenId` INT(10))   BEGIN
	SELECT SUM(ord.orderAmount*ord.orderBuyPrice) AS canteenAllTimeRevenue
    FROM orderheader orh INNER JOIN orderdetail ord ON orh.orderHeaderId = ord.orderHeaderId
    INNER JOIN food f ON f.foodId = ord.foodId INNER JOIN canteen c ON c.canteenId = orh.canteenId
    WHERE c.canteenId = canteenId AND orh.orderHeaderOrderStatus = 'Finish';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `canteenMenuRevenue` (IN `canteenId` INT(10))   BEGIN
	SELECT f.foodName AS foodName, SUM(ord.orderAmount*ord.orderBuyPrice) AS canteenMenuRevenue
    FROM orderheader orh INNER JOIN orderdetail ord ON orh.orderHeaderId = ord.orderHeaderId
    INNER JOIN food f ON f.foodId = ord.foodId
    WHERE orh.canteenId = canteenId AND orh.orderHeaderOrderStatus = 'Finish'
    GROUP BY ord.foodId ORDER BY canteenMenuRevenue DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerOrder` (IN `orderId` INT(10))   BEGIN
	SELECT orh.orderHeaderReferenceCode AS referenceCode, CONCAT(cu.customerFirstName,' ',cu.customerLastname) AS customerName, c.canteenName AS canteenName,f.foodName AS foodName,ord.orderBuyPrice AS buyPrice, ord.orderAmount AS amount ,ord.orderNote AS orderNote, orh.orderHeaderOrderTime AS orderTime , orh.orderHeaderPickupTime AS pickupTime
    FROM orderheader orh 
    INNER JOIN orderdetail ord ON orh.orderHeaderId = ord.orderHeaderId
    INNER JOIN food f ON f.foodId = ord.foodId
    INNER JOIN customer cu ON orh.customerId = cu.customerId
    INNER JOIN canteen c ON orh.canteenId = c.canteenId
    WHERE orh.orderHeaderId = orderId; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `customerOrderHistory` (IN `customerId` INT(10))   BEGIN
	SELECT orh.orderHeaderReferenceCode AS referenceCode, CONCAT(cu.customerFirstName,' ',cu.customerLastName) AS customerName,
    c.canteenName AS canteenName, orh.orderHeaderOrderTime AS orderTime, orh.orderHeaderPickupTime AS pickupTime,
    p.paymentAmount AS orderCost, orh.orderHeaderOrderStatus AS orderStatus
    FROM orderHeader orh INNER JOIN customer cu ON orh.customerId = cu.customerId
    INNER JOIN payment p ON orh.paymentId = p.paymentId
    INNER JOIN canteen c ON orh.canteenId = c.canteenId
    WHERE cu.customerId = customerId;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `canteen`
--

CREATE TABLE `canteen` (
  `canteenId` int(10) NOT NULL,
  `canteenUserName` varchar(50) NOT NULL,
  `canteenPassword` varchar(50) NOT NULL,
  `canteenName` varchar(50) NOT NULL,
  `canteenLocation` varchar(50) NOT NULL,
  `canteenOpenHour` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `canteenCloseHour` time(6) NOT NULL,
  `canteenStatus` int(10) NOT NULL,
  `canteenPreOrderStatus` int(10) NOT NULL,
  `canteenEmail` varchar(50) NOT NULL,
  `canteenContactNo` int(20) NOT NULL,
  `canteenPic` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `canteen`
--

INSERT INTO `canteen` (`canteenId`, `canteenUserName`, `canteenPassword`, `canteenName`, `canteenLocation`, `canteenOpenHour`, `canteenCloseHour`, `canteenStatus`, `canteenPreOrderStatus`, `canteenEmail`, `canteenContactNo`, `canteenPic`) VALUES
(1, 'engineering', 'Siddhi10', 'AaryaBhatta', 'gargi plaza', '2023-03-12 14:34:34.350191', '22:14:00.886000', 1, 1, 'aarya@gmail.com', 1234567890, 'canteen1.jpg'),
(2, 'simsr', '', 'simsrCante', 'gate 3', '0000-00-00 00:00:00.000000', '23:49:00.000000', 1, 1, 'simsrCanteen@gmail.c', 123456789, NULL),
(4, 'simsrs', '', 'simsrCante', 'gate 3', '2023-03-12 13:13:55.745985', '23:56:00.000000', 1, 1, 'simsrsCanteen@gmail.', 1234567890, 'canteen4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` int(10) NOT NULL,
  `customerId` int(10) NOT NULL,
  `canteenId` int(10) NOT NULL,
  `foodId` int(10) NOT NULL,
  `cartAmount` int(10) NOT NULL,
  `cartNote` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartId`, `customerId`, `canteenId`, `foodId`, `cartAmount`, `cartNote`) VALUES
(3, 1, 1, 1, 1, ''),
(11, 2, 1, 1, 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerId` int(10) NOT NULL,
  `customerUserName` varchar(20) NOT NULL,
  `customerPassword` varchar(20) NOT NULL,
  `customerFirstName` varchar(20) NOT NULL,
  `customerLastName` varchar(20) NOT NULL,
  `customerEmail` varchar(100) NOT NULL,
  `customerGender` varchar(10) NOT NULL,
  `CustomerType` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customerId`, `customerUserName`, `customerPassword`, `customerFirstName`, `customerLastName`, `customerEmail`, `customerGender`, `CustomerType`) VALUES
(1, 'Bhavya', 'BHavya01', 'Bhavya', 'Thakkar', 'thakkarbhavya04@gmai', 'M', 'customer'),
(2, 'siddhi', 'Siddhi01$', 'siddhi', 'shah', 'siddhi.ds@somaiya.edu', 'F', 'customer'),
(3, 'Dimple', 'Dimple01$', 'Dimple', 'Shah', 'dimpleshah@gmail.com', 'F', 'admin'),
(6, 'siddhi28', 'siddhi10', 'Siddhi', 'Shah', 'siddhismile9920@gmai', 'F', 'customer'),
(7, 'siddhi28', 'siddhi28', 'Siddhi', 'Shah', 'siddhismile9920@gmai', 'F', 'customer'),
(8, 'siddhi', 'Siddhi28', 'Siddhi', 'Shah', 'siddhismile9920@gmai', 'F', 'customer'),
(9, 'Siddhi', 'siddhi28', 'Siddhi', 'Shah', 'siddhismile9920@gmail.com', 'F', 'customer'),
(14, 'shubham', '14910112', 'Shubham', 'Pawar', 'shubham.sp@gmail.com', 'M', 'customer'),
(17, 'shravan', 'shravan123', 'shravan', 'omayya', 'shravan.o@somaiya.edu', 'F', 'customer'),
(19, 'kaka123', '123456789', 'shravan', 'omayya', 'karan.j@somaiya.edu', 'M', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `foodId` int(10) NOT NULL,
  `canteenId` int(10) NOT NULL,
  `foodName` varchar(20) NOT NULL,
  `foodPrice` decimal(6,2) NOT NULL,
  `foodTodayAvailable` tinyint(10) NOT NULL,
  `foodPreOrderAvailable` tinyint(10) NOT NULL,
  `foodPic` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`foodId`, `canteenId`, `foodName`, `foodPrice`, `foodTodayAvailable`, `foodPreOrderAvailable`, `foodPic`) VALUES
(1, 1, 'noodles', '55.00', 1, 1, NULL),
(11, 1, 'samosa', '20.00', 1, 1, NULL),
(12, 1, 'samosa pav', '30.00', 1, 1, NULL),
(13, 1, 'vADA', '30.00', 1, 1, NULL),
(14, 1, 'chinese', '20.00', 1, 1, NULL),
(15, 1, 'chinese', '60.00', 1, 1, '15_1.png');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

CREATE TABLE `orderdetail` (
  `orderId` int(10) NOT NULL,
  `orderHeaderId` int(10) NOT NULL,
  `foodId` int(10) NOT NULL,
  `orderAmount` int(10) NOT NULL,
  `orderBuyPrice` decimal(6,2) NOT NULL,
  `orderNote` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderheader`
--

CREATE TABLE `orderheader` (
  `orderHeaderId` int(10) NOT NULL,
  `orderhHeaderReferenceCode` varchar(15) DEFAULT NULL,
  `customerId` int(10) NOT NULL,
  `canteenId` int(10) NOT NULL,
  `paymentId` int(10) NOT NULL,
  `orderHeaderOrderTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `orderHeaderPickupTime` datetime NOT NULL,
  `orderHeaderOrderStatus` varchar(10) NOT NULL,
  `orderHeaderFinishedTime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `paymentId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `paymentAmount` decimal(7,2) NOT NULL,
  `paymentDetail` text DEFAULT NULL,
  `paymentType` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `canteen`
--
ALTER TABLE `canteen`
  ADD PRIMARY KEY (`canteenId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`),
  ADD KEY `fk_cart_customerId` (`customerId`),
  ADD KEY `fk_cart_canteenId` (`canteenId`),
  ADD KEY `fk_cart_foodId` (`foodId`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerId`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`foodId`),
  ADD KEY `fk_food_canteenId` (`canteenId`);

--
-- Indexes for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `fk_orderdetail_orderHeaderId` (`orderHeaderId`),
  ADD KEY `fk_orderdetail_foodId` (`foodId`);

--
-- Indexes for table `orderheader`
--
ALTER TABLE `orderheader`
  ADD PRIMARY KEY (`orderHeaderId`),
  ADD KEY `fk_orderheader_customerId` (`customerId`),
  ADD KEY `fk_orderheader_canteenId` (`canteenId`),
  ADD KEY `fk_orderheader_paymentId` (`paymentId`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`paymentId`),
  ADD KEY `fk_customerId` (`customerId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `canteen`
--
ALTER TABLE `canteen`
  MODIFY `canteenId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customerId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `foodId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orderdetail`
--
ALTER TABLE `orderdetail`
  MODIFY `orderId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderheader`
--
ALTER TABLE `orderheader`
  MODIFY `orderHeaderId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `paymentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_canteenId` FOREIGN KEY (`canteenId`) REFERENCES `canteen` (`canteenId`),
  ADD CONSTRAINT `fk_cart_customerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`),
  ADD CONSTRAINT `fk_cart_foodId` FOREIGN KEY (`foodId`) REFERENCES `food` (`foodId`);

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `fk_food_canteenId` FOREIGN KEY (`canteenId`) REFERENCES `canteen` (`canteenId`);

--
-- Constraints for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `fk_orderdetail_foodId` FOREIGN KEY (`foodId`) REFERENCES `food` (`foodId`),
  ADD CONSTRAINT `fk_orderdetail_orderHeaderId` FOREIGN KEY (`orderHeaderId`) REFERENCES `orderheader` (`orderHeaderId`);

--
-- Constraints for table `orderheader`
--
ALTER TABLE `orderheader`
  ADD CONSTRAINT `fk_orderheader_canteenId` FOREIGN KEY (`canteenId`) REFERENCES `canteen` (`canteenId`),
  ADD CONSTRAINT `fk_orderheader_customerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`),
  ADD CONSTRAINT `fk_orderheader_paymentId` FOREIGN KEY (`paymentId`) REFERENCES `payment` (`paymentId`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_customerId` FOREIGN KEY (`customerId`) REFERENCES `customer` (`customerId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
