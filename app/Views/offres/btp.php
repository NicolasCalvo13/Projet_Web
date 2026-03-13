<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StageLink</title>
    <link rel="stylesheet" href="/static/assets/style.css">
</head>

<body>

    <header class="header-style">
        <div class="logo-placement">
            <img src="/static/assets/images/logo.png" alt="Logo StageLink" width="200">
        </div>
        <div class="btn-placement">
            <a href="/login" class="btn-compte">
                <img src="/static/assets/images/icon-user.png" alt="Mon compte" width="50" height="50">
            </a>
            <a href="favoris.html" class="btn-favs">
                <img src="/static/assets/images/icon-fav.png" alt="Favoris" width="50" height="50">
            </a>
        </div>
    </header>

    <div>
        <input type="checkbox" id="menu-toggle">
        <label class="burger-label" for="menu-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </label>
        <nav class="navbar">
            <a href="/"><span class="active">Accueil</span></a>
            <a href="/offres">Toutes les Offres</a>
            <a href="/offres?type=it">Offres Informatiques</a>
            <a href="/offres?type=btp">Offres BTP</a>
            <a href="/register">Inscription</a>
            <a href="/login">Connexion</a>
            <a href="avis.html">Avis</a>
            <a href="/contact">Contact</a>
        </nav>
    </div>

    <main>
        <div class="images">
            <a href="offer-detail.html" class="card"><img src="/static/assets/images/placeholder.png" alt="Offres IT" ></a>
            <a href="offer-detail.html" class="card"><img src="/static/assets/images/placeholder.png" alt="Toutes les offres" ></a>
            <a href="offer-detail.html" class="card"><img src="/static/assets/images/placeholder.png" alt="Offres BTP" ></a>
        </div>
    </main>

    <footer class="footer-style">
        <a href="mentions-légales.html"><span>Mentions légales</span></a>
        <div>&copy; 2026 - Tous droits réservés</div>
        <a href="cookies.html"><span>Gestion des cookies</span></a>
    </footer>

</body>
</html>
