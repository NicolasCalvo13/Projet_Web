document.addEventListener("DOMContentLoaded", function () {
    const cookieBanner = document.getElementById("cookie-banner");
    const acceptBtn = document.getElementById("accept-cookies");
    const declineBtn = document.getElementById("decline-cookies");

    if (!cookieBanner) return;

    const hasConsented = document.cookie.split('; ').find(row => row.startsWith('stagelink_consent='));

    if (!hasConsented) {
        cookieBanner.classList.add("show");
    }

    function setCookie(value) {
        const d = new Date();
        d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));

        document.cookie = "stagelink_consent=" + value + ";expires=" + d.toUTCString() + ";path=/";

        cookieBanner.style.transition = "opacity 0.3s ease";
        cookieBanner.style.opacity = "0";
        setTimeout(() => {
            cookieBanner.classList.remove("show");
            cookieBanner.style.display = "none";
        }, 300);
    }

    if (acceptBtn) acceptBtn.addEventListener("click", () => setCookie("accepted"));
    if (declineBtn) declineBtn.addEventListener("click", () => setCookie("declined"));
});