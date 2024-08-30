-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2024 at 12:36 PM
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
-- Database: `bcar`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `make` varchar(50) DEFAULT NULL,
  `reg_year` int(4) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `people_capacity` int(11) DEFAULT NULL,
  `suitcase_capacity` int(11) DEFAULT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `car_type` enum('sedan','suv','truck','van') NOT NULL,
  `fuel_type` enum('petrol','diesel','electric','hybrid') NOT NULL,
  `avg_consumption` decimal(5,2) DEFAULT NULL,
  `horsepower` int(11) DEFAULT NULL,
  `length` decimal(6,2) DEFAULT NULL,
  `width` decimal(6,2) DEFAULT NULL,
  `gear_type` varchar(20) DEFAULT NULL,
  `conditions` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_2` varchar(255) DEFAULT NULL,
  `image_3` varchar(255) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `location_id` int(11) NOT NULL,
  `pickup_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `model`, `make`, `reg_year`, `color`, `people_capacity`, `suitcase_capacity`, `price_per_day`, `total_price`, `car_type`, `fuel_type`, `avg_consumption`, `horsepower`, `length`, `width`, `gear_type`, `conditions`, `description`, `image`, `image_2`, `image_3`, `available`, `location_id`, `pickup_location`) VALUES
(1, 'Toyota Corolla', 'Toyota', 2021, 'Red', 5, 3, 250.00, 1250.00, 'sedan', 'petrol', 7.50, 140, 4.60, 1.80, 'Automatic', 'No pets allowed', 'Comfortable and fuel-efficient sedan.', 'images/toyota_corolla.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(2, 'Ford Explorer', 'Ford', 2019, 'Blue', 7, 5, 350.00, 1750.00, 'suv', 'diesel', 9.00, 300, 5.00, 2.00, 'Automatic', 'No smoking', 'Spacious and powerful SUV.', 'images/ford_explorer.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(3, 'Tesla Model S', 'Tesla', 2022, 'White', 5, 4, 500.00, 2500.00, 'sedan', 'electric', 15.00, 670, 4.70, 1.90, 'Automatic', 'Charge every 300 km', 'High-performance electric car.', 'images/tesla_model_s.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(4, 'Honda Civic', 'Honda', 2020, 'Silver', 5, 3, 220.00, 1100.00, 'sedan', 'petrol', 7.00, 160, 4.60, 1.80, 'Automatic', 'No food allowed', 'Reliable and affordable sedan.', 'images/honda_civic.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(5, 'Audi A4', 'Audi', 2021, 'Black', 5, 3, 300.00, 1500.00, 'sedan', 'petrol', 7.80, 190, 4.70, 1.85, 'Automatic', 'No pets allowed', 'Luxury sedan with advanced features.', 'images/Audi_A4.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(6, 'Audi A6', 'Audi', 2020, 'White', 5, 3, 400.00, 2000.00, 'sedan', 'diesel', 7.80, 250, 4.95, 1.90, 'Automatic', 'No pets allowed', 'Premium sedan with exceptional performance.', 'images/Audi_A6.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(7, 'Audi Q5', 'Audi', 2022, 'Grey', 5, 3, 450.00, 2250.00, 'suv', 'petrol', 8.00, 280, 4.90, 1.90, 'Automatic', 'No pets allowed', 'Versatile SUV with modern technology.', 'images/Audi_Q5.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(8, 'BMW 3 Series', 'BMW', 2021, 'Blue', 5, 3, 320.00, 1600.00, 'sedan', 'petrol', 7.20, 180, 4.70, 1.80, 'Automatic', 'No pets allowed', 'Elegant and sporty sedan.', 'images/BMW_3_Series.png', NULL, NULL, 1, 1, 'Your Pickup Location'),
(9, 'BMW 5 Series', 'BMW', 2019, 'Black', 5, 3, 420.00, 2100.00, 'sedan', 'diesel', 7.50, 220, 4.95, 1.85, 'Automatic', 'No pets allowed', 'Luxurious sedan with cutting-edge features.', 'images/BMW_5_Series.png', NULL, NULL, 1, 1, 'Your Pickup Location'),
(10, 'BMW X3', 'BMW', 2021, 'Red', 5, 3, 380.00, 1900.00, 'suv', 'petrol', 8.50, 250, 4.75, 1.85, 'Automatic', 'No pets allowed', 'Compact SUV with dynamic performance.', 'images/BMW_X3.jpeg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(11, 'Chevrolet Equinox', 'Chevrolet', 2019, 'White', 5, 3, 350.00, 1750.00, 'suv', 'petrol', 8.00, 200, 4.80, 1.85, 'Automatic', 'No pets allowed', 'Comfortable and reliable SUV.', 'images/Chevrolet_Equinox.jpeg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(12, 'Chevrolet Malibu', 'Chevrolet', 2020, 'Silver', 5, 3, 300.00, 1500.00, 'sedan', 'petrol', 7.80, 190, 4.75, 1.85, 'Automatic', 'No pets allowed', 'Smooth and efficient sedan.', 'images/Chevrolet_Malibu.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(13, 'Ford Edge', 'Ford', 2021, 'Blue', 5, 3, 380.00, 1900.00, 'suv', 'diesel', 8.50, 220, 4.95, 1.85, 'Automatic', 'No pets allowed', 'Powerful and spacious SUV.', 'images/Ford_Edge.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(14, 'Ford Mustang', 'Ford', 2020, 'Red', 4, 2, 450.00, 2250.00, '', 'petrol', 12.00, 450, 4.78, 1.91, 'Manual', 'No pets allowed', 'Iconic sports car with legendary performance.', 'images/Ford_Mustang.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(15, 'Honda Accord', 'Honda', 2021, 'Blue', 5, 3, 320.00, 1600.00, 'sedan', 'petrol', 7.00, 180, 4.90, 1.85, 'Automatic', 'No pets allowed', 'Reliable and stylish sedan.', 'images/Honda_Accord.jpeg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(16, 'Mercedes-Benz C-Class', 'Mercedes-Benz', 2021, 'Black', 5, 3, 350.00, 1750.00, 'sedan', 'diesel', 6.50, 200, 4.75, 1.84, 'Automatic', 'No pets allowed', 'Luxury sedan with advanced technology.', 'images/Mercedes-Benz_C-Class.png', NULL, NULL, 1, 1, 'Your Pickup Location'),
(17, 'Toyota Camry', 'Toyota', 2021, 'Green', 5, 3, 300.00, 1500.00, 'sedan', 'hybrid', 4.50, 176, 4.88, 1.84, 'Automatic', 'No pets allowed', 'Efficient and eco-friendly sedan.', 'images/Toyota_Camry.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(18, 'Toyota Highlander', 'Toyota', 2020, 'Blue', 7, 5, 420.00, 2100.00, 'suv', 'petrol', 10.00, 295, 4.99, 1.93, 'Automatic', 'No pets allowed', 'Spacious and comfortable SUV.', 'images/Toyota_Highlander.jpg', NULL, NULL, 1, 1, 'Your Pickup Location'),
(111783425, 'civil', 'Audi', 21, 'red', 11, 9, 14.00, NULL, 'sedan', 'hybrid', 0.11, 555, 66.00, 200.00, '', '222', 'very nice', 'car0111783425img1.jpg', 'car0111783425img2.jpg', 'car0111783425img3.png', 1, 1, NULL),
(384754200, 'civil', 'Audi', 21, 'red', 11, 9, 14.00, NULL, 'sedan', 'hybrid', 0.11, 555, 66.00, 200.00, '', '222', 'very nice', 'car0384754200img1.jpg', 'car0384754200img2.jpg', 'car0384754200img3.png', 1, 1, NULL),
(450171654, 'civil', 'Honda', 14, 'red', 12, 11, 10.00, NULL, 'sedan', 'electric', 0.12, 9, 0.12, 0.10, 'Manual', 'ddddd', 'very usfull', 'car0450171654img1.jpg', 'car0450171654img2.jpg', 'car0450171654img3.png', 1, 1, NULL),
(639511786, 'civil', 'Audi', 21, 'red', 11, 9, 14.00, NULL, 'sedan', 'hybrid', 0.11, 555, 66.00, 200.00, '', '222', 'very nice', 'car0639511786img1.jpg', 'car0639511786img2.jpg', 'car0639511786img3.png', 1, 1, NULL),
(1109611265, 'civil', 'Audi', 21, 'red', 11, 9, 14.00, NULL, 'sedan', 'hybrid', 0.11, 555, 66.00, 200.00, '', '222', 'very nice', 'car1109611265img1.jpg', 'car1109611265img2.jpg', 'car1109611265img3.png', 1, 1, NULL),
(1839142384, 'civil', 'Audi', 21, 'red', 11, 9, 14.00, NULL, 'sedan', 'hybrid', 0.11, 555, 66.00, 200.00, '', '222', 'very nice', 'car1839142384img1.jpg', 'car1839142384img2.jpg', 'car1839142384img3.png', 1, 1, NULL),
(1868556507, 'civil', 'Honda', 14, 'red', 12, 11, 10.00, NULL, 'sedan', 'electric', 0.12, 9, 0.12, 0.10, 'Manual', 'ddddd', 'very usfull', 'car1868556507img1.jpg', 'car1868556507img2.jpg', 'car1868556507img3.png', 1, 1, NULL),
(1976258675, 'civil', 'Honda', 14, 'red', 12, 11, 10.00, NULL, 'sedan', 'electric', 0.12, 9, 0.12, 0.10, 'Manual', 'ddddd', 'very usfull', 'car1976258675img1.jpg', 'car1976258675img2.jpg', 'car1976258675img3.png', 1, 1, NULL),
(1985651962, 'civil', 'Audi', 21, 'red', 11, 9, 14.00, NULL, 'sedan', 'hybrid', 0.11, 555, 66.00, 200.00, '', '222', 'very nice', 'car1985651962img1.jpg', 'car1985651962img2.jpg', 'car1985651962img3.png', 1, 1, NULL),
(2045197411, 'civil', 'Audi', 21, 'red', 11, 9, 14.00, NULL, 'sedan', 'hybrid', 0.11, 555, 66.00, 200.00, '', '222', 'very nice', 'car2045197411img1.jpg', 'car2045197411img2.jpg', 'car2045197411img3.png', 1, 1, NULL),
(2147483647, 'civil', 'Toyota', 4, 'red', 10, 10, 11.00, NULL, 'sedan', 'petrol', 0.03, 9, 0.08, 0.06, '99999', 'very good ', 'very good ', 'car7387801093img3.jpeg', NULL, NULL, 1, 1, 'Your Pickup Location');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `flat_house_no` varchar(50) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `dob` date NOT NULL DEFAULT curdate(),
  `id_number` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `cc_number` varchar(20) NOT NULL,
  `cc_expiration` date NOT NULL DEFAULT '2025-12-31',
  `cc_name` varchar(100) NOT NULL,
  `cc_bank` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `username`, `password`, `name`, `flat_house_no`, `street`, `city`, `country`, `dob`, `id_number`, `email`, `telephone`, `cc_number`, `cc_expiration`, `cc_name`, `cc_bank`) VALUES
(1, 'SanaaObied', '12345', 'Sanaa Obied', 'Flat 20', 'Second Street', 'CityName', 'CountryName', '1995-02-02', 'ID654321', 'sanaa@example.com', '987654321', '4444333322221111', '2025-12-31', 'Sanaa Obied', 'BankName'),
(2, 'HassanAbdul', '12345', 'Hassan Abdul', 'Flat 10', 'Main Street', 'CityName', 'CountryName', '1990-01-01', 'ID123456', 'hassan@example.com', '123456789', '1111222233334444', '2025-12-31', 'Hassan Abdul', 'BankName'),
(3, 'JohnDoe', 'hashedpassword', 'John Doe', 'Flat 30', 'Third Street', 'CityName', 'CountryName', '1980-03-03', 'ID789012', 'john@example.com', '555666777', '3333444455556666', '2026-12-31', 'John Doe', 'BankName'),
(4, 'JohnDoe', 'hashedpassword', '', '', '', '', '', '2024-06-19', '', '', '', '', '2025-12-31', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `property_number` varchar(255) DEFAULT NULL,
  `street_name` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `address`, `telephone`, `property_number`, `street_name`, `city`, `postal_code`, `country`) VALUES
(1, 'Birzeit', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Ramallah', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Jerusalem', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Nablus', 'Abu-Falah', '972592845459', NULL, NULL, NULL, NULL, NULL),
(6, 'Nablus', 'Abu-Falah', '972592845459', NULL, NULL, NULL, NULL, NULL),
(7, 'Nablus', 'Abu-Falah', '972592845459', NULL, NULL, NULL, NULL, NULL),
(8, 'Nablus', 'Abu-Falah', '972592845459', NULL, NULL, NULL, NULL, NULL),
(9, 'Nablus', 'Abu-Falah', '972592845459', '8888888888', 'samaa', 'abo-falah', '88888', 'trky'),
(10, 'Nablus', 'Abu-Falah', '972592845459', '8888888888', 'samaa', 'abo-falah', '88888', 'trky'),
(11, 'Nablus', 'Abu-Falah', '972592845459', '8888888888', 'samaa', 'abo-falah', '88888', 'trky'),
(12, 'Nablus', 'ramallah', '972592845459', '8888888888', 'samaa', 'abo-falah', '88888', 'trky');

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `rental_start` date NOT NULL,
  `rental_end` date NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `return_location` varchar(255) DEFAULT NULL,
  `rental_status` varchar(50) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`id`, `car_id`, `rental_start`, `rental_end`, `user_id`, `return_location`, `rental_status`) VALUES
(1, 1, '2024-06-01', '2024-06-10', 'user123', 'Ramallah', 'active'),
(2, 2, '2024-06-05', '2024-06-15', 'user123', 'Ramallah', 'active'),
(3, 3, '2024-06-10', '2024-06-20', 'user123', 'Ramallah', 'active'),
(4, 4, '2024-06-15', '2024-06-25', 'user123', 'Ramallah', 'returning');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `address_flat` varchar(50) DEFAULT NULL,
  `address_street` varchar(50) DEFAULT NULL,
  `address_city` varchar(50) DEFAULT NULL,
  `address_country` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `credit_card_number` varchar(20) DEFAULT NULL,
  `credit_card_expiration_date` date DEFAULT NULL,
  `credit_card_name` varchar(255) DEFAULT NULL,
  `bank_issued` varchar(255) DEFAULT NULL,
  `type` enum('Customer','Manager') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `password`, `address_flat`, `address_street`, `address_city`, `address_country`, `date_of_birth`, `id_number`, `email`, `telephone`, `credit_card_number`, `credit_card_expiration_date`, `credit_card_name`, `bank_issued`, `type`) VALUES
('', 'Sanaa', NULL, 'Ramallah', 'abuFalah', 'aaa', 'fff', '2024-05-27', '123455', 'sana.obied@gmail.com', '972592845459', '4444444444', '2024-06-03', 'SanaaObied', 'dddddddddd', NULL),
('2185478207', 'Sanaa', '12345678', 'Ramallah', 'abuFalah', 'aaa', 'fff', '2024-06-12', '123455', 'sana.obied@gmail.com', '00008888888888', '88888888', '2024-06-04', 'SanaaObied', 'Newbanm', NULL),
('5662649673', 'Sanaa', '123456789', 'Ramallah', 'abuFalah', 'aaa', 'fff', '2024-06-03', 'Jane Smith', 'sana.obied@gmail.com', '972592845459', '333333333', '2024-05-27', '5555', 'dddddddddd', NULL),
('9106428857', 'Honday3', '123456789', 'Ramallah', 'abuFalah', 'aaa', 'fff', '2024-06-04', '123455', 'sana.obied@gmail.com', '972592845459', 'dddddd', '2024-06-04', 'SanaaObied', 'dddddddddd', NULL),
('9182035351', 'Sanaa', '12345678', 'Ramallah', 'abuFalah', 'aaa', 'fff', '2024-06-04', '123455', 'sana.obied@gmail.com', '00008888888888', 'Sanaa', '2024-06-18', 'SanaaObied', 'Newbanm', NULL),
('manager123', 'Jane Doe', '4403e52d8a2e672add5fded33d05cb65', 'Flat 30', '789 Another Street', 'Newtown', 'Newcountry', '1976-06-20', '9876543210', 'jane.doe@example.com', '555-6789', '4333333333333333', '2027-07-31', 'Jane Doe', 'Newbank', 'Manager'),
('user123', 'John Smith', '67bc4a4d0c80c103946d42acc3b2be1b', 'Apt 101', '456 Oak Avenue', 'Newville', 'Newcountry', '1985-03-15', '9876543210', 'john.smith@example.com', '555-6789', '4333333333333333', '2027-07-31', 'John Smith', 'Newbanh', 'Customer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`) VALUES
(1, 'Sanaa', '0', 'johndoe@example.com', '123-456-7890');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2147483648;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`);

--
-- Constraints for table `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
