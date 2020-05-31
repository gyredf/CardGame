-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 09 mai 2020 à 13:49
-- Version du serveur :  5.7.26
-- Version de PHP :  7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bataille`
--

-- --------------------------------------------------------

--
-- Structure de la table `carte`
--

DROP TABLE IF EXISTS `carte`;
CREATE TABLE IF NOT EXISTS `carte` (
  `Id` int(255) NOT NULL,
  `Valeur` int(16) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `carte`
--

INSERT INTO `carte` (`Id`, `Valeur`) VALUES
(1, 13),
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 5),
(7, 6),
(8, 7),
(9, 8),
(10, 9),
(11, 10),
(12, 11),
(13, 12);

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

DROP TABLE IF EXISTS `compte`;
CREATE TABLE IF NOT EXISTS `compte` (
  `Id` int(255) NOT NULL,
  `Pseudo` char(16) COLLATE latin1_bin NOT NULL,
  `Mail` char(16) COLLATE latin1_bin NOT NULL,
  `Password` char(255) COLLATE latin1_bin NOT NULL,
  `Date_Inscription` datetime NOT NULL,
  `Last_Connexion` datetime NOT NULL,
  `Online` tinyint(1) NOT NULL,
  `EtatRecherche` tinyint(1) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`Id`, `Pseudo`, `Mail`, `Password`, `Date_Inscription`, `Last_Connexion`, `Online`, `EtatRecherche`) VALUES
(1, 'TesteurOne', 'blabla@blabla.fr', '1234', '2018-12-07 00:00:00', '2018-12-07 00:00:00', 1, 1),
(2, 'TesteurTwo', 'blabla@blabla.fr', '1234', '2012-09-18 00:00:00', '2015-09-15 00:00:00', 0, 0),
(3, 'TesteurThree', 'blabla@blabla.fr', '1234', '2004-11-24 00:00:00', '2007-02-20 00:00:00', 0, 0),
(4, 'TesteurFour', 'blabla@blabla.fr', '1234', '2020-01-09 14:37:52', '2020-01-09 14:37:52', 0, 0),
(5, 'TesteurFive', 'blabla@blabla.fr', '1234', '2020-01-09 14:37:52', '2020-01-09 14:37:52', 0, 0),
(6, 'TesteurSix', 'blabla@blabla.fr', '1234', '2020-01-09 14:37:52', '2020-01-09 14:37:52', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

DROP TABLE IF EXISTS `partie`;
CREATE TABLE IF NOT EXISTS `partie` (
  `IdPartie` int(255) NOT NULL AUTO_INCREMENT,
  `IdJ1` int(255) DEFAULT NULL,
  `IdJ2` int(255) DEFAULT NULL,
  `PVJ1` int(255) DEFAULT NULL,
  `PVJ2` int(255) DEFAULT NULL,
  `EtatJ1` int(2) DEFAULT NULL,
  `EtatJ2` int(2) DEFAULT NULL,
  `J1CJoue` int(11) NOT NULL DEFAULT '0',
  `J2CJoue` int(11) NOT NULL DEFAULT '0',
  `J1Pret` tinyint(1) NOT NULL,
  `J2Pret` tinyint(1) NOT NULL,
  PRIMARY KEY (`IdPartie`),
  KEY `fk_PartieCompteIdJ1` (`IdJ1`),
  KEY `fk_PartieCompteIdJ2` (`IdJ2`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `partie`
--

INSERT INTO `partie` (`IdPartie`, `IdJ1`, `IdJ2`, `PVJ1`, `PVJ2`, `EtatJ1`, `EtatJ2`, `J1CJoue`, `J2CJoue`, `J1Pret`, `J2Pret`) VALUES
(46, 1, 2, 0, 5, 2, 2, 2, 4, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `partieenattente`
--

DROP TABLE IF EXISTS `partieenattente`;
CREATE TABLE IF NOT EXISTS `partieenattente` (
  `idpartie` int(11) NOT NULL AUTO_INCREMENT,
  `idj1` int(11) DEFAULT NULL,
  `idj2` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpartie`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `partie`
--
ALTER TABLE `partie`
  ADD CONSTRAINT `fk_PartieCompteIdJ1` FOREIGN KEY (`IdJ1`) REFERENCES `compte` (`Id`),
  ADD CONSTRAINT `fk_PartieCompteIdJ2` FOREIGN KEY (`IdJ2`) REFERENCES `compte` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
