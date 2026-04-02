function initStatsCarousel() {
    const carousel = document.getElementById('statCarousel');
    const dots = document.querySelectorAll('.nav-dot');

    if (!carousel || dots.length === 0) return;

    carousel.addEventListener('scroll', () => {
        const index = Math.round(carousel.scrollLeft / (carousel.offsetWidth * 0.8));

        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    });

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

document.addEventListener("DOMContentLoaded", initStatsCarousel);