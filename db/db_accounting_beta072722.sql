-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2022 at 03:28 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_purchasing_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `collection_details`
--

CREATE TABLE IF NOT EXISTS `collection_details` (
`collection_details_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL DEFAULT '0',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `billing_remarks` varchar(100) DEFAULT NULL,
  `particulars` varchar(200) DEFAULT NULL,
  `series_number` varchar(50) DEFAULT NULL,
  `defint` decimal(10,2) NOT NULL DEFAULT '0.00',
  `reference_no` varchar(100) DEFAULT NULL,
  `settlement_id` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `zero_rated` decimal(10,2) NOT NULL DEFAULT '0.00',
  `zero_rated_ecozone` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ewt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `old_series_no` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `collection_head`
--

CREATE TABLE IF NOT EXISTS `collection_head` (
`collection_id` int(11) NOT NULL,
  `collection_date` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `date_uploaded` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE IF NOT EXISTS `company` (
`company_id` int(11) NOT NULL,
  `company_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `or_remarks`
--

CREATE TABLE IF NOT EXISTS `or_remarks` (
  `or_no` varchar(50) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
`or_remarks_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

CREATE TABLE IF NOT EXISTS `participant` (
`participant_id` int(11) NOT NULL,
  `participant_name` varchar(200) DEFAULT NULL,
  `short_name` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `membership` varchar(100) DEFAULT NULL,
  `resource` varchar(200) DEFAULT NULL,
  `effective_date` varchar(20) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `participant_email` varchar(100) DEFAULT NULL,
  `trade_name` varchar(200) DEFAULT NULL,
  `tin` varchar(50) DEFAULT NULL,
  `registered_address` text,
  `wht_agent` varchar(20) DEFAULT NULL,
  `vat_zerorated` varchar(20) DEFAULT NULL,
  `income_tax_holiday` varchar(20) DEFAULT NULL,
  `documents_submitted` text,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_position` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `landline` varchar(100) DEFAULT NULL,
  `office_address` text,
  `billing_id` varchar(50) DEFAULT NULL,
  `settlement_id` varchar(50) DEFAULT NULL,
  `imported_by` int(11) NOT NULL DEFAULT '0',
  `date_imported` varchar(20) NOT NULL,
  `create_date` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `zip_code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
`payment_id` int(11) NOT NULL,
  `purchase_detail_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `payment_date` varchar(20) NOT NULL,
  `particulars` text NOT NULL,
  `purchase_mode` varchar(50) NOT NULL,
  `purchase_amount` decimal(10,2) NOT NULL,
  `vat` decimal(10,2) NOT NULL,
  `ewt` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_mode` varchar(20) NOT NULL,
  `pcv` varchar(200) NOT NULL,
  `check_no` varchar(50) NOT NULL,
  `cv_no` varchar(50) NOT NULL,
  `check_date` varchar(20) NOT NULL,
  `create_date` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE IF NOT EXISTS `payment_details` (
`payment_details_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL DEFAULT '0',
  `purchase_details_id` int(11) NOT NULL DEFAULT '0',
  `purchase_mode` varchar(100) DEFAULT NULL,
  `purchase_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ewt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `short_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_head`
--

CREATE TABLE IF NOT EXISTS `payment_head` (
`payment_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `payment_date` varchar(20) NOT NULL,
  `particulars` text NOT NULL,
  `total_purchase` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_vat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_ewt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_mode` varchar(20) NOT NULL,
  `pcv` varchar(200) NOT NULL,
  `check_no` varchar(50) NOT NULL,
  `cv_no` varchar(50) NOT NULL,
  `check_date` varchar(20) NOT NULL,
  `create_date` varchar(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_identifier` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_transaction_details`
--

CREATE TABLE IF NOT EXISTS `purchase_transaction_details` (
`purchase_detail_id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL DEFAULT '0',
  `billing_statement_series` varchar(100) DEFAULT NULL,
  `participant_id` int(11) NOT NULL DEFAULT '0',
  `short_name` varchar(50) DEFAULT NULL,
  `facility_type` varchar(50) DEFAULT NULL,
  `wht_agent` varchar(20) DEFAULT NULL,
  `non_vatable` varchar(20) DEFAULT NULL,
  `zero_rated` varchar(20) DEFAULT NULL,
  `vatables_purchases` decimal(10,2) NOT NULL DEFAULT '0.00',
  `zero_rated_purchases` decimal(10,2) NOT NULL DEFAULT '0.00',
  `zero_rated_ecozones` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_on_purchases` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ewt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `billing_id` varchar(50) DEFAULT NULL,
  `ith_tag` varchar(50) DEFAULT NULL,
  `print_counter` int(11) NOT NULL DEFAULT '0',
  `serial_no` text,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_transaction_head`
--

CREATE TABLE IF NOT EXISTS `purchase_transaction_head` (
`purchase_id` int(11) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `transaction_date` varchar(20) DEFAULT NULL,
  `billing_from` varchar(20) DEFAULT NULL,
  `billing_to` varchar(20) DEFAULT NULL,
  `due_date` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `create_date` varchar(20) DEFAULT NULL,
  `saved` int(11) NOT NULL DEFAULT '0',
  `adjustment` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_transaction_details`
--

CREATE TABLE IF NOT EXISTS `sales_transaction_details` (
`sales_detail_id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL DEFAULT '0',
  `participant_id` int(11) NOT NULL DEFAULT '0',
  `short_name` varchar(50) DEFAULT NULL,
  `facility_type` varchar(50) DEFAULT NULL,
  `wht_agent` varchar(20) DEFAULT NULL,
  `non_vatable` varchar(20) DEFAULT NULL,
  `zero_rated` varchar(20) DEFAULT NULL,
  `vatable_sales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `zero_rated_sales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `zero_rated_ecozones` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vat_on_sales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ewt` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `company_name` text,
  `ith_tag` varchar(100) DEFAULT NULL,
  `billing_id` varchar(100) DEFAULT NULL,
  `serial_no` text,
  `print_counter` int(11) NOT NULL DEFAULT '0',
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `item_no` int(11) NOT NULL DEFAULT '0',
  `old_series_no` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales_transaction_head`
--

CREATE TABLE IF NOT EXISTS `sales_transaction_head` (
`sales_id` int(11) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `transaction_date` varchar(20) DEFAULT NULL,
  `billing_from` varchar(20) DEFAULT NULL,
  `billing_to` varchar(20) DEFAULT NULL,
  `due_date` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `create_date` varchar(20) DEFAULT NULL,
  `saved` int(11) NOT NULL DEFAULT '0',
  `adjustment` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subparticipant`
--

CREATE TABLE IF NOT EXISTS `subparticipant` (
`subparticipant_id` int(11) NOT NULL,
  `participant_id` int(11) NOT NULL DEFAULT '0',
  `sub_participant` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`user_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `fullname`, `position`, `password`, `department`) VALUES
(1, 'admin', 'Admin', 'admin', 'admin', 'billing');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collection_details`
--
ALTER TABLE `collection_details`
 ADD PRIMARY KEY (`collection_details_id`);

--
-- Indexes for table `collection_head`
--
ALTER TABLE `collection_head`
 ADD PRIMARY KEY (`collection_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
 ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `or_remarks`
--
ALTER TABLE `or_remarks`
 ADD PRIMARY KEY (`or_remarks_id`);

--
-- Indexes for table `participant`
--
ALTER TABLE `participant`
 ADD PRIMARY KEY (`participant_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
 ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
 ADD PRIMARY KEY (`payment_details_id`);

--
-- Indexes for table `payment_head`
--
ALTER TABLE `payment_head`
 ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `purchase_transaction_details`
--
ALTER TABLE `purchase_transaction_details`
 ADD PRIMARY KEY (`purchase_detail_id`);

--
-- Indexes for table `purchase_transaction_head`
--
ALTER TABLE `purchase_transaction_head`
 ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `sales_transaction_details`
--
ALTER TABLE `sales_transaction_details`
 ADD PRIMARY KEY (`sales_detail_id`);

--
-- Indexes for table `sales_transaction_head`
--
ALTER TABLE `sales_transaction_head`
 ADD PRIMARY KEY (`sales_id`);

--
-- Indexes for table `subparticipant`
--
ALTER TABLE `subparticipant`
 ADD PRIMARY KEY (`subparticipant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collection_details`
--
ALTER TABLE `collection_details`
MODIFY `collection_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `collection_head`
--
ALTER TABLE `collection_head`
MODIFY `collection_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `or_remarks`
--
ALTER TABLE `or_remarks`
MODIFY `or_remarks_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `participant`
--
ALTER TABLE `participant`
MODIFY `participant_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
MODIFY `payment_details_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment_head`
--
ALTER TABLE `payment_head`
MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_transaction_details`
--
ALTER TABLE `purchase_transaction_details`
MODIFY `purchase_detail_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_transaction_head`
--
ALTER TABLE `purchase_transaction_head`
MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_transaction_details`
--
ALTER TABLE `sales_transaction_details`
MODIFY `sales_detail_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sales_transaction_head`
--
ALTER TABLE `sales_transaction_head`
MODIFY `sales_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subparticipant`
--
ALTER TABLE `subparticipant`
MODIFY `subparticipant_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
