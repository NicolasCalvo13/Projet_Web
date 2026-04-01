document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const currentUri = params.get('uri') || 'home';
    const navLinks = document.querySelectorAll('.navbar a');

    navLinks.forEach(link => {
        if (link.getAttribute('href') === '/?uri=' + currentUri) {
            link.classList.add('active');
        }
    });
});