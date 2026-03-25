# 🎓 Stage-Link

Plateforme web de mise en relation entre étudiants et entreprises pour la recherche de stages.
Projet développé dans le cadre de la formation ingénieur CESI.

---

## 📋 Description

Stage-Link est une application web MVC développée en PHP permettant :
- Aux **étudiants** de consulter et postuler à des offres de stage
- Aux **entreprises** de publier et gérer leurs offres
- Aux **pilotes de promotion** de suivre les candidatures des étudiants
- Aux **administrateurs** de gérer l'ensemble de la plateforme

---

## 🚀 Fonctionnalités

### 👨‍🎓 Étudiants
- Consultation des offres de stage (toutes, IT, BTP)
- Détail d'une offre avec description complète
- Candidature en ligne (CV + lettre de motivation)
- Wishlist d'offres favorites
- Suivi de ses candidatures

### 🏢 Entreprises
- Connexion à un espace dédié
- Consultation des candidatures reçues

### 👨‍🏫 Pilotes
- Suivi des étudiants et de leurs candidatures

### 🔧 Administrateurs
- Tableau de bord avec statistiques globales
- Gestion des offres, entreprises, pilotes et étudiants
- Création de comptes pilotes et étudiants

### 🌐 Pages publiques
- Page d'accueil avec offres récentes
- Fiche détail entreprise avec avis et notation
- Formulaire de contact
- Page d'avis
- Mentions légales & gestion des cookies

---

## 🛠️ Stack technique

| Technologie | Usage |
|---|---|
| PHP 8+ | Backend / Controllers |
| Twig | Moteur de templates |
| MySQL | Base de données |
| HTML / CSS | Frontend |
| JavaScript | Interactions côté client |
| Composer | Gestionnaire de dépendances |
| XAMPP | Serveur local (Apache + MySQL) |

---

## 📁 Structure du projet

```
Projet_Web/
├── public/                  # Point d'entrée (index.php)
│   └── assets/
│       ├── style.css        # Feuille de styles principale
│       └── images/          # Logos, icônes
├── src/
│   ├── Controller/          # Controllers MVC
│   ├── Model/               # Modèles BDD
│   └── Routing/             # Routeur
├── templates/               # Templates Twig
│   ├── admin/               # Dashboard admin
│   ├── applications/        # Candidatures
│   ├── auth/                # Connexion / Inscription
│   ├── companies/           # Fiches entreprises
│   ├── home/                # Page d'accueil
│   ├── offers/              # Offres de stage
│   ├── static/              # Pages statiques
│   └── base.twig.html       # Template de base
├── vendor/                  # Dépendances Composer
├── stage-link.sql           # Script de la base de données
└── composer.json
```

---

## ⚙️ Installation

### Prérequis
- XAMPP (Apache + MySQL + PHP 8+)
- Composer

### Étapes

**1. Cloner le dépôt**
```bash
git clone https://github.com/NicolasCalvo13/Projet_Web.git
cd Projet_Web
```

**2. Installer les dépendances**
```bash
composer install
```

**3. Importer la base de données**
- Ouvrir phpMyAdmin : `http://localhost/phpmyadmin`
- Créer une base de données nommée `stage_link`
- Importer le fichier `stage-link.sql` fourni à la racine du projet

**4. Configurer la connexion BDD**

Modifier les paramètres de connexion dans les fichiers Model concernés :
```php
$this->pdo = new PDO(
    'mysql:host=localhost;dbname=stage_link;charset=utf8',
    'root',   // utilisateur
    ''        // mot de passe
);
```

**5. Lancer le projet**
- Placer le projet dans `C:/xampp/htdocs/stage-link/`
- Démarrer Apache et MySQL depuis XAMPP
- Accéder à : `http://localhost/stage-link/public/`

---

## 👥 Contributeurs

| Nom | GitHub |
|---|---|
| Nicolas Calvo | [@NicolasCalvo13](https://github.com/NicolasCalvo13) |
| Paul Pretot | [@PaulPretot](https://github.com/PaulPretot) |
| Gianni Rodrigez | — |
| Matis Billet | [@Mat8313](https://github.com/Mat8313) |

---

## 📌 État du projet

> ⚠️ Projet en cours de développement — certaines fonctionnalités sont encore en construction.

| Fonctionnalité | État |
|---|---|
| Navigation & routing | ✅ Terminé |
| Pages offres (liste, détail) | ✅ Terminé |
| Fiches entreprises | ✅ Terminé |
| Dashboard admin | ✅ Terminé |
| Candidatures | ✅ Terminé |
| Wishlist | ✅ Terminé |
| Authentification | 🔄 En cours |
| Connexion entreprise | 🔄 En cours |
| Connexion BDD complète | 🔄 En cours |

---

## 📄 Licence

Projet académique — CESI 2026. Tous droits réservés.
