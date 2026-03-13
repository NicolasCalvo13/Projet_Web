<section>
    <h1 class="text-center">Créez un compte</h1>
    <p>Pour déposer une annonce ou bénéficier d'une expérience personnalisée avec du contenu en lien avec vos recherches inscrivez-vous, c'est rapide et gratuit !</p>
    <article>
        <h2>Formulaire d'inscription</h2>
        <form action="/register" method="post">
            <div class="form-row">
                <label for="gender">Civilité</label>
                <select name="title" id="gender">
                    <option value="" disabled selected>--Sélectionnez--</option>
                    <option value="ms">Madame</option>
                    <option value="mr">Monsieur</option>
                </select>
            </div>
            <div class="form-row">
                <label for="lastname">Nom</label>
                <input name="lastname" id="lastname" type="text" required>
            </div>
            <div class="form-row">
                <label for="surname">Prénom</label>
                <input name="surname" id="surname" type="text" required>
            </div>
            <div class="form-row">
                <label for="email">Courriel</label>
                <input name="email" id="email" type="email" required>
            </div>
            <div class="form-row">
                <label for="password">Mot de passe</label>
                <input name="password" id="password" type="password" required>
            </div>
            <div class="form-row">
                <label for="password_confirm">Confirmation</label>
                <input name="password_confirm" id="password_confirm" type="password" required>
            </div>
            <div class="form-row">
                <button type="submit">Envoyer</button>
                <input type="reset" value="Réinitialiser">
            </div>
        </form>
    </article>
</section>
