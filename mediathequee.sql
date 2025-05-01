-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 01 mai 2025 à 01:13
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mediathequee`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnements`
--

DROP TABLE IF EXISTS `abonnements`;
CREATE TABLE IF NOT EXISTS `abonnements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` enum('actif','expiré','contentieux','résilié') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'actif',
  `date_naissance` date DEFAULT NULL,
  `tarif` varchar(20) DEFAULT 'gratuit',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `abonnements`
--

INSERT INTO `abonnements` (`id`, `nom`, `email`, `date_debut`, `date_fin`, `statut`, `date_naissance`, `tarif`) VALUES
(8, '', 'arvinartic@gmail.com', '2025-05-01', '2026-05-01', 'actif', NULL, 'gratuit'),
(6, '', 'maaarvinartic@gmail.com', '2025-04-30', '2026-04-30', 'actif', NULL, 'gratuit'),
(7, '', 'mervinartic@gmail.com', '2025-05-01', '2026-05-01', '', NULL, 'gratuit');

-- --------------------------------------------------------

--
-- Structure de la table `adherents`
--

DROP TABLE IF EXISTS `adherents`;
CREATE TABLE IF NOT EXISTS `adherents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `date_inscription` date NOT NULL,
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `date_naissance` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `adherents`
--

INSERT INTO `adherents` (`id`, `nom`, `prenom`, `email`, `date_inscription`, `statut`, `date_naissance`) VALUES
(13, 'Artic', 'Marvin', 'maaarvinartic@gmail.com', '2025-04-30', 'actif', '2010-06-11'),
(14, 'Artic', 'Marvin', 'mervinartic@gmail.com', '2025-04-30', 'inactif', '2010-06-11'),
(15, 'Artic', 'Marvin', 'arvinartic@gmail.com', '2025-05-01', 'actif', '2010-06-11'),
(16, 'artic', NULL, 'mazzrvinartic@gmail.com', '2025-05-01', 'actif', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `cd`
--

DROP TABLE IF EXISTS `cd`;
CREATE TABLE IF NOT EXISTS `cd` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `artiste` varchar(255) DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `duree` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cd`
--

