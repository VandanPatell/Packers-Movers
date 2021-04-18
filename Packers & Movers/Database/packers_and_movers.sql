-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2021 at 06:50 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `packers_and_movers`
--

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `b_id` int(11) NOT NULL,
  `b_contract_id` int(11) NOT NULL,
  `b_transporter_id` int(11) NOT NULL,
  `b_pickup_time_start` datetime NOT NULL COMMENT 'Pickup time (from- to)',
  `b_pickup_time_end` datetime NOT NULL,
  `b_delivery_time_start` datetime NOT NULL COMMENT 'Delivery time (from- to)',
  `b_delivery_time_end` datetime NOT NULL,
  `b_bid_amount` bigint(20) DEFAULT NULL,
  `b_message` text NOT NULL,
  `b_creation_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'bid creation time',
  `b_status` text DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`b_id`, `b_contract_id`, `b_transporter_id`, `b_pickup_time_start`, `b_pickup_time_end`, `b_delivery_time_start`, `b_delivery_time_end`, `b_bid_amount`, `b_message`, `b_creation_time`, `b_status`) VALUES
(2, 2, 1, '2021-04-06 11:25:00', '2021-04-07 11:25:00', '2021-04-08 11:25:00', '2021-04-09 11:25:00', 500, 'Requires Special Delivery For your Pets.\r\nI am a Dog Lover', '2021-04-06 11:26:15', 'approved'),
(3, 1, 1, '2021-04-05 17:01:00', '2021-04-09 15:01:00', '2021-04-06 15:01:00', '2021-04-06 15:01:00', 1500, 'I will do your task.', '2021-04-08 15:01:37', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `c_id` int(11) NOT NULL,
  `c_creator_id` int(11) NOT NULL,
  `c_creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `c_job_title` text NOT NULL,
  `c_job_description` text NOT NULL,
  `c_length` int(11) NOT NULL,
  `c_width` int(11) NOT NULL,
  `c_height` int(11) NOT NULL,
  `c_weight` int(11) NOT NULL,
  `c_expected_job_price` int(11) NOT NULL,
  `c_no_of_packages` int(11) NOT NULL,
  `c_job_category` text NOT NULL,
  `c_pickup_person` text NOT NULL,
  `c_pickup_contact` text NOT NULL,
  `c_pickup_address` text NOT NULL,
  `c_pickup_city` text NOT NULL,
  `c_pickup_state` text NOT NULL,
  `c_pickup_date` datetime NOT NULL,
  `c_delivery_person` text NOT NULL,
  `c_delivery_contact` text NOT NULL,
  `c_delivery_address` text NOT NULL,
  `c_delivery_city` text NOT NULL,
  `c_delivery_state` text NOT NULL,
  `c_delivery_date` datetime NOT NULL,
  `c_bid_start_time` datetime NOT NULL,
  `c_bid_end_time` datetime NOT NULL,
  `c_quotes_received` int(11) NOT NULL DEFAULT 0,
  `c_bid_winner` int(11) DEFAULT NULL,
  `c_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`c_id`, `c_creator_id`, `c_creation_date`, `c_job_title`, `c_job_description`, `c_length`, `c_width`, `c_height`, `c_weight`, `c_expected_job_price`, `c_no_of_packages`, `c_job_category`, `c_pickup_person`, `c_pickup_contact`, `c_pickup_address`, `c_pickup_city`, `c_pickup_state`, `c_pickup_date`, `c_delivery_person`, `c_delivery_contact`, `c_delivery_address`, `c_delivery_city`, `c_delivery_state`, `c_delivery_date`, `c_bid_start_time`, `c_bid_end_time`, `c_quotes_received`, `c_bid_winner`, `c_status`) VALUES
(1, 1, '2021-04-05 10:17:46', 'Hello', 'asdkvhj', 12, 12, 12, 12, 150, 5, 'Automobiles ATV Cars Motorcycles Trailers Trucks ', 'aasldjkb', 'ljkbkjb', 'ljsjkabdlajkds', 'BODHGAYA', 'BIHAR', '2021-04-05 01:08:00', 'asldjb', ';;alsdn', 'asbjkdlajksd', 'ALLEPPEY', 'KERALA', '2021-04-06 00:09:00', '2021-04-05 10:17:46', '2021-04-17 12:00:01', 1, 1, 5),
(2, 1, '2021-04-05 10:32:30', 'I need to send my dogs ', 'Poemrians', 15, 12, 12, 50, 200, 3, 'Small Pets ', 'Vandan Sojitra', '9408251428', 'My 1st Address', 'RAJKOT', 'GUJARAT', '2021-04-05 11:31:00', 'Vandan Sojitra', '9408251428', 'My 2nd Address', 'RAJKOT', 'GUJARAT', '2021-04-05 02:32:00', '2021-04-05 10:32:30', '2021-04-10 20:41:08', 1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `transporters`
--

CREATE TABLE `transporters` (
  `t_id` int(11) NOT NULL,
  `t_name` text NOT NULL,
  `t_password` text NOT NULL,
  `t_email` text NOT NULL,
  `t_contact` bigint(20) NOT NULL,
  `t_address` text NOT NULL,
  `t_emergency_contact` bigint(20) NOT NULL,
  `t_aadhar_no` bigint(20) NOT NULL,
  `t_license_no` text NOT NULL,
  `t_driving_permit` text NOT NULL,
  `t_vehicle_type` text NOT NULL,
  `t_vehicle_no` text NOT NULL,
  `t_max_cargo` smallint(6) NOT NULL,
  `t_successfull_bids` int(11) NOT NULL,
  `t_contracts_completed` int(11) NOT NULL,
  `t_last_login` datetime NOT NULL,
  `t_active_contract_id` int(11) NOT NULL,
  `t_contract_active_status` tinyint(1) NOT NULL DEFAULT 0,
  `t_approved` tinyint(1) NOT NULL,
  `t_balance` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transporters`
