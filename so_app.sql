-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 28 avr. 2025 à 16:38
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
-- Base de données : `so_app`
--

-- --------------------------------------------------------

--
-- Structure de la table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL,
  `rating` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `user_id`, `content`, `created_at`, `rating`) VALUES
(1, 1, 3, '18 yars old', '2025-04-06 05:58:39', 1),
(2, 3, 3, 'Technically it\'s possible to serialize text, images, audio, video, etc. to JSON, which is a string format, and serve that arbitrary content in Uint8Arrays in a ReadableStream.', '2025-04-07 13:53:47', 4),
(3, 2, 3, 'this is answer user1', '2025-04-07 14:32:41', 3);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `user_id`, `title`, `content`, `created_at`) VALUES
(1, 3, 'Looping through a column and using each cell\'s value to fill in a range of empty cells in adjacent columns', 'I have a worksheet with 5000+ rows. Some cells in column F are empty and some have formulas. I want my current code to loop through all cells of column F and pull the value found in each to fill in adjacent columns G to Q (for all 5000 rows). (Also, if someone is able to tell me how to get my code to only fill cells of columns with Headers as shown in my image, that would also be helpful). Additionally, there are 20 of these in my spreadsheet, so the loop needs to do the same thing for varying numbers of rows (see second image). Right now the code appears to work for the first 69 rows, then doesn\'t work after or works intermittently. Please help me make it loop through all rows.', '2025-04-06 05:54:21'),
(2, 3, 'Why scroll doesn\'t work in Chrome on iPhone, but works fine in Chrome on desktop Mac?', 'Why in this example, the scroll container scrolls fine in Chrome on Mac, but doesn\'t scroll on iPhone Chrome/Safari? (The scroll also works find in Chrome on Android mobile)\r\n\r\nIf you have an iPhone, you can open this page where this code is deployed, and see the scroll not working.\r\n\r\nI also noticed that removing container-type: inline-size; from the .container solves the issue, but I\'d like to keep it to use container queries. Here you can see it scrolls fine.', '2025-04-06 06:09:12'),
(3, 2, 'How does Whasapp render message previews (link, blold, ..etc) if the API returning message as string plain text?', 'I\'m a React JS Developer Who is working with business API. I\'ve noticed that API returning message as string or plain text. but in preview they are parsing it ln link, bold, italic ...etc format.\r\n\r\nin conclusion i just wanted to know how they showing the preview. are they using custom parser or any kind of packages for it ?\r\n\r\nhere is the preview example:', '2025-04-07 12:18:08');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_uid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `google_uid`) VALUES
(1, 'ko_makhbouche@esi.dz', '', '', ''),
(2, '444106890@student.ksu.edu.sa', '$2y$10$.urs/ZMMpeFe1.XWqcbam.GzIX5RQdBuscmxFX7ISFegGlkGxMeAi', '', ''),
(3, 'OUSSAMA MAKHBOUCHE', '', 'ko_makhbouche@esi.dz', 'gnmFvF59ahboyIk6OzTIjJcBEJ22');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
