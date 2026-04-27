-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 17 jan. 2026 à 23:20
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
-- Base de données : `auction_site`
--

-- --------------------------------------------------------

--
-- Structure de la table `belongsto`
--

DROP TABLE IF EXISTS `belongsto`;
CREATE TABLE IF NOT EXISTS `belongsto` (
  `id_product` int NOT NULL,
  `id_category` int NOT NULL,
  PRIMARY KEY (`id_product`,`id_category`),
  KEY `id_category` (`id_category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `belongsto`
--

INSERT INTO `belongsto` (`id_product`, `id_category`) VALUES
(1, 1),
(2, 8),
(3, 4),
(4, 6),
(5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `bid`
--

DROP TABLE IF EXISTS `bid`;
CREATE TABLE IF NOT EXISTS `bid` (
  `id_product` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `current_price` decimal(15,2) DEFAULT NULL,
  `new_price` decimal(15,2) DEFAULT NULL,
  `bid_date` datetime DEFAULT NULL,
  KEY `id_user` (`id_user`),
  KEY `fk_bid_product` (`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `bid`
--

INSERT INTO `bid` (`id_product`, `id_user`, `current_price`, `new_price`, `bid_date`) VALUES
(3, 3, NULL, 50.00, '2026-01-18 00:09:08'),
(1, 5, NULL, 230.00, '2026-01-18 00:10:09'),
(1, 4, 230.00, 750.00, '2026-01-18 00:11:34'),
(1, 3, 750.00, 900.00, '2026-01-18 10:20:00'),
(1, 5, 900.00, 1200.00, '2026-01-18 11:10:00'),
(5, 4, NULL, 400.00, '2026-01-18 10:00:00'),
(5, 5, 400.00, 650.00, '2026-01-18 11:30:00'),
(5, 3, 650.00, 850.00, '2026-01-18 12:15:00');

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id_category`, `name`, `statut`) VALUES
(1, 'Automobile', 1),
(2, 'Sportif', 1),
(3, 'Artiste', 1),
(4, 'Acteur', 1),
(5, 'Dessinateur', 1),
(6, 'Musicien', 1),
(7, 'Informatique', 1),
(8, 'Influenceur', 0);

-- --------------------------------------------------------

--
-- Structure de la table `celebrity`
--

DROP TABLE IF EXISTS `celebrity`;
CREATE TABLE IF NOT EXISTS `celebrity` (
  `id_celebrity` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `statut` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_celebrity`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `celebrity`
--

INSERT INTO `celebrity` (`id_celebrity`, `name`, `url`, `statut`) VALUES
(1, 'Michael Schumacher', NULL, 1),
(2, 'Cristiano Ronaldo', NULL, 1),
(3, 'Angelina Jolie', NULL, 1),
(4, 'Banksy', NULL, 1),
(5, 'Daft Punk', NULL, 1),
(6, 'Furious Jumper', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id_product` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `comment` varchar(550) DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL,
  KEY `id_user` (`id_user`),
  KEY `fk_comment_product` (`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Structure de la table `concerned`
--

DROP TABLE IF EXISTS `concerned`;
CREATE TABLE IF NOT EXISTS `concerned` (
  `id_product` int NOT NULL,
  `id_celebrity` int NOT NULL,
  PRIMARY KEY (`id_product`,`id_celebrity`),
  KEY `id_celebrity` (`id_celebrity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `concerned`
--

INSERT INTO `concerned` (`id_product`, `id_celebrity`) VALUES
(1, 2),
(2, 6),
(3, 3),
(4, 5),
(5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id_image` int NOT NULL AUTO_INCREMENT,
  `id_product` int DEFAULT NULL,
  `path_image` varchar(250) DEFAULT NULL,
  `alt` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_image`),
  KEY `fk_image_product` (`id_product`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id_image`, `id_product`, `path_image`, `alt`) VALUES
(1, 1, 'Annonce/1/1_0.jpg', '1_0.jpg'),
(2, 1, 'Annonce/1/1_1.jpg', '1_1.jpg'),
(3, 2, 'Annonce/2/2_0.jpg', '2_0.jpg'),
(4, 3, 'Annonce/3/3_0.jpg', '3_0.jpg'),
(5, 3, 'Annonce/3/3_1.jpg', '3_1.jpg'),
(6, 4, 'Annonce/4/4_0.jpg', '4_0.jpg'),
(7, 4, 'Annonce/4/4_1.jpg', '4_1.jpg'),
(8, 5, 'Annonce/5/5_0.jpg', '5_0.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `interest`
--

DROP TABLE IF EXISTS `interest`;
CREATE TABLE IF NOT EXISTS `interest` (
  `id_product` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  KEY `id_user` (`id_user`),
  KEY `fk_interest_product` (`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `interest`
--

INSERT INTO `interest` (`id_product`, `id_user`) VALUES
(5, 3),
(3, 3),
(3, 5),
(1, 5),
(1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id_product` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(5000) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `reserve_price` decimal(15,2) DEFAULT NULL,
  `start_price` decimal(15,2) DEFAULT '0.00',
  `status` tinyint(1) DEFAULT '0',
  `mailIsSent` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_product`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id_product`, `title`, `description`, `start_date`, `end_date`, `reserve_price`, `start_price`, `status`, `mailIsSent`) VALUES
(1, 'Lamborghini', 'Découvrez une pièce automobile d’exception : une Lamborghini ayant appartenu à Cristiano Ronaldo, l’un des sportifs les plus célèbres et admirés au monde. Ce véhicule ne se contente pas d’incarner la performance et l’audace caractéristiques de la marque italienne, il porte également l’empreinte d’une icône internationale dont le goût pour les supercars est reconnu dans le monde entier. Posséder une Lamborghini issue de la collection personnelle de Ronaldo, c’est accéder à un niveau de prestige extrêmement rare. Cette Lamborghini se distingue par son design agressif, ses lignes acérées et son allure résolument sportive. Chaque courbe a été pensée pour optimiser l’aérodynamisme et offrir une présence visuelle incomparable. Sous le capot, le moteur délivre une puissance impressionnante, offrant des accélérations explosives et une tenue de route irréprochable. La sonorité profonde et envoûtante du moteur rappelle immédiatement l’ADN Lamborghini : brut, intense et sans compromis. L’habitacle, quant à lui, combine luxe et sportivité, avec des matériaux haut de gamme, des finitions précises et une ergonomie pensée pour le plaisir de conduite. Le fait que ce modèle ait appartenu à Cristiano Ronaldo lui confère une valeur historique et émotionnelle unique. L’athlète est connu pour entretenir ses véhicules avec un soin méticuleux, et cette Lamborghini ne fait pas exception. Son état général témoigne d’une attention constante et d’un respect absolu pour la mécanique. Elle représente non seulement une supercar d’exception, mais aussi un objet de collection chargé d’histoire, associé à l’un des plus grands joueurs de football de tous les temps. Proposée aujourd’hui aux enchères, cette Lamborghini offre une opportunité extrêmement rare d’acquérir un véhicule prestigieux, alliant performance extrême, design iconique et provenance exceptionnelle. Que vous soyez collectionneur, passionné d’automobile ou investisseur, cette pièce unique constitue un ajout incomparable à tout patrimoine. Préparez-vous à prendre le volant d’une véritable légende, à la fois mécanique et sportive.', '2025-01-06 00:00:00', '2026-02-08 00:00:00', NULL, 0.00, 1, 0),
(2, 'T01: La Vidéo de tous les dangers dédicacée', 'Plongez dans l’univers explosif et déjanté de Furious Jumper avec ce premier tome intitulé La Vidéo de tous les dangers, une bande dessinée inspirée de l’un des créateurs de contenu les plus populaires de la scène gaming francophone. Cette édition rare et recherchée est dédicacée, ce qui en fait un véritable objet de collection pour les fans comme pour les amateurs de BD modernes. Dans ce premier volume, Furious Jumper se retrouve entraîné dans une aventure aussi imprévisible que palpitante. Ce qui devait être une simple vidéo se transforme rapidement en mission périlleuse, mêlant humour, action et rebondissements. Le récit, rythmé et accessible, séduira aussi bien les jeunes lecteurs que les passionnés d’univers fantastiques. Les illustrations dynamiques d’Emmanuel Nhieu donnent vie à un monde coloré, vivant et rempli de créatures étonnantes, tout en conservant l’esprit fun et énergique du YouTuber. L’exemplaire proposé ici bénéficie d’une dédicace authentique, ajoutant une dimension unique et personnelle à l’ouvrage. Ce type d’édition est particulièrement prisé, car il témoigne d’un lien direct entre l’auteur, l’artiste et le lecteur. Que vous soyez collectionneur, fan de Furious Jumper ou simplement amateur de belles BD, cette version dédicacée représente une opportunité rare d’acquérir un tome à la fois divertissant, original et chargé de valeur sentimentale. En excellent état et prêt à rejoindre une collection ou à être offert, Furious Jumper T01 : La Vidéo de tous les dangers dédicacée est une pièce incontournable pour tous ceux qui souhaitent posséder un ouvrage unique, à la croisée du gaming, de l’aventure et de la bande dessinée contemporaine.', '2024-12-17 00:00:00', '2026-02-26 00:00:00', NULL, 0.00, 0, 0),
(3, 'Un script de film', 'Découvrez une pièce exceptionnelle du patrimoine cinématographique : un script de film authentique annoté par Angelina Jolie, l’une des actrices les plus influentes et respectées de sa génération. Cet objet rare offre un accès privilégié aux coulisses de son travail, révélant la précision, la sensibilité et l’exigence artistique qui ont façonné sa carrière internationale. Ce script contient de véritables annotations manuscrites de l’actrice : remarques sur les émotions à transmettre, indications de jeu, réflexions personnelles sur certaines scènes, ajustements de dialogues ou notes techniques destinées à affiner son interprétation. Ces traces directes de son processus créatif confèrent à l’ouvrage une valeur unique, à la fois historique et artistique. Chaque page témoigne de l’implication profonde d’Angelina Jolie dans la construction de ses personnages, offrant un regard intime sur sa méthode de travail. Au-delà de son intérêt cinéphile, ce script représente un objet de collection prestigieux, recherché par les passionnés de cinéma, les admirateurs de l’actrice et les collectionneurs d’artefacts hollywoodiens. Sa provenance, associée à une figure emblématique du cinéma contemporain, en fait une pièce rare dont la valeur ne cesse de croître. Conservé avec soin, l’exemplaire est en excellent état, préservant parfaitement les annotations et la structure originale du document. Proposé aujourd’hui aux enchères, ce script annoté par Angelina Jolie constitue une opportunité exceptionnelle d’acquérir un objet chargé d’histoire, témoin direct du travail d’une artiste mondialement reconnue. Une pièce unique, à la croisée de l’art, du cinéma et de la mémoire culturelle, prête à rejoindre une collection d’exception.', '2025-08-14 00:00:00', '2026-02-05 00:00:00', NULL, 0.00, 1, 0),
(4, 'Casque audio', 'Plongez au cœur de la légende électro française avec ce casque authentique ayant appartenu à Daft Punk, le duo mythique qui a marqué l’histoire de la musique électronique mondiale. Véritable symbole de leur identité artistique, le casque représente bien plus qu’un simple accessoire : c’est une icône culturelle, un fragment tangible de l’univers mystérieux et futuriste qui a façonné leur succès planétaire. Ce modèle, soigneusement conservé, reflète l’esthétique unique du groupe : lignes épurées, design avant‑gardiste et finition impeccable. Chaque détail rappelle l’aura énigmatique de Thomas Bangalter et Guy‑Manuel de Homem‑Christo, qui ont fait du casque un élément central de leur image publique. Porté lors de sessions de travail ou d’événements privés, cet objet rare témoigne de l’exigence artistique et du perfectionnisme qui ont toujours caractérisé le duo. L’état général du casque est remarquable, préservant son allure emblématique et son caractère collector. Sa provenance, associée à l’un des groupes les plus influents de la scène électro, lui confère une valeur exceptionnelle. Les objets liés à Daft Punk sont extrêmement recherchés, notamment depuis la fin officielle du duo, ce qui renforce encore l’intérêt historique et émotionnel de cette pièce. Proposé aujourd’hui aux enchères, ce casque représente une opportunité unique d’acquérir un objet chargé d’histoire, intimement lié à l’un des plus grands phénomènes musicaux de notre époque. Que vous soyez collectionneur, passionné de musique, amateur de culture pop ou investisseur, cette pièce iconique constitue un ajout incomparable à toute collection prestigieuse.', '2026-01-01 00:00:00', '2026-01-18 00:01:02', 800.00, 0.00, 1, 1),
(5, 'Casque de course', 'Découvrez une pièce exceptionnelle de l’histoire du sport automobile : un casque authentique porté par Michael Schumacher, le pilote le plus titré de la Formule 1 moderne et une véritable légende du sport. Cet objet rare incarne à lui seul la passion, la précision et l’intensité qui ont marqué la carrière du septuple champion du monde. Ce casque, immédiatement reconnaissable par son design emblématique, reflète l’identité visuelle forte de Schumacher : couleurs vives, motifs distinctifs et finitions soignées. Porté lors de sessions officielles, il témoigne de l’engagement total du pilote, de sa concentration extrême et de son style de pilotage unique. Chaque trace d’usure, chaque détail de la coque raconte une histoire, celle d’un champion qui a repoussé les limites de la performance et marqué à jamais l’histoire de la F1. Conservé avec le plus grand soin, ce casque présente un état remarquable pour un objet de cette importance. Sa provenance, associée à l’un des sportifs les plus admirés au monde, lui confère une valeur historique et émotionnelle exceptionnelle. Les pièces authentiques liées à Michael Schumacher sont extrêmement recherchées par les collectionneurs, et leur rareté ne cesse d’accroître leur prestige et leur valeur sur le marché. Proposé aujourd’hui aux enchères, ce casque représente une opportunité unique d’acquérir un objet mythique, symbole d’une carrière hors du commun. Que vous soyez passionné de Formule 1, collectionneur d’objets sportifs ou investisseur averti, cette pièce iconique constitue un ajout incomparable à toute collection d’exception. Un véritable fragment de légende, prêt à rejoindre les mains d’un nouveau propriétaire.', '2025-12-31 00:00:00', '2026-02-08 00:00:00', NULL, 0.00, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `productview`
--

DROP TABLE IF EXISTS `productview`;
CREATE TABLE IF NOT EXISTS `productview` (
  `id_product` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `view_number` int DEFAULT NULL,
  `view_date` datetime DEFAULT NULL,
  KEY `id_user` (`id_user`),
  KEY `fk_productview_product` (`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `productview`
--

INSERT INTO `productview` (`id_product`, `id_user`, `view_number`, `view_date`) VALUES
(5, 3, 1, '2026-01-18 00:08:38'),
(3, 5, 1, '2026-01-18 00:09:01'),
(1, 3, 1, '2026-01-18 00:10:01'),
(1, 4, 1, '2026-01-18 10:05:00'),
(1, 5, 1, '2026-01-18 10:10:00'),
(1, 4, 1, '2026-01-18 12:30:00'),
(5, 4, 1, '2026-01-18 11:00:00'),
(5, 5, 1, '2026-01-18 11:45:00'),
(5, 3, 1, '2026-01-18 13:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `published`
--

DROP TABLE IF EXISTS `published`;
CREATE TABLE IF NOT EXISTS `published` (
  `id_user` int NOT NULL,
  `id_product` int NOT NULL,
  KEY `id_user` (`id_user`),
  KEY `fk_published_product` (`id_product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `published`
--

INSERT INTO `published` (`id_user`, `id_product`) VALUES
(3, 1),
(3, 2),
(5, 3),
(5, 4),
(3, 5);

-- --------------------------------------------------------

--
-- Structure de la table `rating`
--

DROP TABLE IF EXISTS `rating`;
CREATE TABLE IF NOT EXISTS `rating` (
  `id_buyer` int DEFAULT NULL,
  `id_seller` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  KEY `id_buyer` (`id_buyer`),
  KEY `id_seller` (`id_seller`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `newsletter` tinyint(1) DEFAULT '0',
  `admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `name`, `firstname`, `birth_date`, `address`, `city`, `postal_code`, `email`, `password`, `newsletter`, `admin`) VALUES
(3, 'test', 'test', '2026-01-12', 'blablabla', 'blablabla', '58000', 'test@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SXZsLktHM2l3UlhLSTdCeQ$IxIV3pjNBb/fOFk5PCudTrHyGS1Xdw7VeEIshnanyhg', 0, 0),
(4, 'admin', 'admin', '2026-01-12', 'blablabla', 'blablabla', '58000', 'admin@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$b1Y2WUF1UXBCbTdyZW55Ng$LOqQ4IgcRdKmKzCwhUKrgn5afyfZUDP83FepnbOIrVQ', 0, 1),
(5, 'Garnier', 'Jimmy', '2026-10-11', 'blablabla', 'blablabla', '58000', 'jimmygarnier11@outlook.fr', '$argon2id$v=19$m=65536,t=4,p=1$ejJQRGFFY3IzU2NSVy9pdA$ITH3RZq/iaKL/tAa0xlO2v4kjhIpRwATHxhZVfKuZ2Y', 0, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

UPDATE product
SET start_date = NOW() - INTERVAL 1 DAY,
    end_date = NOW() + INTERVAL 90 DAY,
    status = 1;

