-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2020 at 01:32 PM
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

--
-- Dumping data for table `air_gal`
--

INSERT INTO `air_gal` (`gal_id`, `gal_name`) VALUES
(1, 'GOPR1568.JPG'),
(2, 'GOPR1569.JPG'),
(3, 'GOPR1571.JPG'),
(4, 'GOPR1570.JPG');

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

--
-- Dumping data for table `cost`
--

INSERT INTO `cost` (`cost_id`, `room_id`, `type`, `cost_status`, `date`, `room_cost`, `water_bill`, `elec_bill`, `cable_charge`, `fines`, `total`) VALUES
(1, '201', '', 'ยังไม่ได้ชำระ', '2020-12', 2700.00, 80.00, 570.00, 105.00, 0.00, '3455'),
(2, '202', '', 'ชำระเงินแล้ว', '2020-12', 2700.00, 80.00, 375.00, 105.00, 0.00, '3260'),
(3, '203', '', 'ยังไม่ได้ชำระ', '2020-12', 2700.00, 80.00, 600.00, 105.00, 0.00, '3485'),
(4, '204', '', 'ชำระเงินแล้ว', '2020-12', 2700.00, 80.00, 315.00, 105.00, 0.00, '3200'),
(5, '207', '', 'ยังไม่ได้ชำระ', '2020-12', 2300.00, 80.00, 225.00, 105.00, 0.00, '2710');

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
(27, 'นาย', 'นวพล', 'นรเดชานันท์', 'เกม', 'พนักงาน', '1509966011521', '0956722914', 'blackfrostier@gmail.com', 'blackfrostier', '1998-12-21', '21', 'ไทย', 'ไทย', '140/40 หมู่ 2 ต. หนองป่าครั่ง อ.เมือง จ.เชียงใหม่ 50000', '404365.jpg', 'ข้อเสนอแนะบ้านพักพิงฟ้า.jpg', '115774657_3345395698845713_2446905765721859802_o.jpg', 'nwpgm82', 'ef16b5fe40a49934ad40c0b059090b14');

-- --------------------------------------------------------

--
-- Table structure for table `fan_gal`
--

