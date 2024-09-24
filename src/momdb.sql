-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 27 août 2024 à 16:14
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `momdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id`, `description`) VALUES
(1, 'Alimentation'),
(2, 'Boisson'),
(3, 'Plats');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240717083540', '2024-07-22 16:25:44', 16);

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `plage_heure` varchar(20) DEFAULT NULL,
  `lieu` varchar(255) NOT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `photo1` varchar(255) NOT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `visible_web` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`id`, `user_id`, `titre`, `description`, `date`, `plage_heure`, `lieu`, `prix`, `photo1`, `photo2`, `visible_web`) VALUES
(1, 2, 'Colombia en Grenoble', 'Le groupe musical Canalon de Timbiqui et Nidia Gongora', '2024-07-11', '20h20', 'Jardin de Ville', 0.00, 'colombiaEnGre-66ca571ef3987.jpeg', NULL, 1),
(3, 2, 'Grand Bal Colombien', 'Fête de la Colombie, danses et cours de danses..', '2024-07-19', '18-22h', 'Le Prunier Sauvage - Parc Bachelard', NULL, 'balColombien.jpeg', NULL, 1),
(4, 2, 'Fête de la femme', 'Orchestre plus concert de Wendy Chacon', '2024-08-30', '20h20', 'Restaurant Viva Mexico', 20.00, 'wendyChacon2024-66ca5d89e48d9.jpeg', NULL, 1),
(5, 2, 'Programme Juillet 2024', 'Venez déguster nos délicieux plats et danser avec nous.', '2024-07-01', NULL, 'Divers', 14.00, 'juillet2024-66cb0328d9a68.jpeg', NULL, 1),
(6, 2, 'Fête d\'équateur', 'Venez célébrer avec nous la fête d\'équateur et mangez un ceviche equatorien', '2024-08-03', '11 à 14h', 'Magasin', 10.00, 'FeteEquateur-66cb023a235c6.jpeg', NULL, 1),
(7, 2, 'Grand Bal Colombien', 'xcv', '2024-08-15', '19h-22h', 'Le Prunier Sauvage - Parc Bachelard', NULL, 'FestivalLatino-66cb388b42a50.jpeg', NULL, 0),
(8, 2, 'tset', 'dsqg ds', '2024-09-04', '18-22h', 'dsg', 0.00, 'FeteEquateur-66cb46beaa948.jpeg', NULL, 0),
(9, 2, 'Soirée de filles', 'Grande Soirée de Filles', '2024-07-28', '18-22h', 'Magasin', NULL, 'nocheChicas-66cc594b2e651.jpg', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":5:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:169:\\\"https://127.0.0.1:8000/verify/email?expires=1721291482&id=1&signature=9tFql85tr6vnkf0Mtta56ovWuP5hYZDkB0exQPF2M2Q%3D&token=beW1tINh8SROSVW8x8wFeflrZJfrP3YGZxna89M2JTU%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:18:\\\"eselene1@yahoo.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:18:\\\"Momposina Mail Bot\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:19:\\\"eviougeas@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:25:\\\"Please Confirm your Email\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}i:4;N;}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2024-07-18 07:31:23', '2024-07-18 07:31:23', NULL),(2, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":5:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:177:\\\"https://127.0.0.1:8000/verify/email?expires=1721293155&id=2&signature=mhQX113PPlHLsgIyx72BJTxtrMZrdUNbgb9J2AkjRKo%3D&token=AWY41MRpw%2FKA5%2F%2FZPwGba5IS%2BloNXGYbnChj2r9hsjY%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:18:\\\"eselene1@yahoo.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:18:\\\"Momposina Mail Bot\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:20:\\\"eviougeasa@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:25:\\\"Please Confirm your Email\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}i:4;N;}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2024-07-18 07:59:15', '2024-07-18 07:59:15', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sous_categorie_id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_es` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `pays` varchar(30) DEFAULT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `poids` decimal(10,2) DEFAULT NULL,
  `unite_poids` varchar(10) DEFAULT NULL,
  `ingredients` varchar(255) DEFAULT NULL,
  `allergenes` varchar(255) DEFAULT NULL,
  `certification` varchar(50) DEFAULT NULL,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `visible_web` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `user_id`, `sous_categorie_id`, `nom`, `nom_es`, `description`, `pays`, `marque`, `poids`, `unite_poids`, `ingredients`, `allergenes`, `certification`, `photo1`, `photo2`, `prix`, `visible_web`) VALUES
