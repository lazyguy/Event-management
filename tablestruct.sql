-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 29, 2012 at 02:21 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `balolsav`
--
DROP DATABASE `balolsav`;
CREATE DATABASE `balolsav` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `balolsav`;

-- --------------------------------------------------------

--
-- Table structure for table `event_master`
--

DROP TABLE IF EXISTS `event_master`;
CREATE TABLE IF NOT EXISTS `event_master` (
  `event_id` int(6) NOT NULL AUTO_INCREMENT,
  `event_name` char(100) DEFAULT NULL,
  `event_type` int(1) DEFAULT NULL,
  `event_year` int(4) DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6007 ;

-- --------------------------------------------------------

--
-- Table structure for table `event_result`
--

DROP TABLE IF EXISTS `event_result`;
CREATE TABLE IF NOT EXISTS `event_result` (
  `regn_number` int(4) NOT NULL,
  `event_id` int(4) NOT NULL,
  `position` int(4) NOT NULL,
  PRIMARY KEY (`regn_number`,`event_id`,`position`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event_trans`
--

DROP TABLE IF EXISTS `event_trans`;
CREATE TABLE IF NOT EXISTS `event_trans` (
  `regn_number` int(4) NOT NULL DEFAULT '0',
  `event_id` int(4) NOT NULL DEFAULT '0',
  `event_marks` int(4) DEFAULT '0',
  `event_grade` char(1) DEFAULT NULL,
  `fee_paid` float DEFAULT NULL,
  PRIMARY KEY (`regn_number`,`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `participant_master`
--

DROP TABLE IF EXISTS `participant_master`;
CREATE TABLE IF NOT EXISTS `participant_master` (
  `regn_number` int(6) NOT NULL AUTO_INCREMENT,
  `student_name` char(100) NOT NULL,
  `age` int(3) NOT NULL,
  `dob` date NOT NULL,
  `sex` char(1) NOT NULL,
  `school_id` int(4) NOT NULL,
  `parent_name` char(100) DEFAULT NULL,
  `st_adress` char(100) DEFAULT NULL,
  `pa_mail_id` char(60) DEFAULT NULL,
  `pa_phone_number` int(12) DEFAULT NULL,
  `category` char(1) NOT NULL,
  PRIMARY KEY (`regn_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=243 ;

-- --------------------------------------------------------

--
-- Table structure for table `school_master`
--

DROP TABLE IF EXISTS `school_master`;
CREATE TABLE IF NOT EXISTS `school_master` (
  `school_id` int(6) NOT NULL AUTO_INCREMENT,
  `school_name` char(100) NOT NULL,
  `s_address` char(100) DEFAULT NULL,
  `princ_name` char(60) DEFAULT NULL,
  `phone_number` int(12) DEFAULT NULL,
  `mail_id` char(60) DEFAULT NULL,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4003 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
