-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2014 at 08:28 PM
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
-- Table structure for table `wordlet_attr`
--

DROP TABLE IF EXISTS `wordlet_attr`;
CREATE TABLE IF NOT EXISTS `wordlet_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `idx` int(11) NOT NULL,
  `info` text NOT NULL,
  `instanced` tinyint(1) NOT NULL DEFAULT '0',
  `html` varchar(32) NOT NULL,
  `format` varchar(32) NOT NULL,
  `type` varchar(64) NOT NULL,
  `show_markup` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordlet_object`
--

DROP TABLE IF EXISTS `wordlet_object`;
CREATE TABLE IF NOT EXISTS `wordlet_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `attr_id` int(11) DEFAULT NULL,
  `cardinality` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `page_id_2` (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordlet_page`
--

DROP TABLE IF EXISTS `wordlet_page`;
CREATE TABLE IF NOT EXISTS `wordlet_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `wordlet_val`
--

DROP TABLE IF EXISTS `wordlet_val`;
CREATE TABLE IF NOT EXISTS `wordlet_val` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attr_id` int(11) NOT NULL,
  `idx` int(11) NOT NULL,
  `value` text NOT NULL,
  `val_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `val_id` (`val_id`),
  KEY `attr_id` (`attr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=110 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
