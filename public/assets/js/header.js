document.addEventListener('DOMContentLoaded', () => {
    const header     = document.getElementById('main-header');
    const logo       = document.querySelector('.header-logo');
    const burgerBtn  = document.getElementById('burger-btn');
    const burgerMenu = document.getElementById('burger-menu');

    let lastScrollY = 0;
    let ticking     = false;

    function updateHeader() {
        if (window.scrollY > 80) {
            header.classList.add('header-shrink');
            logo.width = 130;
        } else if (window.scrollY < 40) {
            // ← seuil de retour DIFFÉRENT du seuil d'activation
            header.classList.remove('header-shrink');
            logo.width = 200;
        }
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        lastScrollY = window.scrollY;
        if (!ticking) {
            requestAnimationFrame(updateHeader);
            ticking = true;
        }
    });

    // Burger toggle
    burgerBtn.addEventListener('click', () => {
        const isOpen = burgerMenu.classList.toggle('open');
        burgerBtn.setAttribute('aria-expanded', isOpen);
    });

    burgerMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            burgerMenu.classList.remove('open');
            burgerBtn.setAttribute('aria-expanded', false);
        });
    });
});