INSERT INTO `cd` (`id`, `titre`, `artiste`, `annee`, `genre`, `duree`, `image`) VALUES
(1, 'Random Access Memories', 'Daft Punk', 2013, 'Électronique', 74, 'img/punk.jpg'),
(2, 'Back to Black', 'Amy Winehouse', 2006, 'Soul', 35, 'img/btb.jpg'),
(3, 'Thriller', 'Michael Jackson', 1982, 'Pop', 42, 'img/thr.jpg'),
(4, 'The Dark Side of the Moon', 'Pink Floyd', 1973, 'Rock progressif', 43, 'img/tds.jpg'),
(5, 'Future Nostalgia', 'Dua Lipa', 2020, 'Pop', 37, 'img/nostalgia.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `demandes_abonnement`
--

DROP TABLE IF EXISTS `demandes_abonnement`;
CREATE TABLE IF NOT EXISTS `demandes_abonnement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `fichier_justificatif` varchar(255) NOT NULL,
  `statut` enum('en attente','accepté','refusé') DEFAULT 'en attente',
  `date_demande` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `demandes_abonnement`
--

INSERT INTO `demandes_abonnement` (`id`, `email`, `fichier_justificatif`, `statut`, `date_demande`) VALUES
(1, 'maaarvinartic@gmail.com', '681221e38e357_9f5528eacf59f89565b1f827ec0bd62f.jpg', 'accepté', '2025-04-30 15:13:07'),
(2, 'mervinartic@gmail.com', '68123a4e011a9_1308512.jpeg', 'refusé', '2025-04-30 16:57:18'),
(3, 'arvinartic@gmail.com', '6812bd6a91d3c_1308512.jpeg', 'accepté', '2025-05-01 02:16:42');

-- --------------------------------------------------------

--
-- Structure de la table `dvd`
--

DROP TABLE IF EXISTS `dvd`;
CREATE TABLE IF NOT EXISTS `dvd` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `realisateur` varchar(255) DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `duree` int DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `dvd`
--

INSERT INTO `dvd` (`id`, `titre`, `realisateur`, `annee`, `genre`, `duree`, `image`) VALUES
(6, 'Inception', 'Christopher Nolan', 2010, 'Science-fiction', 148, 'img/inception.jpg'),
(7, 'Le Fabuleux Destin d\'Amélie Poulain', 'Jean-Pierre Jeunet', 2001, 'Comédie romantique', 122, 'img/fabuleux.jpg'),
(8, 'Intouchables', 'Olivier Nakache et Éric Toledano', 2011, 'Comédie dramatique', 112, 'img/intou.jpg'),
(9, 'Avatar', 'James Cameron', 2009, 'Science-fiction', 162, 'img/avatar.jpg'),
(10, 'La Haine', 'Mathieu Kassovitz', 1995, 'Drame', 98, 'img/lh.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

DROP TABLE IF EXISTS `livres`;
CREATE TABLE IF NOT EXISTS `livres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `auteur` varchar(255) NOT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `imagee` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `titre`, `auteur`, `categorie`, `disponible`, `image`, `description`, `imagee`) VALUES
(11, '1984', 'George Orwell', 'Roman dystopique', 1, '/img/1984.jpg', 'Une dystopie classique décrivant un monde sous surveillance totale.', NULL),
(12, 'Le Petit Prince', 'Antoine de Saint-Exupéry', 'Conte philosophique', 1, '/img/prince.jpg', 'Un conte poétique et philosophique intemporel sur l’amitié, l’amour et l’essentiel.', NULL),
(13, 'Les Misérables', 'Victor Hugo', 'Classique', 1, '/img/mis.jpg', 'Une fresque sociale bouleversante sur la misère, l’injustice et la rédemption.', NULL),
(14, 'L’Étranger', 'Albert Camus', 'Philosophique', 1, '/img/etr.jpg', 'Un roman court, brut, sur l’absurde de la vie et l’indifférence humaine.', NULL),
(15, 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', 'Fantastique', 1, '/img/harry.jpg', 'C nul', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `magazine`
--

DROP TABLE IF EXISTS `magazine`;
CREATE TABLE IF NOT EXISTS `magazine` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `date_publication` date DEFAULT NULL,
  `sujet` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `magazine`
--

INSERT INTO `magazine` (`id`, `titre`, `numero`, `date_publication`, `sujet`, `image`) VALUES
(16, 'Science & Vie', '1234', '2024-10-01', 'Technologie et découverte', 'img/sv.jpg'),
(17, 'National Geographic', '892', '2024-09-01', 'Nature et exploration', 'img/geoo.jpg'),
(18, 'Le Monde Diplomatique', '2156', '2024-08-01', 'Géopolitique et économie', 'img/diplo.jpg'),
(19, 'Pour la Science', '567', '2024-07-01', 'Sciences et recherches', 'img/pls.jpg'),
(20, 'Philosophie Magazine', '210', '2024-06-01', 'Pensée contemporaine et société', 'img/philo.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `prets`
--

DROP TABLE IF EXISTS `prets`;
CREATE TABLE IF NOT EXISTS `prets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email_abonne` varchar(255) NOT NULL,
  `id_support` int NOT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour_prevue` date NOT NULL,
  `date_retour_reelle` date DEFAULT NULL,
  `statut` enum('en cours','retard','rendu') DEFAULT 'en cours',
  `type_support` varchar(20) NOT NULL DEFAULT 'livre',
  PRIMARY KEY (`id`),
  KEY `id_livre` (`id_support`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `prets`
--

INSERT INTO `prets` (`id`, `email_abonne`, `id_support`, `date_emprunt`, `date_retour_prevue`, `date_retour_reelle`, `statut`, `type_support`) VALUES
(1, 'tomg@gmail.com', 4, '2025-04-11', '2025-06-11', '2025-04-12', 'rendu', 'livre'),
(6, 'tomg@gmail.com', 6, '2025-04-12', '2025-06-11', '2025-04-28', 'rendu', 'livre'),
(4, 'marvinartic@gmail.com', 4, '2025-04-11', '2005-06-11', '2025-04-28', 'rendu', 'livre'),
(5, 'tomg@gmail.com', 5, '2025-04-12', '2025-04-14', '2025-04-12', 'rendu', 'livre'),
(12, 'maaarvinartic@gmail.com', 11, '2025-04-30', '2025-06-11', '2025-05-01', 'rendu', 'livre'),
(11, 'maaarvinartic@gmail.com', 4, '2025-04-30', '2025-06-11', '2025-05-01', 'rendu', 'livre'),
(10, 'maaarvinartic@gmail.com', 1, '2025-04-30', '2025-06-11', '2025-05-01', 'rendu', 'livre'),
(13, 'maaarvinartic@gmail.com', 6, '2025-04-30', '2010-06-11', NULL, 'retard', 'livre'),
(14, 'maaarvinartic@gmail.com', 6, '2025-04-30', '2025-06-11', NULL, 'en cours', 'dvd'),
(15, 'maaarvinartic@gmail.com', 11, '2025-04-30', '2025-04-30', NULL, 'retard', 'livre'),
(16, 'maaarvinartic@gmail.com', 12, '2025-04-30', '2025-02-11', '2025-05-01', 'rendu', 'livre'),
(17, 'arvinartic@gmail.com', 11, '2025-05-01', '2025-05-15', NULL, 'en cours', 'livre'),
(18, 'arvinartic@gmail.com', 6, '2025-05-01', '2025-05-15', NULL, 'en cours', 'dvd'),
(19, 'arvinartic@gmail.com', 1, '2025-05-01', '2025-05-15', NULL, 'en cours', 'cd'),
(20, 'arvinartic@gmail.com', 16, '2025-05-01', '2025-05-15', NULL, 'en cours', 'magazine');

-- --------------------------------------------------------

--
-- Structure de la table `ressource_numerique`
--

DROP TABLE IF EXISTS `ressource_numerique`;
CREATE TABLE IF NOT EXISTS `ressource_numerique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `auteur` varchar(255) DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `format` varchar(50) DEFAULT NULL,
  `lien` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ressource_numerique`
--

INSERT INTO `ressource_numerique` (`id`, `titre`, `auteur`, `annee`, `format`, `lien`) VALUES
(1, 'Introduction à l\'IA', 'Jean Dupont', 2023, 'PDF', 'https://example.com/ai.pdf'),
(2, 'Histoire de la musique', 'Claire Martin', 2022, 'EPUB', 'https://example.com/music.epub');

-- --------------------------------------------------------

--
-- Structure de la table `supports`
--

DROP TABLE IF EXISTS `supports`;
CREATE TABLE IF NOT EXISTS `supports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `type` enum('livre','dvd','cd','magazine','numerique') NOT NULL,
  `auteur` varchar(255) DEFAULT NULL,
  `realisateur` varchar(255) DEFAULT NULL,
  `artiste` varchar(255) DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','client') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'client',
  `id_adherent` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_user_adherent` (`id_adherent`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `id_adherent`) VALUES
(2, 'Admin', '', 'marvinartic@gmail.com', '$2y$10$ZmjnBEzNGGAcHnvc62WwmOyRb8ASDQEhAnji1VqDPQ9Ezd3K6Yl4.', 'admin', NULL),
(14, 'Artic', 'Marvin', 'maaarvinartic@gmail.com', '$2y$10$5jIARVsclbxmvDXnJbOl8OmQbQJEtW46jXoSkzYNIC.eduoM0LD56', 'client', 13),
(15, 'Artic', 'Marvin', 'mervinartic@gmail.com', '$2y$10$n5.msSPVKofGTVoiuRnOQ.0KMTU8g9hOE8RCqs7AdNTZzyTQTAJ3G', 'client', 14),
(16, 'Artic', 'Marvin', 'arvinartic@gmail.com', '$2y$10$86GtlBPDYBeJe5vuDRxLyuRGegE9Iv6hmH1/r33DX9UWWzc2caAEe', 'client', 15);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
