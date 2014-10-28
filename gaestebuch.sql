-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Okt 2014 um 08:44
-- Server Version: 5.6.11
-- PHP-Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `gaestebuch`
--
CREATE DATABASE IF NOT EXISTS `gaestebuch` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gaestebuch`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gbentries`
--

CREATE TABLE IF NOT EXISTS `gbentries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) NOT NULL,
  `CreationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Title` varchar(50) NOT NULL,
  `Comment` text,
  `ModificationDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Daten für Tabelle `gbentries`
--

INSERT INTO `gbentries` (`id`, `id_user`, `CreationDate`, `Title`, `Comment`, `ModificationDate`) VALUES
(6, 2, '2014-10-26 00:00:00', 'Yeah', 'Oh Yeah', '0000-00-00 00:00:00'),
(8, 2, '2014-10-26 00:00:00', 'Yuppieduppie', 'Fast fertig :)', '0000-00-00 00:00:00'),
(10, 2, '2014-10-27 00:00:00', 'Yuchu, fast fertig :)', 'Yuppie', '0000-00-00 00:00:00'),
(11, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(12, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(13, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(14, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(15, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(16, 2, '2014-10-28 00:00:00', 'Geht das wirklich?', 'Test', '2014-10-28 00:00:00'),
(17, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(18, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(19, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(20, 2, '2014-10-28 00:00:00', 'Test', 'Test', '0000-00-00 00:00:00'),
(21, 5, '2014-10-28 00:00:00', 'Hello there', 'I''m a new user and I find it pretty awesome here :)', '0000-00-00 00:00:00'),
(22, 5, '2014-10-28 00:00:00', 'Test1', 'Test1', '0000-00-00 00:00:00'),
(23, 5, '2014-10-28 00:00:00', 'Test1', 'Test1', '0000-00-00 00:00:00'),
(24, 5, '2014-10-28 00:00:00', 'yes', 'YES SIR', '0000-00-00 00:00:00'),
(25, 5, '0000-00-00 00:00:00', 'Test', 'Test', '2014-10-28 08:39:17'),
(26, 5, '2014-10-28 08:41:45', 'Test', 'Test', '2014-10-28 08:44:15');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id_group` int(10) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(40) NOT NULL,
  `rights` set('read','delete_own','delete_others','write','change_own','change_others') NOT NULL,
  PRIMARY KEY (`id_group`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `groups`
--

INSERT INTO `groups` (`id_group`, `groupname`, `rights`) VALUES
(1, 'Guests', 'read,write'),
(2, 'Users', 'read,delete_own,write,change_own'),
(3, 'Admins', 'read,delete_own,delete_others,write,change_own,change_others');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `Nickname` varchar(40) NOT NULL,
  `Firstname` varchar(50) NOT NULL,
  `Lastname` varchar(50) NOT NULL,
  `Passwort_p` varchar(250) NOT NULL,
  `id_group` int(10) NOT NULL DEFAULT '2',
  `CreationDate` date NOT NULL,
  `Website` varchar(50) NOT NULL,
  `Email_e` varchar(50) NOT NULL,
  `Place` varchar(50) NOT NULL,
  `BirthDate` date NOT NULL,
  `ModificationDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `Nickname`, `Firstname`, `Lastname`, `Passwort_p`, `id_group`, `CreationDate`, `Website`, `Email_e`, `Place`, `BirthDate`, `ModificationDate`) VALUES
(1, 'guest', '', '', '560bcda6641ea82cf0b7567839453f5500ff25021bfe2f7145279b5ef42efa52', 1, '0000-00-00', 'guest entry', 'guest entry', 'guest entry', '0000-00-00', '0000-00-00'),
(2, 'Snatsch', 'Natalie', 'Esther Schumacher', '1955edae3603cd916f94ce380cef5949a48614761ede0b7e0def94bdcb5abb15', 3, '2014-09-16', 'www.facebook.de', 'natalie.schumacher@gibmit.ch', 'Allschwil, Baselland', '1996-03-30', '0000-00-00'),
(3, 'Test', 'Test', 'Test', '2e933a070b8c2c5e421c4fa26e8358f7ecbd64ed247e7d315a0305e183fee556', 2, '2014-10-27', 'Test', 'Test@mail.ch', 'Test', '1996-03-30', '0000-00-00'),
(4, 'Test1', 'Test', 'Test', '1955edae3603cd916f94ce380cef5949a48614761ede0b7e0def94bdcb5abb15', 2, '2014-10-28', 'Test', 'Test@mail.ch', 'test', '1996-03-30', '0000-00-00'),
(5, 'Test2', 'Test', 'Bla bla Du', '1955edae3603cd916f94ce380cef5949a48614761ede0b7e0def94bdcb5abb15', 2, '2014-10-28', 'google.ch', 'natalie.schumacher@gibmit.ch', 'test', '1996-03-30', '0000-00-00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
