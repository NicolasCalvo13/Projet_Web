<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <a href="/">Accueil</a><span>›</span>
    Ma wish-list
</nav>

<h1 style="color:#007575;">Ma wish-list ♡</h1>

<section class="section-card">
    <h2>Offres sauvegardées</h2>
    <table class="table-list" aria-label="Ma wish-list">
        <thead>
            <tr>
                <th>Offre</th>
                <th>Entreprise</th>
                <th>Rémunération</th>
                <th>Compétences</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="wishlist-body">
            <tr>
                <td data-label="Offre"><a href="/offres/1" style="color:#008b8b;">Titre de l'offre</a></td>
                <td data-label="Entreprise"><a href="/entreprises/1" style="color:#008b8b;">Nom de l'entreprise</a></td>
                <td data-label="Rémunération">-- €/mois</td>
                <td data-label="Compétences"><span class="badge">Compétence</span></td>
                <td data-label="Actions">
                    <a href="/offres/1/apply" class="btn-primary" style="padding:7px 14px;font-size:.85rem;">Postuler</a>
                    <button class="btn-danger" onclick="removeWishlist(this)" style="padding:7px 14px;font-size:.85rem;">✕ Retirer</button>
                </td>
            </tr>
        </tbody>
    </table>
    <p style="margin-top:16px;font-size:.9rem;color:#777;">Total : <strong id="wishlist-count">1</strong> offre(s) en favoris</p>
</section>

<a href="/offres" class="btn-secondary">← Voir toutes les offres</a>

<script>
function removeWishlist(btn) {
    const row = btn.closest('tr');
    row.style.transition = 'opacity .3s';
    row.style.opacity = '0';
    setTimeout(() => {
        row.remove();
        const remaining = document.querySelectorAll('#wishlist-body tr').length;
        document.getElementById('wishlist-count').textContent = remaining;
    }, 300);
}
</script>
