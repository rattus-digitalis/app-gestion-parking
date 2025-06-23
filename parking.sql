-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql:3306
-- Généré le : ven. 20 juin 2025 à 19:40
-- Version du serveur : 8.4.5
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `parkly`
--

-- --------------------------------------------------------

--
-- Structure de la table `parking`
--

CREATE TABLE `parking` (
  `id` int NOT NULL,
  `numero_place` varchar(10) NOT NULL,
  `etage` int DEFAULT '0',
  `type_place` enum('standard','handicap','electrique','moto') NOT NULL DEFAULT 'standard',
  `statut` enum('libre','occupe','reserve','maintenance') NOT NULL DEFAULT 'libre',
  `disponible_depuis` datetime DEFAULT NULL,
  `date_maj` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `derniere_reservation_id` int DEFAULT NULL,
  `commentaire` text,
  `actif` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `parking`
--

INSERT INTO `parking` (`id`, `numero_place`, `etage`, `type_place`, `statut`, `disponible_depuis`, `date_maj`, `derniere_reservation_id`, `commentaire`, `actif`) VALUES
(1, 'A1', 0, 'standard', 'libre', '2025-05-17 08:00:00', '2025-05-18 05:00:17', NULL, 'RAS', 1),
(2, 'A2', 0, 'standard', 'occupe', NULL, '2025-05-18 05:00:17', NULL, 'Voiture prsente', 1),
(3, 'A3', 0, 'electrique', 'libre', '2025-05-17 06:30:00', '2025-05-18 05:00:17', NULL, 'Chargeur dispo', 1),
(4, 'B1', 1, 'moto', 'reserve', NULL, '2025-05-18 05:00:17', NULL, 'Rservation  14h', 1),
(5, 'B2', 1, 'handicap', 'libre', '2025-05-17 09:15:00', '2025-05-18 05:00:17', NULL, 'Proche ascenseur', 1),
(6, '001', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(7, '002', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(8, '003', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(9, '004', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(10, '005', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(11, '006', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(12, '007', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(13, '008', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(14, '009', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(15, '010', 0, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(16, '011', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(17, '012', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(18, '013', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(19, '014', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(20, '015', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(21, '016', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(22, '017', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(23, '018', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(24, '019', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(25, '020', 1, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(26, '021', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(27, '022', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(28, '023', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(29, '024', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(30, '025', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(31, '026', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(32, '027', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(33, '028', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(34, '029', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(35, '030', 2, 'standard', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(36, '031', 0, 'handicap', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(37, '032', 0, 'handicap', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(38, '033', 0, 'handicap', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(39, '034', 0, 'handicap', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(40, '035', 0, 'handicap', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(41, '036', 1, 'electrique', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(42, '037', 1, 'electrique', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(43, '038', 1, 'electrique', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(44, '039', 1, 'electrique', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(45, '040', 1, 'electrique', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(46, '041', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(47, '042', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(48, '043', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(49, '044', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(50, '045', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(51, '046', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(52, '047', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(53, '048', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(54, '049', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1),
(55, '050', 2, 'moto', 'libre', NULL, '2025-05-26 13:34:49', NULL, NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `parking`
--
ALTER TABLE `parking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_place` (`numero_place`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `parking`
--
ALTER TABLE `parking`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