CREATE TABLE `fan_gal` (
  `gal_id` int(11) NOT NULL,
  `gal_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fan_gal`
--

INSERT INTO `fan_gal` (`gal_id`, `gal_name`) VALUES
(5, 'GOPR1575.JPG'),
(6, 'GOPR1576.JPG'),
(7, 'GOPR1577.JPG'),
(9, 'GOPR1578.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `gallery_id` int(11) NOT NULL,
  `gallery_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`gallery_id`, `gallery_name`) VALUES
(7, 'GOPR1582.JPG'),
(8, 'GOPR1554.JPG'),
(9, 'GOPR1580.JPG'),
(10, 'GOPR1581.JPG'),
(11, 'GOPR1559.JPG'),
(12, 'GOPR1560.JPG'),
(13, 'GOPR1561.JPG'),
(18, 'GOPR1579.JPG'),
(19, 'GOPR1564.JPG'),
(20, 'GOPR1565.JPG'),
(21, 'GOPR1566.JPG'),
(22, 'GOPR1567.JPG'),
(23, 'GOPR1572.JPG'),
(24, 'GOPR1574.JPG');

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
('201', '201', '69dc9752738ca52de4c6c0b7ce718e08', 'mrdudu@gmail.com', 'guest'),
('202', '202', '475e1c39acbf5b4aff05495b8c1d18f5', 'supisra45.30@gmail.com', 'guest'),
('203', '203', '080d804edae27516dd85cb9b927b6f95', 'manee14655@hotmail.com', 'guest'),
('204', '204', '152f6d383d17f3827fee90a2909a75b8', 'somkid.madee@gmail.com', 'guest'),
('207', '207', 'a576d8b648a41aa7542e55ab5e12b48b', 'wandee.wonderful@gmail.com', 'guest'),
('admin', 'admin', '81dc9bdb52d04dc20036dbd8313ed055', '', 'admin'),
('nwpgm82', 'นวพล', 'ef16b5fe40a49934ad40c0b059090b14', 'blackfrostier@gmail.com', 'employee');

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

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`package_id`, `package_num`, `package_company`, `package_arrived`, `package_status`, `package_name`, `package_room`, `package_received`) VALUES
(2, 'RYMC000206662', 'เคอรี่', '2020-08-10', 'รับพัสดุแล้ว', 'มนุษย์', '201', 'มนุษย์'),
(3, 'RP917308081TH', 'ไปรษณีย์ไทย', '2020-09-08', 'รับพัสดุแล้ว', 'มนุษย์', '201', 'มนุษย์'),
(4, 'RP917307090TH', 'ไปรษณีย์ไทย', '2020-09-12', 'ยังไม่ได้รับพัสดุ', 'สุภิศรา', '202', ''),
(5, 'PD252974232TH', 'ไปรษณีย์ไทย', '2020-09-12', 'ยังไม่ได้รับพัสดุ', 'สุภิศรา', '202', ''),
(6, 'ED375848164TH', 'ไปรษณีย์ไทย', '2020-10-13', 'รับพัสดุแล้ว', 'สมคิด', '204', 'สมคิด'),
(7, 'ED471406393TH', 'ไปรษณีย์ไทย', '2020-10-13', 'ยังไม่ได้รับพัสดุ', 'สุภิศรา', '202', '');

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

--
-- Dumping data for table `repair`
--

INSERT INTO `repair` (`repair_id`, `room_id`, `repair_appliance`, `repair_category`, `repair_detail`, `repair_date`, `repair_successdate`, `repair_status`, `repair_income`, `repair_expenses`) VALUES
(1, '201', 'เครื่องทำน้ำอุ่น', 'เครื่องใช้ไฟฟ้า', 'เครื่องทำน้ำอุ่นไม่ร้อน', '2020-12-05', '2020-12-08', 'ซ่อมเสร็จแล้ว', 300.00, 165.00),
(2, '202', 'โต๊ะ', 'เฟอร์นิเจอร์', 'ขาโต๊ะข้างมุมบนซ้ายชำรุด', '2020-12-09', '2020-12-12', 'ซ่อมเสร็จแล้ว', 200.00, 80.00),
(3, '203', 'ชักโครก', 'สุขภัณฑ์', 'ฝาชักโครกแตกหัก', '2020-12-12', '2020-12-13', 'ซ่อมเสร็จแล้ว', 150.00, 60.00),
(4, '201', 'ประตู', 'เฟอร์นิเจอร์', 'ลูกบิดประตูชำรุด', '2020-12-12', NULL, 'กำลังดำเนินการ', NULL, NULL),
(5, '207', 'พัดลม', 'เครื่องใช้ไฟฟ้า', 'พัดลมไม่ทำงาน', '2020-12-19', NULL, 'รอดำเนินการ', NULL, NULL);

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
('พัดลม', 80.00, 7.50, 105.00, 150.00, 2300.00, 250.00, 'on', '', 'on', 'on', 'on'),
('แอร์', 80.00, 7.50, 105.00, 150.00, 2700.00, 350.00, '', 'on', 'on', 'on', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `roomlist`
--

CREATE TABLE `roomlist` (
  `room_id` varchar(200) NOT NULL,
  `room_type` varchar(200) NOT NULL,
  `room_status` varchar(200) NOT NULL,
  `come` varchar(200) DEFAULT NULL,
  `check_in` varchar(200) DEFAULT NULL,
  `check_out` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roomlist`
--

INSERT INTO `roomlist` (`room_id`, `room_type`, `room_status`, `come`, `check_in`, `check_out`) VALUES
('201', 'แอร์', 'ไม่ว่าง', '2020-03-01', NULL, NULL),
('202', 'แอร์', 'ไม่ว่าง', '2020-10-01', NULL, NULL),
('203', 'แอร์', 'ไม่ว่าง', '2019-08-01', NULL, NULL),
('204', 'แอร์', 'ไม่ว่าง', '2018-10-01', NULL, NULL),
('205', 'แอร์', 'ว่าง', NULL, NULL, NULL),
('206', 'พัดลม', 'ว่าง', NULL, NULL, NULL),
('207', 'พัดลม', 'ไม่ว่าง', '2019-11-01', NULL, NULL),
('208', 'แอร์', 'ว่าง', NULL, NULL, NULL),
('301', 'แอร์', 'ว่าง', NULL, NULL, NULL),
('302', 'แอร์', 'ว่าง', NULL, NULL, NULL),
('303', 'แอร์', 'ว่าง', NULL, NULL, NULL),
('304', 'แอร์', 'ว่าง', NULL, NULL, NULL),
('305', 'แอร์', 'ว่าง', NULL, NULL, NULL),
('306', 'พัดลม', 'ว่าง', NULL, NULL, NULL),
('307', 'แอร์', 'ว่าง', NULL, NULL, NULL),
('308', 'แอร์', 'ว่าง', NULL, NULL, NULL);

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

--
-- Dumping data for table `roommember`
--

INSERT INTO `roommember` (`room_member`, `name_title`, `firstname`, `lastname`, `nickname`, `id_card`, `phone`, `email`, `birthday`, `age`, `race`, `nationality`, `job`, `address`, `pic_idcard`, `pic_home`, `id_line`, `name_title2`, `firstname2`, `lastname2`, `nickname2`, `id_card2`, `phone2`, `email2`, `birthday2`, `age2`, `race2`, `nationality2`, `job2`, `address2`, `pic_idcard2`, `pic_home2`, `id_line2`) VALUES
('201', 'นาย', 'มนุษย์', 'ดาวอังคาร', 'ดู่', '6384419597212', '086-666555', 'mrdudu@gmail.com', '1954-03-01', 65, 'ไทย', 'ไทย', 'ราชการ', '2 ถนนอู่ทองใน แขวงดุสิต เขตดุสิต กรุงเทพมหานคร 10300', 'id_201.jpg', 'home_201.jpg', 'mrdugov', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('202', 'นางสาว', 'สุภิศรา', 'เตชนันท์', 'แยม', '1509966011521', '0956766976', 'supisra45.30@gmail.com', '2009-04-22', 21, 'ไทย', 'ไทย', 'นักศึกษา', 'เลขที่ 288 หมู่ที่ 5 ตำบล ริมกก อำเภอ เมืองเชียงราย จังหวัด เชียงราย 57100', 'id_202.jpg', 'home_202.jpg', 'Yammy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('203', 'นาง', 'มานี', 'มีใจ', 'นี', '1465544564612', '0620477145', 'manee14655@hotmail.com', '1990-12-10', 30, 'ไทย', 'ไทย', 'ราชการ', 'กองบัญชาการกองทัพบก ถนนราชดำเนินนอก เขตพระนคร กรุงเทพฯ 10200', 'id_203.jpg', 'home_203.jpg', 'manee14655', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('204', 'นาย', 'สมคิด', 'มาดี', 'คิด', '1516515615623', '0623215498', 'somkid.madee@gmail.com', '1990-07-11', 30, 'ไทย', 'ไทย', 'รับจ้าง', 'เลขที่ 112 หมู่ 1 ต.ไร่รถ อ.ดอนเจดีย์ จ.สุพรรณบุรี', 'id_204.jpg', 'home_204.jpg', 'somkid1990', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('207', 'นาย', 'วันดี', 'การงาน', 'วันดี', '5698745623156', '0824632598', 'wandee.wonderful@gmail.com', '1995-10-18', 25, 'ไทย', 'ไทย', 'พนักงานรับจ้าง', 'เลขที่ 288 ถ.ทุ่งโฮเต็ล ต.วัดเกต อ.เมือง จ.เชียงใหม่ 50000', 'id_207.jpg', 'home_207.jpg', 'wandeewonderful', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
('1. ผู้เช่าจะต้องอยู่ครบ 6 เดือน พร้อมด้วยการแจ้งล่วงหน้าอย่างน้อย 1 เดือน ทางผู้ให้เช่าจึงจะคืนเงินประกันให้\r\n2. ผู้เช่าจะต้องชำระเงินค่าห้อง ค่าน้ำ และค่าไฟ ก่อนวันที่ 5 ของทุกเดือนหากจ่ายช้ากว่ากำหนดทางผู้เช่าจะเก็บวันละ 150 บาท หากผู้เช่าค้างชำระเกิน 15 วัน ทางผู้ให้เช่ามีสิทธิ์ให้ผู้เช่าออกโดยไม่คืนเงินประกัน\r\n3. ห้ามนำของผิดกฏหมายเข้ามาในห้องพักโดยเด็ดขาด และทางผู้ให้เช่าจะไม่รับผิดชอบใด ๆ ทั้งสิ้น\r\n4. ห้ามเปิด ทีวี เครื่องเสียง วิทยุ ส่งเสียงดัง หรือทำกิจกรรมใด ๆ ที่ทำให้เกิดเสียงดังรบกวนผู้อื่น\r\n5. ห้ามนำสัตว์เลี้ยงทุกชนิดเข้ามาเลี้ยงในห้องพักอย่างเด็ดขาด\r\n6.ห้ามก่อการทะเลาะวิวาท ภายในบริเวณหอพัก\r\n7. ห้ามดื่มสุรา สูบบุหรี่ และสิ่งเสพติดทุกชนิดภายในบริเวณหอพัก\r\n8. ห้ามเล่นการพนันทุกชนิดภายในห้องพัก\r\n9. ห้ามตอกตะปู ติดรูป สติ๊กเกอร์ ภายในห้องพัก และทรัพย์สินของทางหอพัก หายพบจะต้องเสียค่าปรับจุดละ 50 บาท\r\n10. กรณีที่ทำลูกกุญแจหาย ปรับ 100 บาท / ลูก และบัตรคีย์การ์ดหาย ปรับ 200 บาท / ใบ\r\n11. ห้ามประกอบอาหาร โดยใช้แก๊สหุงต้มโดยเด็ดขาด\r\n12. ปิดน้ำประปา และไฟฟ้าให้เรียบร้อยก่อนออกจากห้องพักทุกครั้ง\r\n13. ทางหอพักจะไม่รับผิดชอบทรัพย์สินมีค่าใด ๆ ในกรณีที่เกิดการสูญหาย\r\n14. ทางหอพักได้จัดเตรียม ตู้เสื้อผ้า เตียงนอน ที่นอนสปริง ผ้ารองกันเปื้อน ผ้าปูที่นอน โต๊ะทำงาน พัดลม เครื่องทำน้ำอุ่น และผ้าม่านให้ผู้เช่าไว้พร้อมในห้องพัก แต่ถ้าอุปกรณ์เครื่องใช้เกิดการชำรุด หรือเสียหาย ทางผู้ให้เช่าจะยึดเงินค่าประกัน และเรียกเก็บเพิ่มตามแต่เห็นสมควรของมูลค่าทรัพย์สิน-อุปกรณ์ ที่ชำรุดเสียหายนั้น\r\n15. ทางหอพักสามารถเข้าได้ตลอด 24 ชั่วโมง หากผู้เช่าเข้าหอพักหลังเวลา 20.00 น. ให้ล็อคกุญแจประตูใหญ่ด้านหน้าทุกครั้ง\r\n16. เพื่อความปลอดภัยของผู้เช่าห้องพัก ทางผู้ดูแลหอพักได้บันทึกภาพกล้องวงจรปิดไว้ตลอด 24 ชั่วโมง ห้ามกระทำการใด ๆ ที่ผิดกฎระเบียบฯ หรือผิดกฎหมายอย่างเด็ดขาด\r\n17. ขยะก่อนนำมาทิ้งให้ใส่ถุงขยะที่มีตราของเทศบาลตำบลหนองป่าครั่งเท่านั้น\r\n18. ห้องพักสามารถพักได้ไม่เกิน 2 คน และต้องแจ้งให้ผู้เช่าทราบก่อนทุกครั่ง หากฝ่าฝืนปรับ 200 บาท / วัน หรือให้ออกจากห้องพักทันที\r\n** หากผู้เช่าทำผิดกฎระเบียบข้างต้น ผู้ให้เช่ามีสิทธิ์ให้ออกโดยไม่คืนค่าประกันไม่ว่ากรณีใด ๆ **');

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
  MODIFY `gal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appeal`
--
ALTER TABLE `appeal`
  MODIFY `appeal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cost`
--
ALTER TABLE `cost`
  MODIFY `cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `gal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `package`
--
ALTER TABLE `package`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `repair`
--
ALTER TABLE `repair`
  MODIFY `repair_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
