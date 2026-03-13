<section>
    <h1 class="text-center">Bonjour !</h1>
    <p>Connectez-vous pour retrouver vos annonces sauvegardées et déposées.</p>
    <article>
        <h2>Formulaire de connexion</h2>
        <form action="/login" method="post">
            <div class="form-row">
                <label for="email">Courriel</label>
                <input name="email" id="email" type="email" required>
            </div>
            <div class="form-row">
                <label for="password">Mot de passe</label>
                <input name="password" id="password" type="password" required>
            </div>
            <div class="form-row">
                <button type="submit">Connexion</button>
                <input type="reset" value="Effacer">
            </div>
        </form>
    </article>
</section>
