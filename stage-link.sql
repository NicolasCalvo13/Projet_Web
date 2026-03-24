-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 24 mars 2026 à 21:11
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
  `etudiant_id` int(11) NOT NULL,
  `entreprise_id` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` between 1 and 5),
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `offre_id` int(11) NOT NULL,
  `statut` enum('en_attente','acceptee','refusee') DEFAULT 'en_attente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `secteur` varchar(100) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `secteur`, `ville`, `description`, `created_at`, `logo`) VALUES
(1, 'Sopra Steria', 'Informatique', 'Aix-en-Provence', 'Société de conseil et services numériques, spécialisée dans la transformation digitale des entreprises.', '2026-03-23 14:20:36', '/assets/images/logos/sopra.svg'),
(2, 'Vinci Construction', 'BTP', 'Marseille', 'Leader mondial de la construction et des travaux publics, Vinci Construction intervient sur des projets d\'infrastructures, de bâtiments et de génie civil partout en France.', '2026-03-23 16:07:11', '/assets/images/logos/vinci.svg'),
(3, 'Bouygues Construction', 'BTP', 'Lyon', 'Groupe de construction français intervenant dans le bâtiment, les travaux publics et l\'immobilier en France et à l\'international.', '2026-03-23 16:08:49', '/assets/images/logos/bouygues.png'),
(4, 'Eiffage', 'BTP', 'Bordeaux', 'Groupe de construction et de concessions présent dans le BTP, les infrastructures et l\'énergie en Europe.', '2026-03-23 16:08:49', '/assets/images/logos/eiffage.svg'),
(5, 'Capgemini', 'Informatique', 'Paris', 'Société mondiale de conseil et services numériques, spécialisée dans la transformation digitale, le cloud et la cybersécurité.', '2026-03-23 16:08:49', '/assets/images/logos/capgemini.svg'),
(6, 'Orange', 'Informatique', 'Aix-en-Provence', 'Opérateur télécom et ESN français proposant des services cloud, cybersécurité et développement logiciel.', '2026-03-23 16:08:49', '/assets/images/logos/orange.svg'),
(7, 'Altran', 'Informatique', 'Toulouse', 'Cabinet de conseil en ingénierie et R&D, intervenant dans l\'aéronautique, l\'automobile et les systèmes embarqués.', '2026-03-23 16:08:49', '/assets/images/logos/altran.svg'),
(8, 'GTM Bâtiment', 'BTP', 'Nice', 'Filiale de Vinci spécialisée dans la construction de bâtiments résidentiels et tertiaires sur la côte méditerranéenne.', '2026-03-23 16:08:49', '/assets/images/logos/vinci.svg'),
(9, 'IBM France', 'Informatique', 'Paris', 'Entreprise technologique mondiale proposant des solutions IA, cloud hybride et services IT aux grandes entreprises.', '2026-03-23 16:08:49', '/assets/images/logos/ibm.svg'),
(10, 'Colas', 'BTP', 'Marseille', 'Filiale de Bouygues spécialisée dans la construction et l\'entretien de routes, autoroutes et infrastructures de transport.', '2026-03-23 16:08:49', '/assets/images/logos/colas.png'),
(11, 'Thales', 'Informatique', 'Toulouse', 'Groupe technologique français spécialisé dans la défense, l\'aéronautique, le spatial et la cybersécurité.', '2026-03-23 16:08:49', '/assets/images/logos/thales.svg'),
(12, 'Spie Batignolles', 'BTP', 'Nantes', 'Groupe de construction indépendant intervenant dans le génie civil, les ouvrages d\'art et les bâtiments complexes.', '2026-03-23 16:08:49', '/assets/images/logos/spie.svg');

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

CREATE TABLE `offres` (
  `id` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `secteur` enum('informatique','btp','autre') DEFAULT 'autre',
  `ville` varchar(100) DEFAULT NULL,
  `remuneration` decimal(8,2) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`id`, `titre`, `description`, `secteur`, `ville`, `remuneration`, `date_debut`, `date_fin`, `entreprise_id`, `created_at`) VALUES
(1, 'Stage Développeur Web Full-Stack', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nSopra Steria est un leader européen de la transformation numérique, accompagnant les grandes entreprises et organisations publiques dans leurs projets digitaux. Notre agence d\'Aix-en-Provence intervient sur des projets variés pour des clients dans les secteurs bancaire, industriel et public.\r\n\r\nDans le cadre du renforcement de notre équipe de développement, nous recherchons un(e) stagiaire développeur(se) web Full-Stack pour contribuer à la conception et à l\'amélioration de nos applications internes et clients.\r\n\r\nLe stage portera principalement sur le développement d\'interfaces web modernes et d\'API robustes, en collaboration avec des équipes Agile pluridisciplinaires. L\'objectif est de participer activement au cycle complet de développement logiciel, de la conception à la mise en production.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) développeur(se) web Full-Stack motivé(e) pour rejoindre notre équipe technique. Vous serez impliqué(e) dans la conception, le développement et la maintenance d\'applications web, en travaillant aussi bien sur la partie front-end que back-end. Si vous êtes passionné(e) par le développement web et souhaitez évoluer dans un environnement stimulant et exigeant, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Développer et maintenir des applications web en utilisant des technologies modernes (PHP, JavaScript, React, Vue.js)\r\n- Concevoir et intégrer des API RESTful pour assurer la communication entre les différentes couches applicatives\r\n- Participer à la conception de bases de données relationnelles (MySQL, PostgreSQL)\r\n- Collaborer avec les équipes Agile (Scrum) pour planifier et livrer les fonctionnalités dans les délais impartis\r\n- Rédiger la documentation technique et assurer la qualité du code via des revues et des tests unitaires\r\n- Participer à la résolution de bugs et à la mise à jour des applications existantes\r\n- Utiliser Git et GitHub pour la gestion des versions et la collaboration en équipe\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en informatique (Bac+3 à Bac+5) avec de bonnes bases en développement web front-end et back-end. Vous maîtrisez au moins un langage back-end (PHP, Java, Python ou Node.js) et avez des notions de frameworks JavaScript modernes. Une expérience avec Git, les bases de données SQL et les API REST est appréciée. Vous êtes rigoureux(se), autonome et appréciez le travail en équipe. La connaissance des méthodologies Agile est un plus.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Aix-en-Provence\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une courte lettre de motivation précisant vos expériences en développement web et les technologies que vous maîtrisez.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Aix-en-Provence ou les environs ?\r\nAvez-vous déjà développé une application web avec une architecture MVC ?\r\nAvez-vous une expérience avec les API RESTful ?\r\nAvez-vous utilisé Git dans un projet en équipe ?\r\nConnaissez-vous les méthodologies Agile/Scrum ?\r\n\r\nLangue :\r\nAnglais (courant requis), Français (requis)\r\n\r\nPermis/certification :\r\nAucun requis\r\n\r\nLieu du poste : En présentiel', 'informatique', 'Aix-en-Provence', 650.00, '2026-06-01', '2026-08-31', 1, '2026-03-23 14:20:36'),
(2, 'Stage Conducteur de Travaux', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nVinci Construction est l\'un des leaders mondiaux de la construction et des travaux publics, intervenant sur des projets d\'envergure en France et à l\'international. Notre agence de Marseille pilote des chantiers variés allant du bâtiment résidentiel aux infrastructures urbaines complexes.\r\n\r\nDans le cadre du développement de nos activités sur la région PACA, nous recherchons un(e) stagiaire conducteur(trice) de travaux pour accompagner nos équipes terrain dans le suivi et la gestion de chantiers.\r\n\r\nLe stage portera principalement sur l\'assistance à la conduite de travaux, la coordination des équipes et des sous-traitants, ainsi que le suivi administratif et technique des opérations. L\'objectif est de vous immerger dans la réalité opérationnelle d\'un chantier BTP de grande envergure.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire conducteur(trice) de travaux pour rejoindre notre équipe opérationnelle. Sous la responsabilité d\'un conducteur de travaux senior, vous participerez activement à la gestion quotidienne des chantiers, de la planification à la réception des travaux. Si vous êtes passionné(e) par le terrain et souhaitez acquérir une expérience concrète dans le BTP, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Assister le conducteur de travaux dans le suivi quotidien des chantiers (délais, qualité, sécurité)\r\n- Coordonner les équipes terrain et les sous-traitants selon le planning établi\r\n- Participer aux réunions de chantier et rédiger les comptes-rendus\r\n- Contrôler la conformité des travaux réalisés par rapport aux plans et aux normes en vigueur\r\n- Suivre les approvisionnements en matériaux et gérer les stocks sur site\r\n- Veiller au respect des règles d\'hygiène et de sécurité sur le chantier\r\n- Contribuer au suivi financier du chantier (situations de travaux, gestion des écarts)\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en génie civil, travaux publics ou bâtiment (Bac+3 à Bac+5). Vous avez une bonne connaissance des techniques de construction et des normes DTU. À l\'aise sur le terrain comme en bureau, vous savez lire des plans et utiliser les outils informatiques courants (Pack Office, AutoCAD est un plus). Vous êtes rigoureux(se), organisé(e) et faites preuve d\'un bon relationnel pour travailler avec des équipes pluridisciplinaires. Le permis B est indispensable pour les déplacements sur les chantiers.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Marseille et chantiers région PACA\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation précisant votre intérêt pour le terrain et vos éventuelles expériences en chantier ou en gestion de projet BTP.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Marseille ou les environs ?\r\nÊtes-vous véhiculé(e) et titulaire du permis B ?\r\nAvez-vous déjà effectué un stage ou une expérience sur chantier ?\r\nSavez-vous lire et interpréter des plans de construction ?\r\nAvez-vous des notions de gestion budgétaire ou de suivi financier de chantier ?\r\n\r\nLangue :\r\nFrançais (requis)\r\n\r\nPermis/certification :\r\nPermis B (Requis)\r\n\r\nLieu du poste : En présentiel – déplacements sur chantiers', 'btp', 'Marseille', 600.00, '2026-06-01', '2026-08-31', 2, '2026-03-23 16:07:11'),
(3, 'Stage Ingénieur Génie Civil', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nBouygues Construction est un groupe de construction français de premier plan, intervenant dans les domaines du bâtiment, des travaux publics et de l\'énergie et services. Notre entité de Lyon pilote des projets d\'infrastructure majeurs en région Auvergne-Rhône-Alpes, alliant innovation technique et respect des enjeux environnementaux.\r\n\r\nDans le cadre du développement de nos projets d\'infrastructure, nous recherchons un(e) stagiaire ingénieur(e) génie civil pour renforcer notre bureau d\'études et participer à la conception et au suivi technique de nos ouvrages.\r\n\r\nLe stage portera principalement sur les études de structures, la modélisation des ouvrages et l\'assistance technique aux équipes chantier. L\'objectif est de vous permettre d\'appliquer vos connaissances théoriques sur des projets concrets à fort enjeu technique.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire ingénieur(e) génie civil pour intégrer notre bureau d\'études. Vous travaillerez aux côtés d\'ingénieurs expérimentés sur des projets variés (ponts, bâtiments, infrastructures urbaines) et contribuerez à toutes les phases techniques, de la conception à la vérification sur site. Si vous êtes passionné(e) par les structures et souhaitez évoluer dans un environnement technique exigeant, rejoignez-nous.\r\n\r\nResponsabilités\r\n- Participer aux études de dimensionnement et de vérification des structures (béton armé, charpente métallique)\r\n- Réaliser des modélisations numériques à l\'aide de logiciels de calcul de structures (Robot, SCIA ou equivalent)\r\n- Contribuer à la rédaction des notes de calcul et des rapports techniques\r\n- Assister les équipes chantier lors des visites de suivi et contrôler la conformité des travaux aux plans d\'exécution\r\n- Participer aux réunions techniques avec les maîtres d\'œuvre et les bureaux de contrôle\r\n- Mettre à jour les plans d\'exécution en lien avec les équipes CAO/DAO (AutoCAD, Revit)\r\n- Contribuer à la veille réglementaire et normative (Eurocodes, DTU)\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en génie civil ou ingénierie des structures (Bac+4 à Bac+5). Vous maîtrisez les bases du calcul de structures et avez des connaissances en mécanique des matériaux. La pratique d\'un logiciel de modélisation (Robot, SCIA, SAP2000) est un atout sérieux. Vous êtes à l\'aise avec les outils DAO (AutoCAD, Revit) et le Pack Office. Rigoureux(se), méthodique et curieux(se), vous appréciez aussi bien le travail en bureau d\'études que les visites terrain. Le permis B est apprécié pour les déplacements sur site.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Lyon\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV ainsi qu\'une lettre de motivation précisant vos compétences en calcul de structures et vos expériences avec les logiciels de modélisation.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Lyon ou les environs ?\r\nAvez-vous déjà utilisé un logiciel de calcul de structures (Robot, SCIA, SAP2000) ?\r\nAvez-vous des notions sur les Eurocodes ?\r\nMaîtrisez-vous AutoCAD ou Revit ?\r\nAvez-vous déjà réalisé une note de calcul dans le cadre d\'un projet ou d\'un cours ?\r\n\r\nLangue :\r\nAnglais (lu/écrit apprécié), Français (requis)\r\n\r\nPermis/certification :\r\nPermis B (Apprécié)\r\n\r\nLieu du poste : En présentiel', 'btp', 'Lyon', 580.00, '2026-06-01', '2026-08-31', 3, '2026-03-23 16:08:49'),
(4, 'Stage Chargé d\'Études BTP', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nEiffage est l\'un des leaders européens du BTP et des concessions, présent dans les domaines de la construction, du génie civil, de l\'énergie et des infrastructures de transport. Notre agence de Bordeaux intervient sur des projets d\'aménagement urbain et d\'infrastructure dans toute la région Nouvelle-Aquitaine.\r\n\r\nDans le cadre du renforcement de notre bureau d\'études, nous recherchons un(e) stagiaire chargé(e) d\'études BTP pour participer à la préparation et au suivi technique de nos appels d\'offres et projets en cours.\r\n\r\nLe stage portera principalement sur l\'élaboration des dossiers d\'études, l\'analyse des besoins techniques et la production de livrables (plans, métrés, notes techniques). L\'objectif est de vous intégrer pleinement dans le processus d\'études, de la phase conception jusqu\'à la remise des dossiers.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire chargé(e) d\'études BTP rigoureux(se) et motivé(e) pour rejoindre notre équipe études. Vous travaillerez en étroite collaboration avec les ingénieurs et chefs de projet pour produire des études techniques de qualité dans le respect des délais et des normes en vigueur. Si vous souhaitez acquérir une expérience solide en bureau d\'études BTP au sein d\'un grand groupe, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Participer à la réalisation des études techniques (avant-projet, projet d\'exécution) pour des ouvrages de bâtiment ou de génie civil\r\n- Réaliser des métrés et établir des quantitatifs à partir des plans d\'architectes ou d\'ingénieurs\r\n- Contribuer à la rédaction des pièces écrites (CCTP, mémoires techniques, notices)\r\n- Produire et mettre à jour des plans d\'exécution à l\'aide de logiciels DAO (AutoCAD, Revit)\r\n- Analyser les cahiers des charges et identifier les contraintes techniques et réglementaires\r\n- Participer aux réunions de coordination avec les différents intervenants (architectes, BET, maîtres d\'ouvrage)\r\n- Assurer une veille sur les normes et réglementations en vigueur dans le secteur BTP\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en génie civil, bâtiment ou construction (Bac+3 à Bac+5). Vous avez de bonnes bases en lecture de plans et en techniques de construction. La maîtrise d\'AutoCAD est indispensable ; la connaissance de Revit ou d\'un logiciel de métrés est un plus. Vous êtes organisé(e), précis(e) et capable de gérer plusieurs tâches simultanément. Un bon sens de la communication est nécessaire pour interagir avec les différents acteurs du projet. Le permis B est apprécié.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Bordeaux\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation décrivant vos compétences en études techniques et votre maîtrise des outils DAO.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Bordeaux ou les environs ?\r\nMaîtrisez-vous AutoCAD ou un logiciel DAO équivalent ?\r\nAvez-vous déjà réalisé des métrés ou des quantitatifs ?\r\nAvez-vous une expérience en lecture et interprétation de plans BTP ?\r\nConnaissez-vous les normes et réglementations du secteur BTP (DTU, Eurocodes) ?\r\n\r\nLangue :\r\nFrançais (requis)\r\n\r\nPermis/certification :\r\nPermis B (Apprécié)\r\n\r\nLieu du poste : En présentiel', 'btp', 'Bordeaux', 620.00, '2026-07-01', '2026-09-30', 4, '2026-03-23 16:08:49'),
(5, 'Stage Développeur Full-Stack Java', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nCapgemini est l\'un des leaders mondiaux du conseil, des services informatiques et de la transformation digitale, présent dans plus de 50 pays. Notre agence parisienne accompagne des clients grands comptes dans des secteurs variés tels que la banque, l\'assurance, l\'industrie et le secteur public, en leur proposant des solutions technologiques innovantes et sur mesure.\r\n\r\nDans le cadre du renforcement de nos équipes de développement, nous recherchons un(e) stagiaire développeur(se) Full-Stack Java pour contribuer à la conception et au développement d\'applications métier critiques pour nos clients.\r\n\r\nLe stage portera principalement sur le développement back-end en Java/Spring Boot et front-end en Angular ou React, ainsi que sur l\'intégration de services via des API RESTful. L\'objectif est de vous impliquer pleinement dans le cycle de vie d\'un projet logiciel en environnement professionnel exigeant.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire développeur(se) Full-Stack Java passionné(e) pour intégrer l\'une de nos équipes projet. Vous serez impliqué(e) dans toutes les phases du développement, de la conception technique à la mise en production, en collaborant avec des équipes pluridisciplinaires expérimentées. Si vous souhaitez monter en compétences rapidement dans un environnement stimulant et à la pointe de la technologie, rejoignez-nous.\r\n\r\nResponsabilités\r\n- Concevoir et développer des applications web en Java avec le framework Spring Boot\r\n- Développer des interfaces utilisateur modernes avec Angular ou React\r\n- Concevoir et intégrer des API RESTful pour interconnecter les différentes briques applicatives\r\n- Participer à la conception de la base de données et écrire des requêtes SQL optimisées (PostgreSQL, MySQL)\r\n- Collaborer avec les équipes Agile/Scrum pour planifier et livrer les fonctionnalités dans les délais\r\n- Rédiger les tests unitaires et d\'intégration pour garantir la qualité du code\r\n- Utiliser Git et les outils CI/CD (Jenkins, GitLab CI) pour la gestion des versions et le déploiement continu\r\n- Participer aux revues de code et contribuer à l\'amélioration continue des pratiques de développement\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en informatique ou génie logiciel (Bac+4 à Bac+5). Vous maîtrisez Java et avez des bases solides en développement web front-end (HTML, CSS, JavaScript). La connaissance de Spring Boot et d\'un framework front-end (Angular ou React) est fortement appréciée. Vous êtes à l\'aise avec Git, les bases de données SQL et les concepts d\'API REST. Une première expérience avec les méthodologies Agile/Scrum est un plus. Vous êtes rigoureux(se), curieux(se) et aimez travailler en équipe sur des projets techniques ambitieux.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Paris\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation précisant vos expériences en développement Java et vos projets personnels ou académiques en lien avec le poste.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Paris ou la région Île-de-France ?\r\nAvez-vous déjà développé une application avec Spring Boot ?\r\nMaîtrisez-vous un framework front-end (Angular, React ou Vue.js) ?\r\nAvez-vous une expérience avec les API RESTful ?\r\nAvez-vous travaillé en méthodologie Agile/Scrum ?\r\n\r\nLangue :\r\nAnglais (courant requis), Français (requis)\r\n\r\nPermis/certification :\r\nAucun requis\r\n\r\nLieu du poste : En présentiel', 'informatique', 'Paris', 700.00, '2026-06-01', '2026-08-31', 5, '2026-03-23 16:08:49'),
(6, 'Stage Développeur Mobile', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nOrange est l\'un des principaux opérateurs télécoms et acteurs du numérique en France et dans le monde, proposant des services innovants aussi bien aux particuliers qu\'aux entreprises. Notre centre de développement d\'Aix-en-Provence travaille sur des applications mobiles et des solutions connectées utilisées par des millions d\'utilisateurs au quotidien.\r\n\r\nDans le cadre du développement de notre portefeuille d\'applications mobiles, nous recherchons un(e) stagiaire développeur(se) mobile pour participer à la conception et à l\'amélioration de nos applications iOS et Android.\r\n\r\nLe stage portera principalement sur le développement d\'applications mobiles natives ou cross-platform, l\'intégration de services back-end via des API RESTful et l\'optimisation des performances applicatives. L\'objectif est de vous impliquer dans un cycle de développement complet, de la maquette à la publication sur les stores.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire développeur(se) mobile motivé(e) et curieux(se) pour renforcer notre équipe applicative. Vous contribuerez au développement de fonctionnalités innovantes sur des applications mobiles à forte audience, en collaborant étroitement avec les équipes UX/UI, back-end et qualité. Si vous êtes passionné(e) par le développement mobile et souhaitez évoluer dans un environnement technologique de pointe, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Développer et maintenir des applications mobiles iOS et/ou Android (Swift, Kotlin ou Flutter/React Native)\r\n- Intégrer des API RESTful et des services tiers pour enrichir les fonctionnalités applicatives\r\n- Collaborer avec les équipes UX/UI pour implémenter des interfaces utilisateur fluides et accessibles\r\n- Rédiger des tests unitaires et fonctionnels pour garantir la stabilité des applications\r\n- Participer aux sprints Agile et aux cérémonies Scrum (daily, sprint review, rétrospective)\r\n- Optimiser les performances des applications (temps de chargement, consommation batterie, fluidité)\r\n- Assurer la veille technologique sur les nouvelles pratiques et frameworks du développement mobile\r\n- Contribuer à la publication et au suivi des applications sur l\'App Store et le Google Play Store\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en informatique ou développement logiciel (Bac+3 à Bac+5). Vous avez des bases solides en développement mobile (Swift, Kotlin, Flutter ou React Native). La connaissance des APIs REST et des outils de versioning Git est indispensable. Une expérience avec les outils de design (Figma) ou les plateformes de CI/CD mobile (Fastlane, Firebase) est un plus apprécié. Vous êtes autonome, créatif(ve) et appréciez le travail en équipe dans un environnement Agile. Une sensibilité pour l\'expérience utilisateur et l\'accessibilité est un atout majeur.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Aix-en-Provence\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation précisant vos expériences en développement mobile et, si possible, un lien vers vos projets ou applications réalisées (GitHub, stores).\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Aix-en-Provence ou les environs ?\r\nAvez-vous déjà développé une application mobile (iOS, Android ou cross-platform) ?\r\nMaîtrisez-vous Swift, Kotlin, Flutter ou React Native ?\r\nAvez-vous une expérience avec les API RESTful dans un contexte mobile ?\r\nAvez-vous déjà publié une application sur l\'App Store ou le Google Play Store ?\r\n\r\nLangue :\r\nAnglais (lu/écrit requis), Français (requis)\r\n\r\nPermis/certification :\r\nAucun requis\r\n\r\nLieu du poste : En présentiel', 'informatique', 'Aix-en-Provence', 650.00, '2026-06-15', '2026-09-15', 6, '2026-03-23 16:08:49'),
(7, 'Stage Ingénieur DevOps', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nAltran est un cabinet de conseil en ingénierie et R&D de renommée mondiale, accompagnant les grands acteurs de l\'aéronautique, du spatial, de l\'automobile et du numérique dans leurs projets d\'innovation technologique. Notre centre de Toulouse, au cœur de la capitale européenne de l\'aéronautique, intervient sur des projets critiques nécessitant une maîtrise pointue des environnements DevOps et des infrastructures cloud.\r\n\r\nDans le cadre du renforcement de notre pratique DevOps, nous recherchons un(e) stagiaire ingénieur(e) DevOps pour participer à l\'automatisation et à l\'amélioration de nos pipelines de livraison logicielle.\r\n\r\nLe stage portera principalement sur la mise en place et l\'optimisation de pipelines CI/CD, la gestion des infrastructures cloud et la containerisation des applications. L\'objectif est de fluidifier les processus de build, de test et de déploiement afin de réduire les délais de mise en production et d\'améliorer la fiabilité des systèmes.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire ingénieur(e) DevOps passionné(e) par l\'automatisation et les infrastructures modernes pour rejoindre notre équipe technique. Vous travaillerez aux côtés d\'ingénieurs expérimentés sur des environnements complexes et contribuerez à la transformation des pratiques de livraison logicielle. Si vous souhaitez évoluer dans un environnement technique exigeant à la pointe de l\'innovation, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Concevoir et maintenir des pipelines CI/CD avec des outils comme Jenkins, GitLab CI ou GitHub Actions\r\n- Gérer et automatiser le déploiement d\'applications conteneurisées via Docker et Kubernetes\r\n- Participer à la gestion des infrastructures cloud (AWS, Azure ou GCP) en utilisant des outils d\'Infrastructure as Code (Terraform, Ansible)\r\n- Surveiller les performances des systèmes et mettre en place des outils de monitoring (Prometheus, Grafana, ELK Stack)\r\n- Collaborer avec les équipes de développement pour intégrer les bonnes pratiques DevOps dans le cycle de vie des projets\r\n- Automatiser les tâches répétitives via des scripts Bash ou Python\r\n- Contribuer à la sécurisation des environnements (DevSecOps) et à la gestion des secrets\r\n- Rédiger la documentation technique des infrastructures et des processus mis en place\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en informatique, systèmes ou réseaux (Bac+4 à Bac+5). Vous avez de bonnes bases en administration système Linux et en scripting (Bash, Python). La connaissance de Docker, Kubernetes et d\'au moins un outil CI/CD est indispensable. Une expérience avec les plateformes cloud (AWS, Azure ou GCP) et les outils IaC (Terraform, Ansible) est fortement appréciée. Vous êtes rigoureux(se), autonome et appréciez la résolution de problèmes techniques complexes. La connaissance des pratiques Agile et DevSecOps est un atout.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Toulouse\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation précisant vos expériences avec les outils DevOps, vos projets personnels ou académiques en lien avec le poste, et si possible un lien vers votre profil GitHub.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Toulouse ou les environs ?\r\nAvez-vous déjà mis en place un pipeline CI/CD ?\r\nMaîtrisez-vous Docker et/ou Kubernetes ?\r\nAvez-vous une expérience avec une plateforme cloud (AWS, Azure ou GCP) ?\r\nAvez-vous utilisé des outils d\'Infrastructure as Code (Terraform, Ansible) ?\r\n\r\nLangue :\r\nAnglais (courant requis), Français (requis)\r\n\r\nPermis/certification :\r\nAucun requis\r\n\r\nLieu du poste : En présentiel', 'informatique', 'Toulouse', 680.00, '2026-06-01', '2026-08-31', 7, '2026-03-23 16:08:49'),
(8, 'Stage Chef de Chantier Junior', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nGTM Bâtiment est une filiale de Vinci Construction spécialisée dans la construction de bâtiments tertiaires, résidentiels et industriels. Notre agence de Nice intervient sur des projets d\'envergure sur toute la Côte d\'Azur, alliant exigences techniques, respect des délais et qualité de réalisation.\r\n\r\nDans le cadre du développement de nos activités sur la région, nous recherchons un(e) stagiaire chef(fe) de chantier junior pour accompagner nos équipes dans la gestion opérationnelle quotidienne de nos chantiers.\r\n\r\nLe stage portera principalement sur l\'encadrement des équipes terrain, la coordination des corps de métier et le suivi de l\'avancement des travaux. L\'objectif est de vous former aux réalités du management de chantier dans un environnement professionnel structuré et exigeant.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire chef(fe) de chantier junior pour intégrer nos équipes opérationnelles. Sous la supervision d\'un chef de chantier confirmé, vous assurerez la coordination des compagnons et des sous-traitants, veillerez au respect des règles de sécurité et contribuerez à la bonne réalisation des travaux dans les délais impartis. Si vous êtes à l\'aise sur le terrain et souhaitez acquérir une première expérience solide en management de chantier, rejoignez-nous.\r\n\r\nResponsabilités\r\n- Assister le chef de chantier dans l\'organisation et la supervision quotidienne des équipes terrain\r\n- Coordonner les différents corps de métier et sous-traitants selon le planning de chantier\r\n- Veiller au strict respect des consignes de sécurité et des règles d\'hygiène sur site (port des EPI, plan de prévention)\r\n- Contrôler la qualité des travaux réalisés et signaler les non-conformités au conducteur de travaux\r\n- Participer à la gestion des approvisionnements et au suivi des stocks de matériaux sur site\r\n- Rédiger les rapports journaliers de chantier et les fiches de pointage des équipes\r\n- Participer aux réunions de chantier hebdomadaires et contribuer aux comptes-rendus\r\n- Veiller à la bonne utilisation et à l\'entretien du matériel et des engins sur site\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en bâtiment, génie civil ou travaux publics (Bac+2 à Bac+4). Vous avez une bonne connaissance des techniques de construction et du fonctionnement d\'un chantier. Vous êtes à l\'aise pour encadrer des équipes et communiquer avec différents interlocuteurs (ouvriers, sous-traitants, conducteurs de travaux). Vous faites preuve d\'autorité naturelle, de réactivité et d\'un sens aigu de l\'organisation. La connaissance des règles de sécurité chantier (PPSPS, plan de prévention) est un atout. Le permis B est indispensable pour les déplacements entre les différents sites.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Nice et chantiers région Côte d\'Azur\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation décrivant votre attrait pour le terrain et vos éventuelles expériences en chantier ou en encadrement d\'équipe.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Nice ou les environs ?\r\nÊtes-vous véhiculé(e) et titulaire du permis B ?\r\nAvez-vous déjà encadré une équipe ou coordonné des intervenants sur un chantier ?\r\nAvez-vous des connaissances en règles de sécurité chantier (PPSPS, EPI) ?\r\nAvez-vous déjà effectué un stage ou une expérience en entreprise de construction ?\r\n\r\nLangue :\r\nFrançais (requis)\r\n\r\nPermis/certification :\r\nPermis B (Requis)\r\n\r\nLieu du poste : En présentiel – déplacements sur chantiers', 'btp', 'Nice', 600.00, '2026-07-01', '2026-09-30', 8, '2026-03-23 16:08:49'),
(9, 'Stage Data Analyst', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nIBM France est la filiale française d\'une entreprise technologique mondiale pionnière dans les domaines de l\'intelligence artificielle, du cloud hybride et de l\'analyse de données. Notre centre parisien accompagne les grandes entreprises françaises dans leur transformation data, en leur proposant des solutions analytiques et décisionnelles à haute valeur ajoutée.\r\n\r\nDans le cadre du renforcement de notre practice Data & Analytics, nous recherchons un(e) stagiaire Data Analyst pour participer à l\'exploitation et à la valorisation des données de nos clients.\r\n\r\nLe stage portera principalement sur la collecte, le traitement et l\'analyse de données massives, ainsi que sur la conception de tableaux de bord et de rapports décisionnels. L\'objectif est d\'aider nos clients à mieux comprendre leurs données pour prendre des décisions éclairées et optimiser leurs processus métier.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire Data Analyst rigoureux(se) et curieux(se) pour rejoindre notre équipe Business Intelligence. Vous travaillerez sur des jeux de données réels et variés, en collaborant avec des data engineers, des data scientists et des équipes métier pour produire des insights actionnables. Si vous êtes passionné(e) par la donnée et souhaitez évoluer dans un environnement technologique stimulant, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Collecter, nettoyer et préparer des jeux de données provenant de sources variées (bases SQL, APIs, fichiers CSV)\r\n- Réaliser des analyses exploratoires et statistiques pour identifier des tendances et des anomalies\r\n- Concevoir et maintenir des tableaux de bord interactifs avec des outils de dataviz (Power BI, Tableau ou Looker)\r\n- Rédiger des rapports d\'analyse clairs et synthétiques à destination des équipes métier\r\n- Collaborer avec les data engineers pour optimiser les pipelines de données et les requêtes SQL\r\n- Participer à la modélisation des données et à la conception de data marts\r\n- Contribuer à l\'automatisation des reportings récurrents via Python ou SQL\r\n- Assurer la qualité et la cohérence des données tout au long du processus analytique\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en data science, statistiques, informatique ou école de commerce avec une spécialisation data (Bac+4 à Bac+5). Vous maîtrisez SQL et avez de bonnes bases en Python (Pandas, NumPy, Matplotlib). La connaissance d\'un outil de dataviz (Power BI, Tableau) est indispensable. Vous êtes à l\'aise avec Excel/Google Sheets pour les analyses ad hoc. Vous faites preuve de rigueur, d\'esprit critique et d\'une bonne capacité à vulgariser des résultats techniques auprès d\'interlocuteurs non techniques.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Paris\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation précisant vos expériences en analyse de données, vos projets académiques ou personnels en lien avec la data, et les outils que vous maîtrisez.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Paris ou la région Île-de-France ?\r\nMaîtrisez-vous SQL pour l\'interrogation et la manipulation de données ?\r\nAvez-vous déjà utilisé Python (Pandas, NumPy) pour l\'analyse de données ?\r\nMaîtrisez-vous un outil de dataviz (Power BI, Tableau ou Looker) ?\r\nAvez-vous déjà conçu un tableau de bord ou un rapport décisionnel ?\r\n\r\nLangue :\r\nAnglais (courant requis), Français (requis)\r\n\r\nPermis/certification :\r\nAucun requis\r\n\r\nLieu du poste : En présentiel', 'informatique', 'Paris', 720.00, '2026-06-01', '2026-08-31', 9, '2026-03-23 16:08:49'),
(10, 'Stage Technicien Travaux Publics', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nColas est une filiale du groupe Bouygues spécialisée dans la construction et l\'entretien des infrastructures de transport (routes, voies ferrées, pistes aéroportuaires). Notre agence de Marseille intervient sur des chantiers routiers et d\'aménagement urbain d\'envergure dans toute la région PACA, en s\'appuyant sur des savoir-faire techniques reconnus et des équipes expérimentées.\r\n\r\nDans le cadre du développement de nos activités sur la région, nous recherchons un(e) stagiaire technicien(ne) travaux publics pour accompagner nos équipes terrain dans le suivi et la réalisation de nos chantiers d\'infrastructure.\r\n\r\nLe stage portera principalement sur le suivi technique des travaux de voirie, l\'assistance aux relevés topographiques et le contrôle qualité des matériaux et des réalisations. L\'objectif est de vous immerger dans la réalité opérationnelle des travaux publics au sein d\'une entreprise leader du secteur.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire technicien(ne) travaux publics motivé(e) et à l\'aise sur le terrain pour rejoindre notre équipe chantier. Sous la responsabilité d\'un chef de chantier expérimenté, vous participerez activement au suivi quotidien des travaux, aux relevés de terrain et aux contrôles de conformité. Si vous souhaitez acquérir une expérience concrète dans les travaux publics au sein d\'un grand groupe, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Assister le chef de chantier dans le suivi quotidien des travaux de voirie et d\'aménagement\r\n- Réaliser des relevés topographiques et des implantations sur le terrain\r\n- Contrôler la qualité des matériaux utilisés (enrobés, béton, granulats) et des travaux réalisés\r\n- Participer à la rédaction des rapports journaliers et des fiches de contrôle qualité\r\n- Veiller au respect des consignes de sécurité et des procédures chantier\r\n- Contribuer au suivi des approvisionnements en matériaux et à la gestion du matériel sur site\r\n- Assister aux réunions de chantier et rédiger les comptes-rendus\r\n- Participer aux essais de compactage et aux contrôles de conformité des ouvrages réalisés\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en travaux publics, génie civil ou infrastructure (Bac+2 à Bac+4). Vous êtes à l\'aise sur le terrain et avez des bases solides en topographie et en techniques de construction routière. La connaissance des normes NF et des procédures de contrôle qualité dans les TP est appréciée. Vous maîtrisez les outils bureautiques courants (Pack Office) et avez idéalement des notions d\'AutoCAD. Vous faites preuve de rigueur, de réactivité et d\'un bon sens du terrain. Le permis B est indispensable pour les déplacements entre les différents chantiers.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Marseille et chantiers région PACA\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation décrivant votre intérêt pour les travaux publics et vos éventuelles expériences terrain ou en laboratoire de matériaux.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Marseille ou les environs ?\r\nÊtes-vous véhiculé(e) et titulaire du permis B ?\r\nAvez-vous déjà effectué des relevés topographiques sur le terrain ?\r\nAvez-vous des connaissances en contrôle qualité des matériaux TP (enrobés, béton) ?\r\nAvez-vous déjà travaillé ou effectué un stage sur un chantier de voirie ou d\'infrastructure ?\r\n\r\nLangue :\r\nFrançais (requis)\r\n\r\nPermis/certification :\r\nPermis B (Requis)\r\n\r\nLieu du poste : En présentiel – déplacements sur chantiers', 'btp', 'Marseille', 590.00, '2026-06-01', '2026-08-31', 10, '2026-03-23 16:08:49'),
(11, 'Stage Cybersécurité', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nThales est un groupe technologique français de rang mondial, spécialisé dans la défense, l\'aéronautique, le spatial et la cybersécurité. Notre centre de Toulouse regroupe des équipes d\'experts en sécurité des systèmes d\'information, intervenant sur des projets critiques pour des clients institutionnels, industriels et gouvernementaux exigeant les plus hauts niveaux de protection.\r\n\r\nDans le cadre du renforcement de notre équipe cybersécurité, nous recherchons un(e) stagiaire ingénieur(e) en cybersécurité pour participer à l\'analyse, à la conception et à l\'amélioration de la sécurité de nos systèmes et infrastructures.\r\n\r\nLe stage portera principalement sur l\'audit de sécurité, la détection des vulnérabilités et la mise en place de mesures de protection adaptées. L\'objectif est de contribuer activement à la résilience de nos systèmes face aux menaces cyber en constante évolution.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire passionné(e) par la cybersécurité pour rejoindre notre équipe sécurité des systèmes d\'information. Vous travaillerez aux côtés d\'experts reconnus sur des problématiques de sécurité complexes et à fort enjeu. Vous participerez à des missions variées allant du pentest à la réponse à incident, en passant par la mise en conformité réglementaire. Si vous souhaitez évoluer dans un environnement technique exigeant au cœur des enjeux de souveraineté numérique, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Réaliser des audits de sécurité et des tests d\'intrusion (pentest) sur des applications web et des infrastructures réseau\r\n- Identifier et analyser les vulnérabilités des systèmes et proposer des mesures correctives adaptées\r\n- Participer à la mise en place et au maintien du système de gestion de la sécurité de l\'information (SGSI)\r\n- Surveiller les événements de sécurité via des outils SIEM (Splunk, QRadar) et analyser les alertes\r\n- Contribuer à la rédaction de politiques de sécurité, de procédures et de documentations techniques\r\n- Participer aux exercices de réponse à incident et aux analyses post-mortem\r\n- Effectuer une veille sur les nouvelles menaces, vulnérabilités (CVE) et techniques d\'attaque\r\n- Collaborer avec les équipes DevOps pour intégrer les bonnes pratiques de sécurité (DevSecOps)\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en cybersécurité, réseaux ou informatique (Bac+4 à Bac+5). Vous avez de solides connaissances en sécurité des réseaux (TCP/IP, firewalls, VPN) et en sécurité applicative (OWASP Top 10). La pratique des outils de pentest (Kali Linux, Metasploit, Burp Suite, Nmap) est indispensable. Une expérience avec les outils SIEM et les systèmes de détection d\'intrusion (IDS/IPS) est fortement appréciée. Vous êtes rigoureux(se), discret(e) et faites preuve d\'une éthique irréprochable. La connaissance des normes ISO 27001 et des réglementations RGPD est un atout majeur.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Toulouse\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation précisant vos expériences en cybersécurité, vos certifications éventuelles (CEH, OSCP, CompTIA Security+) et vos projets CTF ou académiques en lien avec le poste.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Toulouse ou les environs ?\r\nAvez-vous déjà réalisé des tests d\'intrusion ou des audits de sécurité ?\r\nMaîtrisez-vous les outils de pentest (Kali Linux, Burp Suite, Metasploit) ?\r\nAvez-vous une expérience avec les outils SIEM (Splunk, QRadar) ?\r\nAvez-vous des connaissances en normes de sécurité (ISO 27001, RGPD) ?\r\n\r\nLangue :\r\nAnglais (courant requis), Français (requis)\r\n\r\nPermis/certification :\r\nHabilitation de sécurité (souhaitée)\r\n\r\nLieu du poste : En présentiel', 'informatique', 'Toulouse', 710.00, '2026-06-15', '2026-09-15', 11, '2026-03-23 16:08:49'),
(12, 'Stage Ingénieur Structures', 'Description du poste\r\n\r\nQui sommes-nous ?\r\nSpie Batignolles est un groupe de construction indépendant intervenant dans les domaines du bâtiment, du génie civil et des travaux publics. Notre agence de Nantes pilote des projets d\'infrastructure et de construction ambitieux dans toute la région Pays de la Loire, en s\'appuyant sur une culture technique forte et un engagement constant pour la qualité et l\'innovation.\r\n\r\nDans le cadre du développement de notre bureau d\'études structures, nous recherchons un(e) stagiaire ingénieur(e) structures pour participer à la conception et à la vérification d\'ouvrages variés, allant du bâtiment tertiaire aux ouvrages de génie civil.\r\n\r\nLe stage portera principalement sur le calcul et le dimensionnement des structures, la modélisation numérique des ouvrages et l\'assistance technique aux équipes chantier. L\'objectif est de vous impliquer sur des projets concrets à fort enjeu technique, de la phase études jusqu\'au suivi de réalisation.\r\n\r\nÀ propos du poste\r\nNous recherchons un(e) stagiaire ingénieur(e) structures rigoureux(se) et motivé(e) pour rejoindre notre bureau d\'études. Vous travaillerez en étroite collaboration avec des ingénieurs seniors sur des projets structurellement complexes et contribuerez à toutes les phases techniques, de la conception préliminaire à la vérification des plans d\'exécution. Si vous êtes passionné(e) par la mécanique des structures et souhaitez acquérir une expérience solide en bureau d\'études, cette opportunité est faite pour vous.\r\n\r\nResponsabilités\r\n- Réaliser des calculs de dimensionnement et de vérification de structures (béton armé, charpente métallique, bois)\r\n- Modéliser des ouvrages à l\'aide de logiciels de calcul de structures (Robot Structural Analysis, SCIA Engineer)\r\n- Rédiger des notes de calcul conformes aux Eurocodes et aux normes françaises en vigueur\r\n- Produire et mettre à jour des plans d\'exécution en lien avec les équipes DAO (AutoCAD, Revit)\r\n- Participer aux réunions techniques avec les architectes, les maîtres d\'œuvre et les bureaux de contrôle\r\n- Assister les équipes chantier lors des visites de suivi et contrôler la conformité des travaux aux plans\r\n- Contribuer à la rédaction des dossiers techniques et des mémoires de synthèse\r\n- Effectuer une veille sur les évolutions réglementaires et normatives dans le domaine des structures\r\n\r\nProfil recherché\r\nVous êtes étudiant(e) en génie civil, ingénierie des structures ou école d\'ingénieurs avec spécialisation structure (Bac+4 à Bac+5). Vous maîtrisez les bases du calcul de structures et avez une bonne connaissance des Eurocodes (EC2, EC3). La pratique d\'un logiciel de modélisation (Robot, SCIA ou équivalent) est indispensable. Vous êtes à l\'aise avec AutoCAD et le Pack Office. Rigoureux(se), méthodique et curieux(se), vous appréciez autant le travail en bureau d\'études que les visites terrain. Le permis B est apprécié pour les déplacements sur site.\r\n\r\nDurée :\r\n6 mois\r\n\r\nLieu du stage :\r\nPrésentiel – Nantes\r\n\r\nPour postuler :\r\nMerci d\'envoyer votre CV accompagné d\'une lettre de motivation précisant vos compétences en calcul de structures, vos expériences avec les logiciels de modélisation et tout projet académique ou personnel en lien avec le poste.\r\n\r\nType d\'emploi : Stage\r\nDurée du contrat : 6 mois\r\n\r\nQuestion(s) de présélection :\r\nVous habitez Nantes ou les environs ?\r\nMaîtrisez-vous les Eurocodes (EC2 béton armé, EC3 charpente métallique) ?\r\nAvez-vous déjà utilisé un logiciel de calcul de structures (Robot, SCIA ou équivalent) ?\r\nAvez-vous réalisé une note de calcul dans le cadre d\'un projet ou d\'un cours ?\r\nMaîtrisez-vous AutoCAD ou Revit pour la production de plans d\'exécution ?\r\n\r\nLangue :\r\nAnglais (lu/écrit apprécié), Français (requis)\r\n\r\nPermis/certification :\r\nPermis B (Apprécié)\r\n\r\nLieu du poste : En présentiel', 'btp', 'Nantes', 610.00, '2026-07-01', '2026-09-30', 12, '2026-03-23 16:08:49');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('etudiant','pilote','admin') DEFAULT 'etudiant',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `offre_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `offre_id` (`offre_id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

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
  ADD KEY `etudiant_id` (`etudiant_id`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `offres`
--
ALTER TABLE `offres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `candidatures_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidatures_ibfk_2` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `offres_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
