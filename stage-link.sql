-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 31 mars 2026 à 14:16
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
-- Base de données : `stage-link`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `entreprise_id` int(11) NOT NULL,
  `note` tinyint(4) DEFAULT NULL CHECK (`note` between 1 and 5),
  `commentaire` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `offre_id` int(11) NOT NULL,
  `statut` enum('en_attente','acceptee','refusee') DEFAULT 'en_attente',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `siret` varchar(14) DEFAULT NULL,
  `secteur` varchar(100) DEFAULT NULL,
  `taille` enum('1-10','11-50','51-200','200+') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `telephone` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `user_id`, `nom`, `siret`, `secteur`, `taille`, `description`, `logo_path`, `telephone`) VALUES
(1, 4, 'Sopra Steria', '326 820 065 00', 'informatique', NULL, NULL, '/assets/images/logos/soprasteria.svg', '0912345678');

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

CREATE TABLE `offres` (
  `id` int(11) NOT NULL,
  `entreprise_id` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `lieu` varchar(150) DEFAULT NULL,
  `duree` varchar(50) DEFAULT NULL,
  `remuneration` decimal(8,2) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`id`, `entreprise_id`, `titre`, `description`, `lieu`, `duree`, `remuneration`, `date_debut`, `created_at`) VALUES
(1, 1, 'Stage - Développeur/euse Java - Aix-en-Provence', 'Sopra Steria, acteur majeur de la Tech en Europe, avec 50 000 collaborateurs dans près de 30 pays, est reconnu pour ses activités de conseil, de services et solutions numériques. Il aide ses clients à mener leur transformation digitale et à obtenir des bénéfices concrets et durables. Le Groupe apporte une réponse globale aux enjeux de compétitivité des grandes entreprises et organisations, en combinant une connaissance approfondie des secteurs d’activité et des technologies à une approche collaborative.\r\n\r\nSopra Steria place l’humain au cœur de son action et s’engage auprès de ses clients à tirer le meilleur parti du numérique pour construire un avenir positif. En 2024, le Groupe a réalisé un chiffre d’affaires de 5,8 milliards d’euros.\r\n\r\nThe world is how we shape it*\r\nLe monde est tel que nous le façonnons\r\n\r\nDescription du poste\r\n\r\nIntéressé/e par la transformation digitale et les innovations technologiques ?\r\n\r\nVoici 4 bonnes raisons de rejoindre la région Sud-Est :\r\n\r\nUne variété de projets pour des entreprises locales et des grands groupes dans des secteurs divers : énergie, télécoms, transports, industrie, tertiaire, aéronautique, santé, services publics, défense\r\nDes postes diversifiés en intégration sur des domaines innovants : Cloud, Big Data, IA, SAP\r\nUn accompagnement RH, une proximité managériale et des perspectives de carrière\r\nDes équipes dynamiques à taille humaine et des communautés métier aux compétences variées.\r\nPar exemple, nous vous proposons de prendre part à l’initiative Tech’me UP qui a pour but de valoriser les filières techniques chez Sopra Steria.\r\n\r\nLes axes principaux sont les suivants :\r\n\r\nExcellence Tech : développer les compétences par du craftsmanship et de l’algorithmie, améliorer les environnements de développement pour répondre aux enjeux de time-to-market et de qualité ;\r\nImage Tech : préparation et répétition de talk en interne et en externe, conférences et rédaction de billets tech ;\r\nDéveloppement humain : permettre à chaque développeur/euse et architecte de trouver sa voie d’évolution jusqu’au plus haut niveau et mettre en lumière les compétences des expert/es !\r\nVotre rôle et vos missions :\r\n\r\nPour le compte d\'un client majeur, Sopra Steria assure une double mission dans le cadre de la gestion d\'une partie du système d\'information : maintenance corrective et évolutive et conduite de projets de développement.\r\n\r\nDans le cadre de votre alternance, vous êtes accueilli/e dans les locaux Sopra Steria au sein d\'une équipe de conception et développement et différentes responsabilités vous sont confiées :\r\n\r\nAnalyser fonctionnellement et/ou techniquement les besoins clients\r\nConcevoir, développer et tester des composants logiciels\r\nRédiger et exécuter des plans de tests de qualification et d\'intégration\r\nParticiper activement aux différentes cérémonies agiles du projet\r\nÊtre acteur/trice d\'un collectif dynamique et convivial\r\nEnvironnement technologique/fonctionnel :\r\n\r\nDes technologies avec les dernières versions du marché : Java, PHP, Angular, .Net, Springboot.\r\nUne dimension industrialisation est fortement présente sur les projets avec l\'usage de pipeline DevOps, supportés par Gitlab CI, Docker, Jenkins et le cloud Azure (AWS : Amazon Web Service).\r\nLes apports de l\'alternance :\r\n\r\nAcquérir des compétences techniques avec nos experts.\r\nDécouvrir et/ou appliquer les bonnes pratiques de développement dans un contexte professionnel.\r\nIntervenir sur les différentes phases du développement logiciel (spécification / conception / développement / déploiement).\r\nS\'approprier une méthodologie agile.\r\nS\'intégrer au sein d\'une équipe d\'environ 5/6 personnes et participer de manière active à la dynamique collective.\r\n(Re)Découvrir le fonctionnement d\'une des plus grandes ESN françaises (Entreprise de services du numérique) aux dimensions internationales.', 'Aix-en-Provence', '6', 450.00, '2026-04-06', '2026-03-30 15:10:42');

-- --------------------------------------------------------

--
-- Structure de la table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `ecole` varchar(150) DEFAULT NULL,
  `formation` varchar(150) DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `telephone` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `student`
--

INSERT INTO `student` (`id`, `user_id`, `nom`, `prenom`, `gender`, `ecole`, `formation`, `cv_path`, `photo`, `telephone`) VALUES
(3, 3, 'Billet', 'Matis', 'mr', 'Cesi école d\'ingénieur', 'Ingénieur Informatique', 'assets/images/cv/CV Matis BILLET aix .pdf', 'assets/images/photos/pdp.jpg', '0666924421');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','entreprise','admin') NOT NULL DEFAULT 'student',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `password`, `role`, `created_at`) VALUES
(3, 'matisbillet.83@gmail.com', '$2y$10$Z/zycjA8Ei8XwYvRq4tv5eCNmAc5hc33SvGmO6RF1AlbcR1.VUIla', 'student', '2026-03-26 21:43:26'),
(4, 'soprasteria@soprasteria.com', '$2y$10$XQDXO7DoEvs5gGnGVJ37Ee8gUfcf73nsrAKx53iEiVQP6vbEPY73C', 'entreprise', '2026-03-30 09:35:35');

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `offre_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_candidature` (`student_id`,`offre_id`),
  ADD KEY `offre_id` (`offre_id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Index pour la table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`student_id`,`offre_id`),
  ADD KEY `offre_id` (`offre_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `offres`
--
ALTER TABLE `offres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `candidatures_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidatures_ibfk_2` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD CONSTRAINT `entreprises_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `offres_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
