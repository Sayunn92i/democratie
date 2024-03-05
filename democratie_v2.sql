-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 28 fév. 2024 à 10:07
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `democratie_v2`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_autorisation_aut`
--

DROP TABLE IF EXISTS `t_autorisation_aut`;
CREATE TABLE IF NOT EXISTS `t_autorisation_aut` (
  `id_aut` int(11) NOT NULL AUTO_INCREMENT,
  `id_uti_donateur` int(11) NOT NULL,
  `id_uti_beneficiaire` int(11) NOT NULL,
  `id_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_aut`),
  KEY `id_uti_donateur` (`id_uti_donateur`),
  KEY `id_uti_beneficiaire` (`id_uti_beneficiaire`),
  KEY `id_grp_aut` (`id_grp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_commentaire_com`
--

DROP TABLE IF EXISTS `t_commentaire_com`;
CREATE TABLE IF NOT EXISTS `t_commentaire_com` (
  `id_com` int(11) NOT NULL AUTO_INCREMENT,
  `contenu_com` varchar(255) NOT NULL,
  `num_debut_com` int(11) NOT NULL,
  `num_fin_com` int(11) NOT NULL,
  `datecrea_com` date NOT NULL,
  `id_pro` int(11) NOT NULL,
  `id_uti` int(11) NOT NULL,
  PRIMARY KEY (`id_com`),
  KEY `id_pro_idx` (`id_pro`),
  KEY `id_uti_idx` (`id_uti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_groupe_grp`
--

DROP TABLE IF EXISTS `t_groupe_grp`;
CREATE TABLE IF NOT EXISTS `t_groupe_grp` (
  `id_grp` int(11) NOT NULL AUTO_INCREMENT,
  `nom_grp` varchar(100) NOT NULL,
  `desc_grp` varchar(200) DEFAULT NULL,
  `type_grp` varchar(45) NOT NULL,
  `admin` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_grp`),
  UNIQUE KEY `nom_grp_UNIQUE` (`nom_grp`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_groupe_grp`
--

INSERT INTO `t_groupe_grp` (`id_grp`, `nom_grp`, `desc_grp`, `type_grp`, `admin`) VALUES
(1, 'Groupe1', 'Description du Groupe 1', 'public', 'JohnDoe'),
(2, 'Groupe2', 'Description du Groupe 2', 'privé', NULL),
(3, 'Groupe3', 'Description du Groupe 3', 'public', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `t_modification_mod`
--

DROP TABLE IF EXISTS `t_modification_mod`;
CREATE TABLE IF NOT EXISTS `t_modification_mod` (
  `id_mod` int(11) NOT NULL AUTO_INCREMENT,
  `contenumodif_com` text NOT NULL,
  `datecrea_mod` datetime NOT NULL,
  `id_pro` int(11) NOT NULL,
  `id_uti` int(11) NOT NULL,
  PRIMARY KEY (`id_mod`),
  KEY `id_pro_idx` (`id_pro`),
  KEY `id_uti_idx` (`id_uti`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_modification_mod`
--

INSERT INTO `t_modification_mod` (`id_mod`, `contenumodif_com`, `datecrea_mod`, `id_pro`, `id_uti`) VALUES
(1, '<p>test nouvelle modification donc juste un update</p>', '2023-12-12 14:25:21', 1, 1),
(2, '<p>test modification par un nouvel utilisateur</p>', '2023-12-12 14:32:10', 1, 2),
(3, '<p><span style=\"color: rgb(230, 0, 0);\">tesgyefguefguryfuyerufyrreee</span></p>', '2024-02-15 10:37:09', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `t_possede_pos`
--

DROP TABLE IF EXISTS `t_possede_pos`;
CREATE TABLE IF NOT EXISTS `t_possede_pos` (
  `id_uti` int(11) NOT NULL,
  `id_grp` int(11) NOT NULL,
  PRIMARY KEY (`id_uti`,`id_grp`),
  KEY `id_grp_idx` (`id_grp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_possede_pos`
--

INSERT INTO `t_possede_pos` (`id_uti`, `id_grp`) VALUES
(1, 1),
(2, 1),
(1, 2),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `t_proposition_pro`
--

DROP TABLE IF EXISTS `t_proposition_pro`;
CREATE TABLE IF NOT EXISTS `t_proposition_pro` (
  `id_pro` int(11) NOT NULL AUTO_INCREMENT,
  `titre_pro` varchar(100) DEFAULT NULL,
  `contenu_pro` text,
  `datecrea_pro` date DEFAULT NULL,
  `statut_pro` varchar(45) NOT NULL,
  `id_grp` int(11) NOT NULL,
  `verrou` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_pro`),
  KEY `id_grp_idx` (`id_grp`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_proposition_pro`
--

INSERT INTO `t_proposition_pro` (`id_pro`, `titre_pro`, `contenu_pro`, `datecrea_pro`, `statut_pro`, `id_grp`, `verrou`) VALUES
(1, 'Proposition 1', '<p><span style=\"color: rgb(230, 0, 0);\">tesgyefguefguryfuyerufyrreee</span></p>', '2023-10-17', 'En attente', 1, 0),
(2, 'Proposition 2', 'Contenu de la Proposition 2', '2023-10-16', 'Acceptée', 2, 0),
(3, 'Proposition 3', 'Contenu de la Proposition 3', '2023-10-15', 'Rejetée', 3, 0),
(4, 'Proposition 4', 'Contenu de la Proposition 4', '2023-10-14', 'En attente', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `t_reponsesondage_rep`
--

DROP TABLE IF EXISTS `t_reponsesondage_rep`;
CREATE TABLE IF NOT EXISTS `t_reponsesondage_rep` (
  `id_rep` int(11) NOT NULL AUTO_INCREMENT,
  `id_son` int(11) NOT NULL,
  `titre_rep` varchar(100) NOT NULL,
  `nb_votes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_rep`),
  KEY `id_son` (`id_son`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_sondage_son`
--

DROP TABLE IF EXISTS `t_sondage_son`;
CREATE TABLE IF NOT EXISTS `t_sondage_son` (
  `id_son` int(11) NOT NULL AUTO_INCREMENT,
  `titre_son` text NOT NULL,
  `id_createur` int(11) NOT NULL,
  PRIMARY KEY (`id_son`),
  KEY `id_createur` (`id_createur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_utilisateur_uti`
--

DROP TABLE IF EXISTS `t_utilisateur_uti`;
CREATE TABLE IF NOT EXISTS `t_utilisateur_uti` (
  `id_uti` int(11) NOT NULL AUTO_INCREMENT,
  `nom_uti` varchar(45) NOT NULL,
  `mdp_uti` char(64) NOT NULL,
  `email_uti` varchar(100) DEFAULT NULL,
  `type_uti` varchar(45) NOT NULL,
  PRIMARY KEY (`id_uti`),
  UNIQUE KEY `nom_uti_UNIQUE` (`nom_uti`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_utilisateur_uti`
--

INSERT INTO `t_utilisateur_uti` (`id_uti`, `nom_uti`, `mdp_uti`, `email_uti`, `type_uti`) VALUES
(1, 'JohnDoe', 'd3fa01281e1b21eff1c9669f3a5631d38c446d21da3608d506e79c90d5f2b40b', 'john.doe@example.com', 'Utilisateur'),
(2, 'JaneDoe', '02a4509ef417812e5717573116bfc75f90849c23d645e42f8169c32c299cd965', 'jane.doe@example.com', 'Admin'),
(3, 'BobSmith', 'a494d921b81d98b7df4e1789df1db82f92ebd9d98f21b21998a384cb84d5eb89', 'bob.smith@example.com', 'Utilisateur'),
(6, 'test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', 'test@test.com', 'Utilisateur');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_autorisation_aut`
--
ALTER TABLE `t_autorisation_aut`
  ADD CONSTRAINT `id_grp_aut` FOREIGN KEY (`id_grp`) REFERENCES `t_groupe_grp` (`id_grp`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_uti_beneficiaire` FOREIGN KEY (`id_uti_beneficiaire`) REFERENCES `t_utilisateur_uti` (`id_uti`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_uti_donateur` FOREIGN KEY (`id_uti_donateur`) REFERENCES `t_utilisateur_uti` (`id_uti`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `t_commentaire_com`
--
ALTER TABLE `t_commentaire_com`
  ADD CONSTRAINT `id_pro_com` FOREIGN KEY (`id_pro`) REFERENCES `t_proposition_pro` (`id_pro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_uti_com` FOREIGN KEY (`id_uti`) REFERENCES `t_utilisateur_uti` (`id_uti`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `t_modification_mod`
--
ALTER TABLE `t_modification_mod`
  ADD CONSTRAINT `id_pro_mod` FOREIGN KEY (`id_pro`) REFERENCES `t_proposition_pro` (`id_pro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_uti_mod` FOREIGN KEY (`id_uti`) REFERENCES `t_utilisateur_uti` (`id_uti`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `t_possede_pos`
--
ALTER TABLE `t_possede_pos`
  ADD CONSTRAINT `id_grp` FOREIGN KEY (`id_grp`) REFERENCES `t_groupe_grp` (`id_grp`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_uti` FOREIGN KEY (`id_uti`) REFERENCES `t_utilisateur_uti` (`id_uti`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `t_proposition_pro`
--
ALTER TABLE `t_proposition_pro`
  ADD CONSTRAINT `t_proposition_pro_ibfk_1` FOREIGN KEY (`id_grp`) REFERENCES `t_groupe_grp` (`id_grp`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `t_reponsesondage_rep`
--
ALTER TABLE `t_reponsesondage_rep`
  ADD CONSTRAINT `t_reponsesondage_rep_ibfk_1` FOREIGN KEY (`id_son`) REFERENCES `t_sondage_son` (`id_son`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `t_sondage_son`
--
ALTER TABLE `t_sondage_son`
  ADD CONSTRAINT `t_sondage_son_ibfk_1` FOREIGN KEY (`id_createur`) REFERENCES `t_utilisateur_uti` (`id_uti`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
