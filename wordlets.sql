-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 22, 2013 at 01:32 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wordlets`
--
CREATE DATABASE IF NOT EXISTS `wordlets` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `wordlets`;

-- --------------------------------------------------------

--
-- Table structure for table `wordlet_object`
--

DROP TABLE IF EXISTS `wordlet_object`;
CREATE TABLE IF NOT EXISTS `wordlet_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `show_markup` tinyint(1) NOT NULL DEFAULT '1',
  `attrs` text NOT NULL,
  `vals` text NOT NULL,
  `cardinality` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wordlet_object`
--

INSERT INTO `wordlet_object` VALUES(1, 1, 'Title', 1, 'a:1:{s:6:"single";a:4:{s:4:"type";s:6:"single";s:4:"html";s:4:"none";s:5:"order";i:0;s:11:"show_markup";i:1;}}', 'a:1:{i:0;a:1:{s:6:"single";s:15:"This is a Title";}}', 1);
INSERT INTO `wordlet_object` VALUES(2, 1, 'SubTitle', 1, 'a:1:{s:6:"single";a:4:{s:4:"type";s:6:"single";s:4:"html";s:4:"none";s:5:"order";i:0;s:11:"show_markup";i:1;}}', 'a:1:{i:0;a:1:{s:6:"single";s:19:"This is a Sub Title";}}', 1);
INSERT INTO `wordlet_object` VALUES(3, 1, 'Image', 1, 'a:2:{s:3:"src";a:4:{s:4:"type";s:6:"single";s:4:"html";s:4:"none";s:5:"order";i:0;s:11:"show_markup";i:0;}s:3:"alt";a:4:{s:4:"type";s:6:"single";s:4:"html";s:4:"none";s:5:"order";i:1;s:11:"show_markup";i:0;}}', 'a:1:{i:0;a:2:{s:3:"src";s:31:"http://i.imgur.com/9fOG9nlb.jpg";s:3:"alt";s:16:"This is some Alt";}}', 1);
INSERT INTO `wordlet_object` VALUES(4, 1, 'List', 1, 'a:1:{s:6:"single";a:4:{s:4:"type";s:6:"single";s:4:"html";s:4:"none";s:5:"order";i:0;s:11:"show_markup";i:1;}}', 'a:4:{i:0;a:1:{s:6:"single";s:27:"This is the first list item";}i:1;a:1:{s:6:"single";s:28:"This is the second list item";}i:2;a:1:{s:6:"single";s:27:"This is the third list item";}i:3;a:1:{s:6:"single";s:28:"This is the fourth list item";}}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wordlet_page`
--

DROP TABLE IF EXISTS `wordlet_page`;
CREATE TABLE IF NOT EXISTS `wordlet_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `page_id` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wordlet_page`
--

INSERT INTO `wordlet_page` VALUES(1, NULL, 'index');

-- --------------------------------------------------------

--
-- Table structure for table `wordlet_value`
--

DROP TABLE IF EXISTS `wordlet_value`;
CREATE TABLE IF NOT EXISTS `wordlet_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
