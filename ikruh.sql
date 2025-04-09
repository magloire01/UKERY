-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 24 fév. 2025 à 11:13
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ikruh`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualites`
--

DROP TABLE IF EXISTS `actualites`;
CREATE TABLE IF NOT EXISTS `actualites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) DEFAULT NULL,
  `contenu` text,
  `image` varchar(256) NOT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('publie','brouillon') NOT NULL DEFAULT 'publie',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `actualites`
--

INSERT INTO `actualites` (`id`, `titre`, `contenu`, `image`, `date_creation`, `statut`) VALUES
(11, 'dfdfs', 'fdgdfgdgf', '67b95f6984954.png', '2025-02-22 06:23:53', 'publie'),
(13, 'test wewe', 'uuihuui ugiug ihhiohi  uigug lbklbkj', '67bc51c59b7dd.png', '2025-02-24 12:02:29', 'publie');

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `nom`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `cover`
--

DROP TABLE IF EXISTS `cover`;
CREATE TABLE IF NOT EXISTS `cover` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('image','video') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `active` tinyint(1) DEFAULT '0',
  `date_ajout` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cover`
--

INSERT INTO `cover` (`id`, `type`, `file_name`, `active`, `date_ajout`) VALUES
(1, 'image', 'cover_67b9af95e6a89.png', 0, '2025-02-22 12:05:57'),
(2, 'image', 'cover_67b9b1a10ece4.png', 0, '2025-02-22 12:14:41'),
(3, 'image', 'cover_67bc53196350b.png', 0, '2025-02-24 12:08:09'),
(4, 'image', 'cover_67bc535d23967.png', 1, '2025-02-24 12:09:17');

-- --------------------------------------------------------

--
-- Structure de la table `musiques`
--

DROP TABLE IF EXISTS `musiques`;
CREATE TABLE IF NOT EXISTS `musiques` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(256) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `lien` varchar(256) DEFAULT NULL,
  `statut` enum('publie','brouillon','','') NOT NULL DEFAULT 'publie',
  `date_ajout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `musiques`
--

INSERT INTO `musiques` (`id`, `titre`, `image`, `lien`, `statut`, `date_ajout`) VALUES
(2, 'fxvf', '67b9676c0edd1.png', 'https://ddjj.com', 'publie', '2025-02-22 06:58:04');

-- --------------------------------------------------------

--
-- Structure de la table `phototeques`
--

DROP TABLE IF EXISTS `phototeques`;
CREATE TABLE IF NOT EXISTS `phototeques` (
  `id` int NOT NULL AUTO_INCREMENT,
  `image` varchar(256) DEFAULT NULL,
  `statut` enum('publie','brouillon') NOT NULL DEFAULT 'publie',
  `date_ajout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `phototeques`
--

INSERT INTO `phototeques` (`id`, `image`, `statut`, `date_ajout`) VALUES
(5, 'photo2.png', 'publie', '2025-02-22 08:59:46'),
(4, 'photo.png', 'publie', '2025-02-22 08:59:46'),
(6, 'photo3.png', 'publie', '2025-02-22 09:00:10'),
(7, 'music.png', 'publie', '2025-02-22 09:02:10'),
(8, 'videos.png', 'publie', '2025-02-22 09:02:10'),
(9, 'Acc.jpg', 'publie', '2025-02-22 09:02:26');

-- --------------------------------------------------------

--
-- Structure de la table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(256) DEFAULT NULL,
  `image` varchar(256) NOT NULL,
  `lien` varchar(256) DEFAULT NULL,
  `statut` enum('publie','brouillon','','') NOT NULL DEFAULT 'publie',
  `date_ajout` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `videos`
--

INSERT INTO `videos` (`id`, `titre`, `image`, `lien`, `statut`, `date_ajout`) VALUES
(3, 'jbjbj', '67bc53c29f401.png', 'https://ddjj.com', 'publie', '2025-02-24 12:10:58'),
(4, 'nnn  ', '67bc53e26b37d.png', 'https://ddjj.com', 'publie', '2025-02-24 12:11:30');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
