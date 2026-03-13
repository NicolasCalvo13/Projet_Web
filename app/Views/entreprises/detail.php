<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Fiche entreprise - StageLink, la plateforme de recherche de stages CESI">
    <meta name="keywords" content="entreprise, stage, CESI, StageLink">
    <title>Fiche entreprise - StageLink</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header class="header-style">
    <div class="logo-placement">
        <a href="/"><img src="assets/images/logo.png" alt="Logo StageLink" class="icon-style" style="width:80px;height:auto;"></a>
    </div>
    <div class="btn-placement">
        <a href="/login"><img src="./static/assets/images/icon-user.png" alt="Mon compte" class="icon-style"></a>
        <a href="wishlist.html"><img src="./static/assets/images/icon-fav.png"  alt="Favoris" class="icon-style"></a>
    </div>
</header>

<label class="burger-label" for="menu-toggle">
    <span class="bar"></span><span class="bar"></span><span class="bar"></span>
</label>
<input type="checkbox" id="menu-toggle">

<nav class="navbar" aria-label="Navigation principale">
    <a href="/">Accueil</a>
    <a href="/offres">Toutes les Offres</a>
    <a href="/offres?type=it">Offres Informatiques</a>
    <a href="/offres?type=btp">Offres BTP</a>
    <a href="/register">Inscription</a>
    <a href="/login">Connexion</a>
    <a href="avis.html">Avis</a>
    <a href="/contact">Contact</a>
</nav>

<main>

    <nav class="breadcrumb" aria-label="Fil d'Ariane">
        <a href="/">Accueil</a><span>›</span>
        <a href="/offres">Offres</a><span>›</span>
        Nom de l'entreprise
    </nav>

    <section class="company-hero" aria-labelledby="company-name">
        <img src="assets/images/company-placeholder.png" alt="Logo de l'entreprise" class="company-logo">
        <div class="company-hero-info">
            <h1 id="company-name">Nom de l'entreprise</h1>
            <p>📧 <a href="#">email@entreprise.fr</a></p>
            <p>📞 00 00 00 00 00</p>
            <p>👥 <strong>--</strong> stagiaires ayant postulé</p>
            <div class="rating-bar-wrap">
                <span class="stars" aria-label="Note moyenne">★★★★☆</span>
                <div class="rating-bar"><div class="rating-fill" style="width:80%"></div></div>
                <strong>-- / 5</strong> <span style="color:#aaa;font-size:.85rem;">(-- avis)</span>
            </div>
        </div>
    </section>

    <section class="section-card" aria-labelledby="desc-title">
        <h2 id="desc-title">Description</h2>
        <p>Description de l'entreprise à afficher ici.</p>
    </section>

    <section class="section-card" aria-labelledby="offers-title">
        <h2 id="offers-title">Offres de stage disponibles</h2>
        <div class="offer-mini">
            <div>
                <strong>Titre de l'offre</strong><br>
                <span class="badge">Compétence</span>
                <span class="badge">-- €/mois</span>
            </div>
            <a href="offer-detail.html" class="btn-secondary">Voir l'offre</a>
        </div>
    </section>

    <section class="section-card" aria-labelledby="avis-title">
        <h2 id="avis-title">Avis des stagiaires</h2>
        <div class="avis-card">
            <span class="avis-author">Prénom N.</span>
            <span class="avis-date">Mois Année</span>
            <div class="stars" aria-label="Note">★★★★☆</div>
            <p>Contenu de l'avis à afficher ici.</p>
        </div>
        <div style="margin-top:16px;">
            <a href="avis.html" class="btn-primary">Laisser un avis</a>
        </div>
    </section>

    <a href="/offres" class="btn-secondary">← Retour aux offres</a>

</main>

<footer class="footer-style">
    <nav aria-label="Liens du footer">
        <a href="/offres?type=it">Offres IT</a> |
        <a href="/offres">Toutes les offres</a> |
        <a href="/offres?type=btp">Offres BTP</a>
    </nav>
    <p>© 2026 - Tous droits réservés</p>
    <nav aria-label="Liens légaux">
        <a href="mentions-légales.html">Mentions légales</a> |
        <a href="gestion-cookies.html">Gestion des cookies</a>
    </nav>
</footer>

</body>
</html>
