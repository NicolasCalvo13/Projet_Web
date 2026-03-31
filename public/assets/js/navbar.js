// public/assets/js/navbar.js

document.addEventListener('DOMContentLoaded', () => {
    // On récupère les paramètres de l'URL (ex: ?uri=offers)
    const params = new URLSearchParams(window.location.search);

    // On extrait la valeur de "uri" (si elle n'existe pas, on considère qu'on est sur "home")
    const currentUri = params.get('uri') || 'home';

    // On cible tous les liens de la barre de navigation
    const navLinks = document.querySelectorAll('.navbar a');

    // On parcourt chaque lien
    navLinks.forEach(link => {
        // Si le lien correspond à la page actuelle, on lui ajoute la classe "active"
        if (link.getAttribute('href') === '/?uri=' + currentUri) {
            link.classList.add('active');
        }
    });
});