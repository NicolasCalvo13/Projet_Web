/**
 * Gestion du carrousel de statistiques admin
 */
function initStatsCarousel() {
    const carousel = document.getElementById('statCarousel');
    const dots = document.querySelectorAll('.nav-dot');

    if (!carousel || dots.length === 0) return;

    carousel.addEventListener('scroll', () => {
        // On calcule l'index de la carte visible
        const index = Math.round(carousel.scrollLeft / (carousel.offsetWidth * 0.8));
        
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    });

    // Permettre de cliquer sur un point pour scroller
    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            const scrollAmount = carousel.offsetWidth * 0.8 * i;
            carousel.scrollTo({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });
    });
}

// On lance l'initialisation au chargement
document.addEventListener("DOMContentLoaded", initStatsCarousel);