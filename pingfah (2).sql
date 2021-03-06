-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2020 at 07:59 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pingfah`
--

-- --------------------------------------------------------

--
-- Table structure for table `air_gal`
--

CREATE TABLE `air_gal` (
  `gal_id` int(11) NOT NULL,
  `gal_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `appeal`
--

CREATE TABLE `appeal` (
  `appeal_id` int(11) NOT NULL,
  `room_id` varchar(200) NOT NULL,
  `appeal_topic` varchar(200) NOT NULL,
  `appeal_detail` varchar(200) NOT NULL,
  `appeal_date` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cost`
--

CREATE TABLE `cost` (
  `cost_id` int(11) NOT NULL,
  `room_id` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `cost_status` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `room_cost` float(100,2) NOT NULL,
  `water_bill` float(100,2) NOT NULL,
  `elec_bill` float(100,2) NOT NULL,
  `cable_charge` float(100,2) NOT NULL,
  `fines` float(100,2) NOT NULL,
  `total` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `daily`
--

CREATE TABLE `daily` (
  `daily_id` int(11) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `id_card` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `tel` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `check_in` varchar(200) NOT NULL,
  `check_out` varchar(200) NOT NULL,
  `people` varchar(2) NOT NULL,
  `air_room` int(2) NOT NULL,
  `fan_room` int(2) NOT NULL,
  `daily_status` varchar(200) NOT NULL,
  `payment_datebefore` varchar(200) NOT NULL,
  `payment_img` varchar(200) DEFAULT NULL,
  `room_select` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dailycost`
--

CREATE TABLE `dailycost` (
  `dailycost_id` int(11) NOT NULL,
  `room_id` varchar(200) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `id_card` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `tel` varchar(200) NOT NULL,
  `check_in` varchar(200) NOT NULL,
  `check_out` varchar(200) NOT NULL,
  `price_total` float(11,2) NOT NULL,
  `daily_status` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  `payment_img` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `title_name` varchar(200) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `nickname` varchar(200) NOT NULL,
  `position` varchar(200) NOT NULL,
  `id_card` varchar(200) NOT NULL,
  `tel` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `id_line` varchar(200) NOT NULL,
  `birthday` varchar(200) NOT NULL,
  `age` varchar(200) NOT NULL,
  `race` varchar(200) NOT NULL,
  `nationality` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `pic_idcard` varchar(200) NOT NULL,
  `pic_home` varchar(200) NOT NULL,
  `profile_img` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `title_name`, `firstname`, `lastname`, `nickname`, `position`, `id_card`, `tel`, `email`, `id_line`, `birthday`, `age`, `race`, `nationality`, `address`, `pic_idcard`, `pic_home`, `profile_img`, `username`, `password`) VALUES
(27, '?????????', '????????????', '?????????????????????????????????', '?????????', '?????????????????????', '1509966011521', '0956722914', 'blackfrostier@gmail.com', 'blackfrostier', '1998-12-21', '21', '?????????', '?????????', '140/40 ???????????? 2 ???. ???????????????????????????????????? ???.??????????????? ???.??????????????????????????? 50000', '404365.jpg', '?????????????????????????????????????????????????????????????????????.jpg', '115774657_3345395698845713_2446905765721859802_o.jpg', 'nwpgm82', 'ef16b5fe40a49934ad40c0b059090b14');

-- --------------------------------------------------------

--
-- Table structure for table `fan_gal`
--

CREATE TABLE `fan_gal` (
  `gal_id` int(11) NOT NULL,
  `gal_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL,
  `gallery_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `username` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `level` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`username`, `name`, `password`, `email`, `level`) VALUES
('admin', 'admin', '81dc9bdb52d04dc20036dbd8313ed055', '', 'admin'),
('nwpgm82', '????????????', 'ef16b5fe40a49934ad40c0b059090b14', 'blackfrostier@gmail.com', 'employee');

-- --------------------------------------------------------

--
-- Table structure for table `package`
--

CREATE TABLE `package` (
  `package_id` int(11) NOT NULL,
  `package_num` varchar(200) NOT NULL,
  `package_company` varchar(200) NOT NULL,
  `package_arrived` varchar(200) NOT NULL,
  `package_status` varchar(200) NOT NULL,
  `package_name` varchar(200) NOT NULL,
  `package_room` varchar(200) NOT NULL,
  `package_received` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `repair`
--

CREATE TABLE `repair` (
  `repair_id` int(11) NOT NULL,
  `room_id` varchar(200) NOT NULL,
  `repair_appliance` varchar(200) NOT NULL,
  `repair_category` varchar(200) NOT NULL,
  `repair_detail` varchar(200) NOT NULL,
  `repair_date` varchar(200) NOT NULL,
  `repair_successdate` varchar(200) DEFAULT NULL,
  `repair_status` varchar(200) NOT NULL,
  `repair_income` float(100,2) DEFAULT NULL,
  `repair_expenses` float(100,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roomdetail`
--

CREATE TABLE `roomdetail` (
  `type` varchar(255) NOT NULL,
  `water_bill` float(255,2) NOT NULL,
  `elec_bill` float(255,2) NOT NULL,
  `cable_charge` float(255,2) NOT NULL,
  `fines` float(255,2) NOT NULL,
  `price` float(255,2) NOT NULL,
  `daily_price` float(255,2) NOT NULL,
  `sv_fan` varchar(2) DEFAULT NULL,
  `sv_air` varchar(2) DEFAULT NULL,
  `sv_wifi` varchar(2) DEFAULT NULL,
  `sv_furniture` varchar(2) DEFAULT NULL,
  `sv_readtable` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roomdetail`
--

INSERT INTO `roomdetail` (`type`, `water_bill`, `elec_bill`, `cable_charge`, `fines`, `price`, `daily_price`, `sv_fan`, `sv_air`, `sv_wifi`, `sv_furniture`, `sv_readtable`) VALUES
('???????????????', 80.00, 7.50, 105.00, 150.00, 2300.00, 250.00, 'on', '', 'on', 'on', 'on'),
('????????????', 80.00, 7.50, 105.00, 150.00, 2700.00, 350.00, '', 'on', 'on', 'on', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `roomlist`
--

CREATE TABLE `roomlist` (
  `room_id` varchar(200) NOT NULL,
  `room_type` varchar(200) NOT NULL,
  `room_status` varchar(200) NOT NULL,
  `come` varchar(200) NOT NULL,
  `check_in` varchar(200) NOT NULL,
  `check_out` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roommember`
--

CREATE TABLE `roommember` (
  `room_member` varchar(10) NOT NULL,
  `name_title` varchar(10) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `nickname` varchar(200) NOT NULL,
  `id_card` varchar(13) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(200) NOT NULL,
  `birthday` varchar(200) NOT NULL,
  `age` int(11) NOT NULL,
  `race` varchar(200) NOT NULL,
  `nationality` varchar(200) NOT NULL,
  `job` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `pic_idcard` text NOT NULL,
  `pic_home` text NOT NULL,
  `id_line` varchar(200) NOT NULL,
  `name_title2` varchar(10) DEFAULT NULL,
  `firstname2` varchar(200) DEFAULT NULL,
  `lastname2` varchar(200) DEFAULT NULL,
  `nickname2` varchar(200) DEFAULT NULL,
  `id_card2` varchar(13) DEFAULT NULL,
  `phone2` varchar(10) DEFAULT NULL,
  `email2` varchar(200) DEFAULT NULL,
  `birthday2` varchar(200) DEFAULT NULL,
  `age2` int(11) DEFAULT NULL,
  `race2` varchar(200) DEFAULT NULL,
  `nationality2` varchar(200) DEFAULT NULL,
  `job2` varchar(200) DEFAULT NULL,
  `address2` varchar(200) DEFAULT NULL,
  `pic_idcard2` text DEFAULT NULL,
  `pic_home2` text DEFAULT NULL,
  `id_line2` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rule`
--

CREATE TABLE `rule` (
  `rule_detail` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rule`
--

INSERT INTO `rule` (`rule_detail`) VALUES
('1. ???????????????????????????????????????????????????????????? 6 ??????????????? ??????????????????????????????????????????????????????????????????????????????????????????????????? 1 ??????????????? ??????????????????????????????????????????????????????????????????????????????????????????????????????\r\n2. ???????????????????????????????????????????????????????????????????????????????????? ?????????????????? ???????????????????????? ?????????????????????????????? 5 ????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????? 150 ????????? ?????????????????????????????????????????????????????????????????? 15 ????????? ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????\r\n3. ????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????? ???????????????????????????????????????????????????????????????????????????????????????????????? ??? ????????????????????????\r\n4. ???????????????????????? ???????????? ???????????????????????????????????? ??????????????? ????????????????????????????????? ????????????????????????????????????????????? ??? ????????????????????????????????????????????????????????????????????????????????????????????????\r\n5. ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????\r\n6.??????????????????????????????????????????????????????????????? ????????????????????????????????????????????????\r\n7. ???????????????????????????????????? ??????????????????????????? ????????????????????????????????????????????????????????????????????????????????????????????????????????????\r\n8. ??????????????????????????????????????????????????????????????????????????????????????????????????????\r\n9. ????????????????????????????????? ?????????????????? ?????????????????????????????? ???????????????????????????????????? ????????????????????????????????????????????????????????????????????? ????????????????????????????????????????????????????????????????????????????????? 50 ?????????\r\n10. ???????????????????????????????????????????????????????????? ???????????? 100 ????????? / ????????? ????????????????????????????????????????????????????????? ???????????? 200 ????????? / ??????\r\n11. ????????????????????????????????????????????? ??????????????????????????????????????????????????????????????????????????????\r\n12. ????????????????????????????????? ???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????\r\n13. ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????? ??? ??????????????????????????????????????????????????????????????????\r\n14. ???????????????????????????????????????????????????????????? ????????????????????????????????? ???????????????????????? ????????????????????????????????? ????????????????????????????????????????????? ????????????????????????????????? ??????????????????????????? ??????????????? ???????????????????????????????????????????????? ??????????????????????????????????????????????????????????????????????????????????????????????????????????????? ????????????????????????????????????????????????????????????????????????????????????????????????????????? ????????????????????????????????? ????????????????????????????????????????????????????????????????????????????????????????????? ??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????-????????????????????? ?????????????????????????????????????????????????????????\r\n15. ??????????????????????????????????????????????????????????????????????????? 24 ????????????????????? ????????????????????????????????????????????????????????????????????????????????? 20.00 ???. ???????????????????????????????????????????????????????????????????????????????????????????????????????????????\r\n16. ??????????????????????????????????????????????????????????????????????????????????????????????????? ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????? 24 ????????????????????? ?????????????????????????????????????????? ??? ???????????????????????????????????????????????? ???????????????????????????????????????????????????????????????????????????\r\n17. ????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????\r\n18. ?????????????????????????????????????????????????????????????????????????????? 2 ?????? ??????????????????????????????????????????????????????????????????????????????????????????????????????????????? ??????????????????????????????????????? 200 ????????? / ????????? ???????????????????????????????????????????????????????????????????????????\r\n** ????????????????????????????????????????????????????????????????????????????????????????????? ?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????? ??? **');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `air_gal`
--
ALTER TABLE `air_gal`
  ADD PRIMARY KEY (`gal_id`);

--
-- Indexes for table `appeal`
--
ALTER TABLE `appeal`
  ADD PRIMARY KEY (`appeal_id`);

--
-- Indexes for table `cost`
--
ALTER TABLE `cost`
  ADD PRIMARY KEY (`cost_id`);

--
-- Indexes for table `daily`
--
ALTER TABLE `daily`
  ADD PRIMARY KEY (`daily_id`);

--
-- Indexes for table `dailycost`
--
ALTER TABLE `dailycost`
  ADD PRIMARY KEY (`dailycost_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fan_gal`
--
ALTER TABLE `fan_gal`
  ADD PRIMARY KEY (`gal_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `package`
--
ALTER TABLE `package`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (`repair_id`);

--
-- Indexes for table `roomdetail`
--
ALTER TABLE `roomdetail`
  ADD PRIMARY KEY (`type`);

--
-- Indexes for table `roomlist`
--
ALTER TABLE `roomlist`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `roommember`
--
ALTER TABLE `roommember`
  ADD PRIMARY KEY (`room_member`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `air_gal`
--
ALTER TABLE `air_gal`
  MODIFY `gal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appeal`
--
ALTER TABLE `appeal`
  MODIFY `appeal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cost`
--
ALTER TABLE `cost`
  MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily`
--
ALTER TABLE `daily`
  MODIFY `daily_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `fan_gal`
--
ALTER TABLE `fan_gal`
  MODIFY `gal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `repair`
--
ALTER TABLE `repair`
  MODIFY `repair_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
