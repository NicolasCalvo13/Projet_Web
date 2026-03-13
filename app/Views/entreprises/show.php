<section>
    <h1 class="text-center">Votre avis nous intéresse</h1>
    <p>Vous avez une remarque à nous faire concernant nos services ? Utilisez ce formulaire en nous fournissant le maximum d'informations.</p>
    <article>
        <h2>Description</h2>
        <form action="/avis" method="post">
            <div class="form-row">
                <label for="nom">Nom complet*</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-row">
                <label for="courriel">Courriel*</label>
                <input type="email" id="courriel" name="courriel" required>
            </div>
            <div class="form-row">
                <label for="categorie">Objet de votre demande</label>
                <select id="categorie" name="categorie">
                    <option selected disabled>--Sélectionnez--</option>
                    <option>Produit acheté</option>
                    <option>Service commercial</option>
                    <option>Service technique</option>
                    <option>Autres</option>
                </select>
            </div>
            <div class="form-row form-row--wrap">
                <label>Je suis satisfait</label>
                <div class="radio-group">
                    <label class="check-item"><input type="radio" name="satisfaction" value="yes" checked> Oui</label>
                    <label class="check-item"><input type="radio" name="satisfaction" value="no"> Non</label>
                </div>
            </div>
            <div class="form-row">
                <label for="commentaires">Commentaires</label>
                <textarea id="commentaires" name="commentaires" rows="6"></textarea>
            </div>
            <div class="form-row">
                <button type="submit">Envoyer</button>
                <input type="reset" value="Effacer">
            </div>
        </form>
    </article>
</section>