(9, 2, 6, 'far fr 9', 'far es  9', 'Poudre obtenue en broyant des grains de maïs, cette farine peut servir autant à la fabrication de pain qu’à d’autres plats, dont « las Arepas » et la célèbre « Hallacas »Poudre obte2 farine peut servir autant à la fabrication de pain qu’à d’autres plats,', 'France', 'pabillon', NULL, NULL, NULL, NULL, NULL, 'Aain-s-Creed-Origins-PS4-66601deeafebe-669ec998bc0b2-669fe70b43c21.jpeg', NULL, NULL, 1),
(10, 2, 6, 'Corona Light', 'Cerveza light Corona', 'bière légère de style pilsner, appréciée pour son goût rafraîchissant et sa faible teneur en calories. Avec un taux d’alcool de 4,0% et seulement 99 calories par bouteille de 355 ml, elle est idéale pour ceux qui recherchent une bière savoureuse mais lég', NULL, 'Corona', NULL, NULL, NULL, NULL, NULL, 'b-corona-66a7ff809822f-66cb9b1a20988.jpeg', NULL, NULL, 0),
(11, 2, 6, 'far fr  111111111111111', NULL, 'dsfq desc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'The-Last-of-Us-Part-II-669fa685c4c01.png', NULL, NULL, 1),
(12, 2, 6, 'pro12', NULL, 'desc12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Animal-Crossing-New-Horizons-669e816e79fdb.jpg', NULL, NULL, 1),
(13, 5, 2, 'reter 13', 'rtzr es', '13E  Poudre obtenue en broyant des grains de maïs, cette farine peut servir autant à la fabrication de pain qu’à d’autres plats, dont « las Arepas » et la célèbre « Hallacas »', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'nintendo-switch-669eaade888d6.jpg', NULL, NULL, 1),
(14, 4, 8, 'far fr14', NULL, 'qsdfghjk;', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aain-s-Creed-Origins-PS4-66601deeafebe-669ec998bc0b2.jpg', NULL, NULL, 1),
(15, 2, 2, 'farine 2', NULL, 'fdqds\r\nfdq\r\nqsd\r\ns', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'faviconV2-669fa3b1acbb9.png', NULL, NULL, 1),
(16, 2, 2, 'far fr dfqsd', 'dsqqs  es', 'sdfghjklm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Zombi-U-Wii-U-669f7896c3e4e-669faa4f8c672.jpg', NULL, NULL, 1),
(17, 3, 2, 'far fr17', NULL, 'dfqsdfqds  17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Aain-s-Creed-Origins-PS4-66a01721b3d80.jpg', NULL, NULL, 1),
(18, 3, 2, 'sdfghj', NULL, 'sdfvbn,;:', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a-66a01c4276a17.png', NULL, NULL, 1),
(19, 2, 3, 'Empanadas', NULL, 'Chaussons à base de farine de maïs  farcis à la viande, poulet et végétarien tous les jours.\r\nEmpanadas colombianas caseras de Carne, pollo y vegetarianas todos los dias.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empanadas-66cb9564b8c9d.jpeg', NULL, NULL, 1),
(20, 2, 3, 'Tamales', NULL, 'Plat à base de pois chiches, viandes et legumes cuisiné dans une papillote  de banane plantain.\r\nOption végétarienne disponible.\r\nPlato a base de masa de garbanzo, de carnes y verduras.  El conjunto es envuelto en una hoja de platano y cocinado dur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Tamales-66cb95a71e0f0.jpeg', NULL, NULL, 1),
(21, 2, 3, 'Sancocho', NULL, 'Soupe de viande à base de manioc, pomme de terre et banane plantain. Viens accompagné de riz hogao et guacamole.\r\n\r\nSopa hecha con diferentes tipos de carnes a base de yuca, papa y platano verde. Viene acompañado de arroz, hogao y guacamole.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sancocho-66cb95d12878d.jpeg', NULL, NULL, 1),
(22, 2, 3, 'Lechona', NULL, 'Porc effiloché assaisonné avec des condiments traditionnels.\r\n\r\nPlato cuyo ingrediente principal es la carne de cerdo cocinada durante varias horas y adobada con especias tradicionales.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Lechona-66cb95eb11941.jpeg', NULL, NULL, 1),
(23, 2, 3, 'Chicharron', NULL, 'Poitrine de porc frite accompagnée de Pommes de Terre.\r\n\r\nTiras gruesas de tocino crocante servido con papa salada', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Chicharron-66cb96881663f.jpeg', NULL, NULL, 0),
(24, 2, 3, 'Bandeja Paisa', NULL, 'Des haricots rouges, du riz blanc, de la viande de bœuf hachée, du chicharrón, du chorizo, de la morcilla, un œuf frit, des bananes plantains frites, de l’avocat et une arepa. \r\nFrijoles rojos, arroz blanco, carne molida, chicharrón (piel de cerdo frita),', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BandejaPaisa-66cb971d1c2dd.jpeg', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `evenement_id` int(11) NOT NULL,
  `nom_participant` varchar(80) NOT NULL,
  `prenom_participant` varchar(80) NOT NULL,
  `email_participant` varchar(180) NOT NULL,
  `telephone_participant` varchar(20) DEFAULT NULL,
  `nombre_places` smallint(6) NOT NULL,
  `date_reservation` date NOT NULL,
  `mode_paiement` varchar(50) DEFAULT NULL,
  `mail_reserva_envoye` tinyint(1) NOT NULL,
  `status_reservation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sous_categorie`
--

CREATE TABLE `sous_categorie` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `description` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sous_categorie`
--

INSERT INTO `sous_categorie` (`id`, `categorie_id`, `description`) VALUES
(1, 1, 'Alimentation'),
(2, 2, 'Boisson'),
(3, 3, 'Plats'),
(4, 1, 'Confiserie'),
(5, 1, 'Farines'),
(6, 1, 'Fromages'),
(7, 1, 'Sauces & Épices '),
(8, 1, 'Saucisses'),
(9, 2, 'Bières'),
(10, 2, 'Cafés'),
(11, 2, 'Mate'),
(12, 2, 'Spiritueux'),
(13, 2, 'Vins');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `nom` varchar(121) NOT NULL,
  `prenom` varchar(120) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(120) DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `etat` varchar(50) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `genre` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `telephone`, `adresse`, `code_postal`, `ville`, `pays`, `etat`, `is_verified`, `genre`) VALUES
(2, 'eviougeasa@gmail.com', '[]', '$2y$13$JWOiWYC6QNNemiMc5TsZrewMFItO86XjwaqeDZEZMaSufu6RX38AO', 'Viougeas', 'ELNA', '0608079850', '11 rue Jean Marie Tjibaou', '38400', 'SAINT MARTIN D HERES', 'fr', 'actif', 0, ''),
(3, 'eviougeasafzedrftgqd@gmail.com', '[]', '$2y$13$w1VjD03iJcLE6G2xXbUe3u4P7uJPkZNVbdgd8dQIqcvzANgvY1HwC', 't', 'to', NULL, NULL, NULL, NULL, NULL, 'actif', 0, 'Monsieur'),
(4, 'eviougeas2aaaa@gmail.com', '[]', '$2y$13$oaFLYbBRxmvzq8GEojkwpuE8D/kkPpFioUU40u4lYMV9BPEvKB1Zu', 'b', 'b', NULL, 'b', 'B', 'b', NULL, 'actif', 0, 'Madame'),
(5, 'eviougeaszzzzzzzzzzzzzz@gmail.com', '[]', '$2y$13$WqB2wgeFUPZuXUZp1ZfJ2.B1519kIdimTTFzTcZP60OWBK976ZxLe', 'Viougeas', 'ELNA', '0608079850', '11 rue Jean Marie Tjibaou', '38400', 'SAINT MARTIN D HERES', NULL, 'actif', 0, 'Madame'),
(6, 'eviougeawxcfgs@gmail.com', '[]', '$2y$13$lfJbxsgrDTspaWGdiEMst.pcL3WePxPXZZUAAAaDBNatKqhoDkmDm', 'Viougeas', 'ELNA', '0608079850', '11 rue Jean Marie Tjibaou', '38400', 'SAINT MARTIN D HERES', NULL, 'actif', 0, 'Madame'),
(7, 'eseleneeeeeeeeeeeeeeee1@yahoo.com', '[]', '$2y$13$Zqjt3MGVW6BvwjtTI9WQ3u.dzhgozfigwpKZ0Vjvh1TXdiIn52jzO', 'cx', 'dqs', NULL, NULL, NULL, NULL, NULL, 'actif', 0, 'Madame'),
(8, 'laurent.viougeas@gmail.com', '[]', '$2y$13$NfvaIhEiFXG0Z.zueay6WegB/SjnWsGBl3FvYZ8uFsF4yYuTGe3Z.', 'ElnaZ', 'Z', '0608079850', '11 rue Jean Marie Tjibaou', '38400', 'SAINT MARTIN D HERES', NULL, 'actif', 0, 'Madame');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B26681EA76ED395` (`user_id`) USING BTREE;

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29A5EC27365BF48` (`sous_categorie_id`),
  ADD KEY `IDX_29A5EC27A76ED395` (`user_id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_42C84955A76ED395` (`user_id`),
  ADD KEY `IDX_42C84955FD02F13` (`evenement_id`);

--
-- Index pour la table `sous_categorie`
--
ALTER TABLE `sous_categorie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_52743D7BBCF5E72D` (`categorie_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sous_categorie`
--
ALTER TABLE `sous_categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD CONSTRAINT `FK_B26681EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC27365BF48` FOREIGN KEY (`sous_categorie_id`) REFERENCES `sous_categorie` (`id`),
  ADD CONSTRAINT `FK_29A5EC27A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_42C84955FD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `sous_categorie`
--
ALTER TABLE `sous_categorie`
  ADD CONSTRAINT `FK_52743D7BBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
