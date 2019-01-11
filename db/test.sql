-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 25, 2018 at 05:06 AM
-- Server version: 5.7.21
-- PHP Version: 7.0.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('anup7vm55fmeaq15976behjvm61o57uo', '192.168.0.104', 1535948065, 0x5f5f63695f6c6173745f726567656e65726174657c693a313533353934373839323b6c616e677c733a323a226b68223b6c6f676765645f696e7c623a313b),
('lpeousu9ph0f7920o4l6vdv4aqi19g8p', '192.168.0.104', 1535947895, 0x5f5f63695f6c6173745f726567656e65726174657c693a313533353934373839323b6c616e677c733a323a226b68223b6c6f676765645f696e7c623a313b),
('kseie08c6culjcfgj2u0n1aj59gn4d89', '192.168.0.104', 1535947432, 0x5f5f63695f6c6173745f726567656e65726174657c693a313533353934373432363b6c616e677c733a323a22656e223b6c6f676765645f696e7c623a313b),
('pldhcjdvarfngv4lk17grh2llt1dso1m', '192.168.0.104', 1535946928, 0x5f5f63695f6c6173745f726567656e65726174657c693a313533353934363932383b6c616e677c733a323a22656e223b6c6f676765645f696e7c623a313b),
('5m1jvd4d82nm5l73hndgq2e0nd8bfnsu', '192.168.0.104', 1535946594, 0x5f5f63695f6c6173745f726567656e65726174657c693a313533353934363539343b),
('uarv6164aquengks2q3ts98nmb3mq2qv', '192.168.0.104', 1535946574, 0x5f5f63695f6c6173745f726567656e65726174657c693a313533353934363537313b),
('s60qlroomuou5vnu6m0gvltb0l98ojur', '192.168.0.104', 1535946574, 0x5f5f63695f6c6173745f726567656e65726174657c693a313533353934363537313b),
('asuk2rv4j67ea8sega4cegilvkon107e', '192.168.0.104', 1535946574, 0x5f5f63695f6c6173745f726567656e65726174657c693a313533353934363537303b),
('dklg5jrmjoke30th1ft8iucoepsojp0n', '192.168.0.110', 1535938769, ''),
('ei9aatnta3a2qar5kd0r947vhu333kf8', '192.168.0.110', 1535938816, ''),
('6jpcnv1hkn1m5mframk52777qu9s4lj1', '192.168.0.110', 1535938769, ''),
('ft4cqel9aho56bcbt8uk451albsla9u9', '192.168.0.110', 1535938825, ''),
('5330io6pd9crdnmno92mum84lqlts87u', '192.168.0.110', 1535938825, ''),
('1bs1u4st3g410qi2qceadgt2memu1bij', '192.168.0.110', 1535938948, ''),
('leesvsm7kromgrr6l3j8pjaqmi7ejnup', '192.168.0.110', 1535938955, ''),
('rjo8fj721sk1cnl0a6r2tkun94pfdvvc', '192.168.0.110', 1535938955, ''),
('tl5mv0dp65rejupo6p56nm4mq2qgkg0q', '192.168.0.110', 1535939029, ''),
('jd2tqanuh7gridlva6fiaqbi5par7rih', '192.168.0.110', 1535939041, ''),
('45bjql4990vpf05ungltj8ve5ke7pcql', '192.168.0.110', 1535939041, ''),
('bndr8fbfivhnck9ld0lr5ah52dmko8md', '192.168.0.110', 1535939541, ''),
('rc4gcn2q2gr0dik6kicghvnp7srca09d', '192.168.0.110', 1535939600, ''),
('imr7phnhftkhr5raa1dv1krtesh9g2bj', '192.168.0.110', 1535939600, ''),
('2hu7b989lmohk10qtc03h5smjmnm4r67', '192.168.0.110', 1535940497, ''),
('8eiimn43jknddthp69q60qqveth9h0s3', '192.168.0.110', 1535940504, ''),
('qm64rcc5ffueu4ne94df532p0lmbsqbu', '192.168.0.110', 1535940504, ''),
('ku57s13te58jj0kdbgsnb6kpcau0dg02', '192.168.0.110', 1535942657, 0x6c616e677c733a323a226b68223b6c6f676765645f696e7c623a313b),
('ehq160vkm740tc23qqpelh5dctp9m1fa', '192.168.0.110', 1535943117, ''),
('1tcamr008to1qg1g4gm29o0kbbr2hhuf', '192.168.0.110', 1535943117, ''),
('630cbr0f6599513mlot52arap0l78qrv', '192.168.0.110', 1535943309, 0x6c616e677c733a323a226b68223b6c6f676765645f696e7c623a313b),
('q002f6r96uk61petimq6oq20lbr71drp', '192.168.0.110', 1535944075, ''),
('a4e3tkp4ju9tnm793gftkhr0ivk055k7', '192.168.0.110', 1535944075, ''),
('86u4d6pv2i09a6ro68ok9jtf8qfrgtps', '192.168.0.110', 1535944539, ''),
('9ka27631r5n53peto5cc72b95pb56l4v', '192.168.0.110', 1535944545, ''),
('5b7m87sprph8n2cndji6h2m5ejhj6eff', '192.168.0.110', 1535944545, ''),
('3f3imahhs056ptd8p58i34hddfakshnp', '192.168.0.110', 1535944616, 0x6c616e677c733a323a226b68223b6c6f676765645f696e7c623a313b),
('gn5e8qm6j455gnde7kem2br1ig0i524i', '192.168.0.110', 1535944677, ''),
('ptnupi1qpn3e2f0r0pbltbld4c83e8kl', '192.168.0.110', 1535944677, ''),
('8n789josb04rvhbqutb17n219phb920c', '192.168.0.110', 1535945242, 0x6c616e677c733a323a226b68223b6c6f676765645f696e7c623a313b),
('f2ugtm909le0jp3q3mvofepiqn8dd85i', '192.168.0.110', 1535945249, ''),
('dps874vpimm95p40dm4siubmt7b3utdd', '192.168.0.110', 1535945249, ''),
('c7uuht4r7h80g27gdq5nmj7dgcev180v', '192.168.0.110', 1535948307, 0x6c616e677c733a323a22656e223b6c6f676765645f696e7c623a313b);

-- --------------------------------------------------------

--
-- Table structure for table `tblconfig`
--

DROP TABLE IF EXISTS `tblconfig`;
CREATE TABLE IF NOT EXISTS `tblconfig` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) NOT NULL,
  `server_name` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `database_name` varchar(100) NOT NULL,
  `type` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblconfig`
--

INSERT INTO `tblconfig` (`id`, `app_name`, `server_name`, `user_name`, `password`, `database_name`, `type`) VALUES
(14, '192.168.0.105:81', '192.168.0.105', 'sa', '123', 'RestaurantV1', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