--

INSERT INTO `transporters` (`t_id`, `t_name`, `t_password`, `t_email`, `t_contact`, `t_address`, `t_emergency_contact`, `t_aadhar_no`, `t_license_no`, `t_driving_permit`, `t_vehicle_type`, `t_vehicle_no`, `t_max_cargo`, `t_successfull_bids`, `t_contracts_completed`, `t_last_login`, `t_active_contract_id`, `t_contract_active_status`, `t_approved`, `t_balance`) VALUES
(1, 'Vroomy Patel', '123', 'vroomy@movers.com', 9408251428, '12-ny street', 9408251428, 6549873215, '36A587AD', 'National', 'TATA XXXL', '12sad1', 1500, 1, 1, '2021-03-07 10:41:25', 1, 0, 1, 2700);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `u_name` text NOT NULL,
  `u_password` text NOT NULL,
  `u_email` text NOT NULL,
  `u_last_login` datetime NOT NULL,
  `u_contract_limit` tinyint(1) NOT NULL,
  `u_balance` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_password`, `u_email`, `u_last_login`, `u_contract_limit`, `u_balance`) VALUES
(1, 'Vandan Sojitra', '123', 'vandanp89@gmail.com', '2021-03-21 14:10:23', 0, 3000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`b_id`),
  ADD KEY `b_contract_id` (`b_contract_id`),
  ADD KEY `b_transporter_id` (`b_transporter_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `c_creator_id` (`c_creator_id`),
  ADD KEY `c_bid_winner` (`c_bid_winner`);

--
-- Indexes for table `transporters`
--
ALTER TABLE `transporters`
  ADD PRIMARY KEY (`t_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `b_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transporters`
--
ALTER TABLE `transporters`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`b_transporter_id`) REFERENCES `transporters` (`t_id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `bids_ibfk_3` FOREIGN KEY (`b_contract_id`) REFERENCES `contracts` (`c_id`);

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`c_creator_id`) REFERENCES `users` (`u_id`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`c_bid_winner`) REFERENCES `transporters` (`t_id`);

--
-- Constraints for table `transporters`
--
ALTER TABLE `transporters`
  ADD CONSTRAINT `transporters_ibfk_1` FOREIGN KEY (`t_active_contract_id`) REFERENCES `contracts` (`c_id`) ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
