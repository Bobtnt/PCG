-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 23 Mars 2009 à 18:18
-- Version du serveur: 5.1.30
-- Version de PHP: 5.2.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `sample`
--

-- --------------------------------------------------------

--
-- Structure de la table `bug`
--

CREATE TABLE IF NOT EXISTS `bug` (
  `bug_id` int(11) NOT NULL AUTO_INCREMENT,
  `bug_subject` varchar(255) NOT NULL,
  `bug_description` text NOT NULL,
  `bug_priority` enum('low','high','medium') NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY (`bug_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=177 ;

--
-- Contenu de la table `bug`
--

INSERT INTO `bug` (`bug_id`, `bug_subject`, `bug_description`, `bug_priority`, `category_id`) VALUES
(1, 'Migrer statut Flux ', 'desc test 1814', 'low', 3),
(2, 'rapport entreprise rapport ', 'desc test 1916', 'low', 3),
(3, 'entreprise Accès sites ', 'desc test 1001', 'low', 3),
(4, 'rapport Modification Accès ', 'desc test 1715', 'low', 3),
(5, 'Création nom de domaine Création ', 'desc test 399', 'low', 3),
(6, 'statut Technique provenant ', 'desc test 1500', 'low', 1),
(7, 'statut provenant provenant ', 'desc test 1359', 'low', 3),
(8, 'sites Accès sites ', 'desc test 791', 'low', 3),
(9, 'Accès entreprise Flux ', 'desc test 1585', 'low', 3),
(10, 'provenant Gestion Mettre à jour ', 'desc test 954', 'low', 3),
(11, 'statut table provenant ', 'desc test 821', 'low', 3),
(12, 'Accès statut Flux ', 'desc test 418', 'low', 3),
(13, 'Flux Mettre à jour Accès ', 'desc test 1629', 'low', 3),
(14, 'provenant Gestion sites ', 'desc test 596', 'low', 3),
(15, 'Technique Création Technique ', 'desc test 1028', 'low', 3),
(16, 'Gestion rapport sites ', 'desc test 1207', 'low', 3),
(17, 'statut bug rapport ', 'desc test 760', 'low', 3),
(18, 'Création Modification Création ', 'desc test 1069', 'low', 3),
(19, 'Mettre à jour Mettre à jour Flux ', 'desc test 1671', 'low', 3),
(20, 'rapport provenant Accès ', 'desc test 1701', 'low', 3),
(21, 'Gestion bug Accès ', 'desc test 1903', 'low', 3),
(22, 'statut table Modification ', 'desc test 1922', 'low', 3),
(23, 'Accès Modification statut ', 'desc test 499', 'low', 3),
(24, 'nom de domaine Modification rapport ', 'desc test 1449', 'low', 3),
(25, 'entreprise bug statut ', 'desc test 1006', 'low', 3),
(26, 'statut Création provenant ', 'desc test 903', 'low', 3),
(27, 'statut Modification Création ', 'desc test 664', 'low', 3),
(28, 'nom de domaine Migrer Technique ', 'desc test 1802', 'low', 3),
(29, 'Migrer Gestion Modification ', 'desc test 1220', 'low', 3),
(30, 'bug Accès entreprise ', 'desc test 1164', 'low', 3),
(31, 'Modification bug Modification ', 'desc test 1715', 'low', 3),
(32, 'provenant Mettre à jour rapport ', 'desc test 509', 'low', 3),
(33, 'Gestion Modification statut ', 'desc test 294', 'low', 3),
(34, 'entreprise Flux entreprise ', 'desc test 455', 'low', 3),
(35, 'Accès Flux bug ', 'desc test 1402', 'low', 3),
(36, 'sites Accès Flux ', 'desc test 721', 'low', 3),
(37, 'Migrer bug bug ', 'desc test 1381', 'low', 3),
(38, 'Création Migrer nom de domaine ', 'desc test 1927', 'low', 3),
(39, 'Migrer Création Création ', 'desc test 1074', 'low', 3),
(40, 'table Modification entreprise ', 'desc test 1989', 'low', 3),
(41, 'provenant nom de domaine rapport ', 'desc test 427', 'low', 3),
(42, 'nom de domaine Modification Gestion ', 'desc test 1527', 'low', 3),
(43, 'bug statut bug ', 'desc test 282', 'low', 3),
(44, 'table Création provenant ', 'desc test 260', 'low', 3),
(45, 'nom de domaine Création Accès ', 'desc test 585', 'low', 3),
(46, 'Migrer Accès entreprise ', 'desc test 607', 'low', 3),
(47, 'nom de domaine rapport Migrer ', 'desc test 377', 'low', 3),
(48, 'Mettre à jour rapport provenant ', 'desc test 486', 'low', 3),
(49, 'statut Accès provenant ', 'desc test 1404', 'low', 3),
(50, 'table provenant Gestion ', 'desc test 517', 'low', 3),
(51, 'Flux Migrer Création ', 'desc test 908', 'low', 3),
(52, 'entreprise Mettre à jour rapport ', 'desc test 417', 'low', 3),
(53, 'bug table Gestion ', 'desc test 633', 'low', 3),
(54, 'Flux table Mettre à jour ', 'desc test 807', 'low', 3),
(55, 'rapport Mettre à jour statut ', 'desc test 1424', 'low', 3),
(56, 'bug sites nom de domaine ', 'desc test 1403', 'low', 3),
(57, 'bug rapport sites ', 'desc test 1424', 'low', 3),
(58, 'bug statut Accès ', 'desc test 1026', 'low', 3),
(59, 'Migrer entreprise Flux ', 'desc test 1477', 'low', 3),
(60, 'table Modification bug ', 'desc test 1194', 'low', 3),
(61, 'Mettre à jour Migrer Flux ', 'desc test 1526', 'low', 3),
(62, 'Création Gestion Accès ', 'desc test 725', 'low', 3),
(63, 'entreprise nom de domaine sites ', 'desc test 614', 'low', 3),
(64, 'Mettre à jour table Migrer ', 'desc test 1139', 'low', 3),
(65, 'statut statut bug ', 'desc test 488', 'low', 3),
(66, 'bug sites table ', 'desc test 1254', 'low', 3),
(67, 'bug Modification Flux ', 'desc test 1989', 'low', 3),
(68, 'entreprise rapport Migrer ', 'desc test 788', 'low', 3),
(69, 'sites Migrer provenant ', 'desc test 1461', 'low', 3),
(70, 'sites rapport Accès ', 'desc test 362', 'low', 3),
(71, 'Accès Flux Flux ', 'desc test 1549', 'low', 3),
(72, 'table Création Gestion ', 'desc test 1493', 'low', 3),
(73, 'Mettre à jour table Mettre à jour ', 'desc test 395', 'low', 3),
(74, 'bug Mettre à jour bug ', 'desc test 1200', 'low', 3),
(75, 'Gestion entreprise rapport ', 'desc test 645', 'low', 3),
(76, 'Gestion sites Migrer ', 'desc test 1002', 'low', 3),
(77, 'provenant table Flux ', 'desc test 441', 'low', 3),
(78, 'Gestion sites Mettre à jour ', 'desc test 1518', 'low', 3),
(79, 'table bug Gestion ', 'desc test 627', 'low', 3),
(80, 'provenant Gestion Technique ', 'desc test 666', 'low', 3),
(81, 'bug Accès Modification ', 'desc test 1147', 'low', 3),
(82, 'bug rapport Gestion ', 'desc test 865', 'low', 3),
(83, 'Création Migrer provenant ', 'desc test 1045', 'low', 3),
(84, 'Accès table table ', 'desc test 1835', 'low', 3),
(85, 'sites Création Migrer ', 'desc test 264', 'low', 3),
(86, 'provenant Création entreprise ', 'desc test 592', 'low', 3),
(87, 'table entreprise bug ', 'desc test 1449', 'low', 3),
(88, 'Migrer Accès Migrer ', 'desc test 457', 'low', 3),
(89, 'nom de domaine Modification Technique ', 'desc test 942', 'low', 3),
(90, 'table Mettre à jour Création ', 'desc test 249', 'low', 3),
(91, 'rapport statut Technique ', 'desc test 1389', 'low', 3),
(92, 'sites Modification Création ', 'desc test 1486', 'low', 3),
(93, 'Modification Flux Création ', 'desc test 931', 'low', 3),
(94, 'Mettre à jour Migrer nom de domaine ', 'desc test 1186', 'low', 3),
(95, 'statut provenant Accès ', 'desc test 414', 'low', 3),
(96, 'Gestion Technique Accès ', 'desc test 868', 'low', 3),
(97, 'nom de domaine Création table ', 'desc test 1582', 'low', 3),
(98, 'Gestion entreprise entreprise ', 'desc test 1152', 'low', 3),
(99, 'my test', 'desc test 1676', 'low', 3),
(100, 'my test', 'desc test 1756', 'low', 3),
(101, 'Flux table Migrer ', 'desc test 642', 'low', 3),
(102, 'rapport entreprise entreprise ', 'desc test 1498', 'low', 3),
(103, 'Migrer Mettre à jour Modification ', 'desc test 1124', 'low', 3),
(104, 'rapport table Accès ', 'desc test 1898', 'low', 3),
(105, 'nom de domaine sites statut ', 'desc test 1264', 'low', 3),
(106, 'Migrer sites sites ', 'desc test 1773', 'low', 3),
(107, 'Modification Modification Création ', 'desc test 1908', 'low', 3),
(108, 'Mettre à jour Création nom de domaine ', 'desc test 844', 'low', 3),
(109, 'Gestion Flux Flux ', 'desc test 1197', 'low', 3),
(110, 'Accès Mettre à jour Mettre à jour ', 'desc test 1528', 'low', 3),
(111, 'statut Flux Accès ', 'desc test 1777', 'low', 3),
(112, 'Accès nom de domaine entreprise ', 'desc test 1745', 'low', 3),
(113, 'table Flux bug ', 'desc test 1791', 'low', 3),
(114, 'statut Mettre à jour Création ', 'desc test 313', 'low', 3),
(115, 'Technique Mettre à jour rapport ', 'desc test 282', 'low', 3),
(116, 'nom de domaine Flux Gestion ', 'desc test 551', 'low', 3),
(117, 'nom de domaine Création provenant ', 'desc test 795', 'low', 3),
(118, 'sites Création statut ', 'desc test 1278', 'low', 3),
(119, 'table sites bug ', 'desc test 573', 'low', 3),
(120, 'bug nom de domaine provenant ', 'desc test 411', 'low', 3),
(121, 'Technique Migrer Mettre à jour ', 'desc test 1361', 'low', 3),
(122, 'Création rapport Migrer ', 'desc test 372', 'low', 3),
(123, 'sites table Gestion ', 'desc test 400', 'low', 3),
(124, 'nom de domaine nom de domaine Technique ', 'desc test 877', 'low', 3),
(125, 'nom de domaine table provenant ', 'desc test 1237', 'low', 3),
(126, 'entreprise provenant sites ', 'desc test 746', 'low', 3),
(127, 'table table nom de domaine ', 'desc test 1114', 'low', 3),
(128, 'table Gestion nom de domaine ', 'desc test 1497', 'low', 3),
(129, 'Accès sites Création ', 'desc test 1775', 'low', 3),
(130, 'Technique Technique table ', 'desc test 1950', 'low', 3),
(131, 'sites Modification sites ', 'desc test 463', 'low', 3),
(132, 'Mettre à jour entreprise entreprise ', 'desc test 1822', 'low', 3),
(133, 'table entreprise Gestion ', 'desc test 723', 'low', 3),
(134, 'entreprise rapport Création ', 'desc test 1733', 'low', 3),
(135, 'Accès table bug ', 'desc test 1598', 'low', 3),
(136, 'rapport Flux entreprise ', 'desc test 1402', 'low', 3),
(137, 'Gestion Migrer Création ', 'desc test 1232', 'low', 3),
(138, 'table statut Migrer ', 'desc test 1446', 'low', 3),
(139, 'Mettre à jour rapport Migrer ', 'desc test 469', 'low', 3),
(140, 'Technique entreprise rapport ', 'desc test 1586', 'low', 3),
(141, 'provenant sites statut ', 'desc test 1053', 'low', 3),
(142, 'entreprise Création nom de domaine ', 'desc test 639', 'low', 3),
(143, 'entreprise sites statut ', 'desc test 226', 'low', 3),
(144, 'Modification Modification Migrer ', 'desc test 1925', 'low', 3),
(145, 'rapport Flux Technique ', 'desc test 1534', 'low', 3),
(146, 'sites statut Création ', 'desc test 661', 'low', 3),
(147, 'Accès Mettre à jour Mettre à jour ', 'desc test 419', 'low', 3),
(148, 'statut entreprise Création ', 'desc test 1968', 'low', 3),
(149, 'rapport nom de domaine bug ', 'desc test 426', 'low', 3),
(150, 'sites Gestion bug ', 'desc test 1063', 'low', 3),
(151, 'rapport Flux rapport ', 'desc test 598', 'low', 3),
(152, 'entreprise sites Flux ', 'desc test 1266', 'low', 3),
(153, 'bug sites nom de domaine ', 'desc test 879', 'low', 3),
(154, 'Modification provenant sites ', 'desc test 1395', 'low', 3),
(155, 'Modification nom de domaine bug ', 'desc test 312', 'low', 3),
(156, 'rapport Gestion Accès ', 'desc test 1169', 'low', 3),
(157, 'Gestion Mettre à jour Création ', 'desc test 643', 'low', 3),
(158, 'statut bug table ', 'desc test 1207', 'low', 3),
(159, 'nom de domaine Mettre à jour entreprise ', 'desc test 913', 'low', 3),
(160, 'statut Flux statut ', 'desc test 1227', 'low', 3),
(161, 'entreprise rapport Modification ', 'desc test 1068', 'low', 3),
(162, 'bug Mettre à jour sites ', 'desc test 1848', 'low', 3),
(163, 'nom de domaine provenant table ', 'desc test 989', 'low', 3),
(164, 'entreprise Flux Technique ', 'desc test 1951', 'low', 3),
(165, 'rapport entreprise statut ', 'desc test 1069', 'low', 3),
(166, 'Modification Gestion statut ', 'desc test 1704', 'low', 3),
(167, 'Gestion sites Modification ', 'desc test 1806', 'low', 3),
(168, 'Migrer Flux Mettre à jour ', 'desc test 1173', 'low', 3),
(169, 'sites bug Création ', 'desc test 219', 'low', 3),
(170, 'Accès rapport entreprise ', 'desc test 300', 'low', 3),
(171, 'sites rapport Accès ', 'desc test 1428', 'low', 3),
(172, 'entreprise Flux entreprise ', 'desc test 1732', 'low', 3),
(173, 'Modification bug Gestion ', 'desc test 649', 'low', 3),
(174, 'table Migrer statut ', 'desc test 1809', 'low', 3),
(175, 'entreprise Mettre à jour Mettre à jour ', 'desc test 1204', 'low', 3),
(176, 'Modification entreprise Technique ', 'desc test 1376', 'low', 3);

-- --------------------------------------------------------

--
-- Structure de la table `bug_has_user`
--

CREATE TABLE IF NOT EXISTS `bug_has_user` (
  `bug_id` int(1) NOT NULL,
  `reported_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `verified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`bug_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `bug_has_user`
--


-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `category_id` int(1) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'hardawre'),
(2, 'software'),
(3, 'Between the chair and the keyboard');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_short_name` varchar(255) NOT NULL,
  `user_full_name` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `user`
--

