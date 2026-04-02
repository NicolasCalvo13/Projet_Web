document.getElementById('offerForm').addEventListener('submit', function (e) {
    let valid = true;
    document.querySelectorAll('.form-error').forEach(el => el.style.display = 'none');

    const fields = [
        { id: 'titre',       errorId: 'titre-error',      check: v => v.trim() !== '' },
        { id: 'ville',       errorId: 'ville-error',      check: v => v.trim() !== '' },
        { id: 'duree',       errorId: 'duree-error',      check: v => v > 0 },
        { id: 'date_debut',  errorId: 'date-error',       check: v => v !== '' },
        { id: 'description', errorId: 'desc-error',       check: v => v.trim().length >= 30 },
        // Champ admin uniquement (ignoré si absent du DOM)
        { id: 'entreprise_id', errorId: 'entreprise-error', check: v => v !== '' },
    ];

    fields.forEach(({ id, errorId, check }) => {
        const el = document.getElementById(id);
        if (!el) return; // champ absent (vue entreprise) → on skip
        if (!check(el.value)) {
            const err = document.getElementById(errorId);
            if (err) err.style.display = 'block';
            valid = false;
        }
    });

    if (!valid) e.preventDefault();
});