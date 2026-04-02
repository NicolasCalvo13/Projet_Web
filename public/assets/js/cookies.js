document.addEventListener("DOMContentLoaded", function() {
    const cookieBanner = document.getElementById("cookie-banner");
    const acceptBtn = document.getElementById("accept-cookies");
    const declineBtn = document.getElementById("decline-cookies");

    // Si le bandeau n'existe pas sur la page, on arrête le script
    if (!cookieBanner) return;

    // 1. On vérifie si notre cookie "stagelink_consent" existe déjà
    const hasConsented = document.cookie.split('; ').find(row => row.startsWith('stagelink_consent='));

    // 2. S'il n'existe pas, on ajoute la classe "show" pour l'afficher
    if (!hasConsented) {
        cookieBanner.classList.add("show");
    }

    // 3. Fonction pour sauvegarder le choix et cacher le bandeau
    function setCookie(value) {
        const d = new Date();
        d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000)); // Expire dans 30 jours
        
        document.cookie = "stagelink_consent=" + value + ";expires=" + d.toUTCString() + ";path=/";
        
        // Animation de disparition
        cookieBanner.style.transition = "opacity 0.3s ease";
        cookieBanner.style.opacity = "0";
        setTimeout(() => {
            cookieBanner.classList.remove("show");
            cookieBanner.style.display = "none";
        }, 300);
    }

    // 4. Écouteurs d'événements
    if (acceptBtn) acceptBtn.addEventListener("click", () => setCookie("accepted"));
    if (declineBtn) declineBtn.addEventListener("click", () => setCookie("declined"));
});