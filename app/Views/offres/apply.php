<nav class="breadcrumb" aria-label="Fil d'Ariane">
    <a href="/">Accueil</a><span>›</span>
    <a href="/offres">Offres</a><span>›</span>
    Postuler
</nav>

<h1 style="color:#007575;">Postuler à une offre</h1>

<div class="apply-offer-recap" aria-label="Récapitulatif de l'offre">
    <div>
        <strong>Titre de l'offre</strong><br>
        <span>Nom de l'entreprise — Ville (00)</span>
    </div>
    <div>
        <span class="badge">💶 -- €/mois</span>
        <span class="badge">⏱ -- mois</span>
    </div>
</div>

<section class="section-card">
    <h2>Votre candidature</h2>
    <form method="POST" action="/offres/1/apply" enctype="multipart/form-data" id="applyForm" novalidate>
        <div class="form-group">
            <label for="cv">CV (PDF uniquement, 5 Mo max) <span style="color:red">*</span></label>
            <input type="file" id="cv" name="cv" accept=".pdf" required>
            <span class="form-error" id="cv-error">Veuillez fournir un CV au format PDF.</span>
        </div>
        <div class="form-group">
            <label for="lm">Lettre de motivation <span style="color:red">*</span></label>
            <textarea id="lm" name="lm" maxlength="3000" placeholder="Rédigez votre lettre de motivation ici..." required oninput="updateCount(this, 'lm-count')"></textarea>
            <div style="font-size:.8rem;color:#aaa;text-align:right;margin-top:4px;"><span id="lm-count">0</span> / 3000 caractères</div>
            <span class="form-error" id="lm-error">La lettre de motivation est obligatoire (50 caractères minimum).</span>
        </div>
        <div class="form-group" style="display:flex;gap:15px;flex-wrap:wrap;align-items:center;">
            <button type="submit" class="btn-primary">Envoyer ma candidature</button>
            <a href="/offres/1" class="btn-secondary">Annuler</a>
        </div>
    </form>
</section>

<script>
function updateCount(el, countId) {
    document.getElementById(countId).textContent = el.value.length;
}
document.getElementById('applyForm').addEventListener('submit', function(e) {
    let valid = true;
    document.querySelectorAll('.form-error').forEach(el => el.style.display = 'none');
    const cv = document.getElementById('cv');
    const lm = document.getElementById('lm');
    if (!cv.value) { document.getElementById('cv-error').style.display = 'block'; valid = false; }
    if (lm.value.trim().length < 50) { document.getElementById('lm-error').style.display = 'block'; valid = false; }
    if (!valid) e.preventDefault();
});
</script>
