<?php
if (!isset($_SESSION['user'])) {
    header('location: index.php?action=connection');
    exit();
}

$user = $_SESSION['user'];

$title = "Page de vente";
$style = "templates/style/sellProduct.css";
?>

<?php ob_start(); ?>
<?php include('preset/header.php'); ?>

<div class="page-vendre">

    <h1>Mise en enchère de votre produit</h1>

    <form class="form-vente-produit" action="index.php?action=addProduct" method="POST" enctype="multipart/form-data">

        <div class="vente-grid">
            <div class="col-left">
                <div class="form-group">
                    <label class="form-label">Nom de votre annonce</label>
                    <input type="text" name="nom_annonce_vente" placeholder="Nom de votre annonce" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Catégorie</label>
                    <input type="text" name="lst_categorie_vente" id="lst_categorie_vente" placeholder="Écrivez votre catégorie" required>
                    <div id="categorie_results" class="autocomplete-results"></div>
                </div>

                <div class="form-group reserve-group">
                    <label class="form-label">Réserve</label>
                    <div class="reserve-row">
                        <input type="checkbox" id="prix_reserve_checkbox" onclick="afficherInputPrixReserve()">
                        <span>Activer un prix de réserve</span>
                    </div>
                    <div id="input_prix_reserve" class="reserve-input-wrap"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Date de début</label>
                    <input type="date" name="date_debut" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Date de fin</label>
                    <input type="date" name="date_fin" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Célébrité</label>
                    <input type="text" name="inputcelebrity" id="inputcelebrity" placeholder="Rechercher une célébrité">
                    <div id="celebrity_results" class="autocomplete-results"></div>
                </div>
            </div>

            <div class="col-right">
                <div class="img-selector">
                    <label class="form-label">Ajouter des images</label>
                    <p class="img-info">Vous pouvez ajouter jusqu'à 4 images</p>

                    <div class="img-grid">

                        <div class="input-selector-image">
                            <label class="main-btn" for="img1">Image 1</label>
                            <input type="file" id="img1" name="image_produit[]" class="img_selector_input" accept="image/*">
                            <img id="img_preview_1" class="img-preview" src="" alt="">
                        </div>

                        <div class="input-selector-image">
                            <label class="main-btn" for="img2">Image 2</label>
                            <input type="file" id="img2" name="image_produit[]" class="img_selector_input" accept="image/*">
                            <img id="img_preview_2" class="img-preview" src="" alt="">
                        </div>

                        <div class="input-selector-image">
                            <label class="main-btn" for="img3">Image 3</label>
                            <input type="file" id="img3" name="image_produit[]" class="img_selector_input" accept="image/*">
                            <img id="img_preview_3" class="img-preview" src="" alt="">
                        </div>

                        <div class="input-selector-image">
                            <label class="main-btn" for="img4">Image 4</label>
                            <input type="file" id="img4" name="image_produit[]" class="img_selector_input" accept="image/*">
                            <img id="img_preview_4" class="img-preview" src="" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="certificat-authenticite-vente">
            <label class="form-label">Certificat d'authenticité (PDF)</label>
            <label class="main-btn certificat-btn" for="certificat_authenticite">
                Choisir un fichier
            </label>
            <input type="file" name="certificat_autenticite" id="certificat_authenticite" class="img_selector_input" accept="application/pdf,image/*">
            <span id="certificat-filename" class="certificat-filename">Aucun fichier choisi</span>
            <embed id="pdf_preview" src="" type="application/pdf" style="display:none;">
        </div>

        <div class="description-produit-vente">
            <label class="form-label">Description du produit</label>
            <textarea name="description_produit" id="description_produit" placeholder="Vous pouvez ici décrire plus en détail votre produit..." required></textarea>
        </div>

        <button type="submit" class="submit-btn">Publier</button>
    </form>
</div>

<?php include('preset/footer.php'); ?>

<script src="templates/JS/vente_produit.js"></script>

<?php $content = ob_get_clean(); ?>
<?php require('preset/layout.php'); ?>