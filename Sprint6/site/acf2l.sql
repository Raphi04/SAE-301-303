-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 05 jan. 2024 à 13:51
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `acf2l`
--

-- --------------------------------------------------------

--
-- Structure de la table `adherent`
--

DROP TABLE IF EXISTS `adherent`;
CREATE TABLE IF NOT EXISTS `adherent` (
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `adherent`
--

INSERT INTO `adherent` (`email`, `prenom`, `nom`, `telephone`) VALUES
('emanuelmacron@gmail.com', 'Emanuel', 'Macron', '0698741256'),
('erickzemour@gmail.com', 'Erick', 'Zemour', '0696987421'),
('hbajoue@gmail.com', 'Hugo', 'Bajoue', '0685749636'),
('jeanlucmelanchon@gmail.com', 'Jean-luc', 'Mélanchon', '068741236'),
('jeanpatrick@gmail.com', 'Jean', 'Patrick', '0685123596'),
('jeanpaul@gmail.com', 'Jean', 'Paul', '0625743912'),
('justinedelafontaine@gmail.com', 'Justine', 'De Lafontaine', '0685742132'),
('martinedupont@gmail.com', 'Martine', 'Dupont', '0685749632'),
('paulmartin@gmail.com', 'Paul', 'Martin', '0687416932'),
('raphaelcadete04@gmail.com', 'Raphael', 'Cadete', '0178585636');

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `Utilisateur` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Mdp` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`Utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`Utilisateur`, `Mdp`) VALUES
('Admin', '1eb28d226fcff027dfe6fcc7ab50fb5ffb54252b8e53f33865cd33e843c3e3be');

-- --------------------------------------------------------

--
-- Structure de la table `pilote`
--

DROP TABLE IF EXISTS `pilote`;
CREATE TABLE IF NOT EXISTS `pilote` (
  `identifiant` int NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`identifiant`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pilote`
--

INSERT INTO `pilote` (`identifiant`, `type`) VALUES
(2, 'Pendulaire'),
(3, 'AutoGire'),
(9, 'Pendulaire'),
(10, 'AutoGire'),
(11, 'Axes'),
(12, 'Axes');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `numReserv` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `matricule` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identifiant` int DEFAULT NULL,
  `dateReserv` date DEFAULT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`numReserv`),
  KEY `identifiant` (`identifiant`),
  KEY `type` (`type`),
  KEY `reservation_ibfk_1` (`email`),
  KEY `reservation_ibfk_2` (`matricule`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`numReserv`, `email`, `matricule`, `identifiant`, `dateReserv`, `type`, `statut`) VALUES
(3, 'emanuelmacron@gmail.com', 'AXB-185-PML', 2, '2024-01-17', 'Pendulaire', 'Traité'),
(6, 'raphaelcadete04@gmail.com', 'AXB-185-PML', 2, '2024-01-04', 'Pendulaire', 'Traité'),
(7, 'raphaelcadete04@gmail.com', 'AXB-185-PML', 2, '2024-01-06', 'Pendulaire', 'Traité'),
(8, 'raphaelcadete04@gmail.com', 'NBB-275-JUL', 9, '2024-01-06', 'Pendulaire', 'Traité'),
(9, 'raphaelcadete04@gmail.com', 'NBB-275-JUL', 9, '2024-01-19', 'Pendulaire', 'Traité'),
(10, 'raphaelcadete04@gmail.com', 'AXB-185-PML', 2, '2024-02-02', 'Pendulaire', 'Traité'),
(12, 'raphaelcadete04@gmail.com', 'AXB-185-PML', 2, '2024-01-05', 'Pendulaire', 'Traité'),
(13, 'raphaelcadete04@gmail.com', NULL, NULL, '2024-01-08', 'Pendulaire', 'En attente'),
(15, 'raphaelcadete04@gmail.com', NULL, NULL, '2024-01-06', 'Pendulaire', 'En attente');

-- --------------------------------------------------------

--
-- Structure de la table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `identifiant` int NOT NULL AUTO_INCREMENT,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`identifiant`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `staff`
--

INSERT INTO `staff` (`identifiant`, `prenom`, `nom`, `role`) VALUES
(2, 'Jean', 'Patrick', 'Pilote'),
(3, 'Jean', 'Michel', 'Pilote'),
(6, 'Jean', 'Micheline', 'Secretaire'),
(8, 'Jean', 'Eude', 'Météorologiste'),
(9, 'Jean', 'Polnareff', 'Pilote'),
(10, 'Jean', 'Giovana', 'Pilote'),
(11, 'Jean', 'Joestar', 'Pilote'),
(12, 'Jean', 'Kakyoin', 'Pilote');

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

DROP TABLE IF EXISTS `vehicule`;
CREATE TABLE IF NOT EXISTS `vehicule` (
  `matricule` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`matricule`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`matricule`, `type`) VALUES
('AXB-185-PML', 'Pendulaire'),
('CVG-256-SDF', 'Axes'),
('IOP-789-MLK', 'Axes'),
('NBB-275-JUL', 'Pendulaire'),
('QSD-147-LPD', 'AutoGire'),
('RTO-963-GFD', 'AutoGire');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `pilote`
--
ALTER TABLE `pilote`
  ADD CONSTRAINT `pilote_ibfk_1` FOREIGN KEY (`identifiant`) REFERENCES `staff` (`identifiant`),
  ADD CONSTRAINT `pilote_ibfk_2` FOREIGN KEY (`identifiant`) REFERENCES `staff` (`identifiant`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`email`) REFERENCES `adherent` (`email`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`matricule`) REFERENCES `vehicule` (`matricule`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_3` FOREIGN KEY (`identifiant`) REFERENCES `pilote` (`identifiant`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
