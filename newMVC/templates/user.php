<?php
$title = "Page d'utilisateur";
$style = "templates/style/Accueil.css";
$script = "";

if (!isset($_SESSION['user'])) {
    redirectTo('index.php?action=login');
    exit();
}

$user = $_SESSION['user'];
?>

<?php ob_start(); ?>
<?php include('preset/header.php'); ?>
<link href="templates/style/stylePopup.css" rel="stylesheet" />

<main>

    <!-- ================= TITRE ================= -->
    <div class="Historique_annonces">
        <h1>Mon compte</h1>
        <p style="text-align:center; margin-top:-10px; color:#aaa;">
            Bienvenue <?= htmlspecialchars(strip_tags($user['firstname'])) ?>,
            gérez votre profil et suivez vos annonces.
        </p>
    </div>

    <!-- ================= INFOS ================= -->
    <div class="Historique_annonces">
        <h2>Mes informations</h2>

        <div class="Annonces-list-cards">

            <div class="card">
                <h3>Nom</h3>
                <input type="text"
                    value="<?= htmlspecialchars(strip_tags($user['name'])) ?>"
                    disabled>
            </div>

            <div class="card">
                <h3>Prénom</h3>
                <input type="text"
                    value="<?= htmlspecialchars(strip_tags($user['firstname'])) ?>"
                    disabled>
            </div>

            <div class="card">
                <h3>Email</h3>
                <input type="email"
                    value="<?= htmlspecialchars(strip_tags($user['email'])) ?>"
                    disabled>
                <button class="btns" type="button" onclick="ouvrirPopup('Email')">Modifier</button>
            </div>

            <div class="card">
                <h3>Adresse</h3>
                <input type="text"
                    value="<?= htmlspecialchars(strip_tags($user['address'])) . ' ' .
                                htmlspecialchars(strip_tags($user['postal_code'])) . ' ' .
                                htmlspecialchars(strip_tags($user['city'])) ?>"
                    disabled>
                <button class="btns" type="button" onclick="ouvrirPopup('Adresse')">Modifier</button>
            </div>

            <div class="card">
                <h3>Mot de passe</h3>
                <input type="password" value="************" disabled>
                <button class="btns" type="button" onclick="ouvrirPopup('Password')">Modifier</button>
            </div>

        </div>
    </div>

    <!-- POPUP -->
    <div id="popup"></div>

    <!-- ================= STATS ================= -->
    <div class="Historique_annonces">
        <h2>Mes statistiques</h2>

        <?php $annoncements = get_all_annoncement($user["id_user"]); ?>

        <input type="hidden" id="number_annoncement" name="action">
        <input type="hidden" id="values_annoncements"
            value='<?= htmlspecialchars(json_encode($annoncements, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, "UTF-8"); ?>'>

        <div class="stat_annonce"></div>
    </div>

    <!-- ================= MES ANNONCES ================= -->
    <div class="Historique_annonces">
        <h2>Mes annonces</h2>

        <div id="div_end_annoncement_with_reserved" style="display: none;">
            <input type="hidden" id="id_user" value="<?= $user["id_user"] ?>">
        </div>

        <div class="Product_verif_admin" id="Product_verif_admin"></div>
    </div>

    <!-- ================= ACTIONS ================= -->
    <div class="Historique_annonces">
        <h2>Accès rapide</h2>

        <div class="Annonces-list-cards">

            <div class="card">
                <h3>Historique</h3>
                <p>Consultez toutes vos annonces publiées.</p>
                <a id="btn_historique_annonce_published"
                    class="btns"
                    href="index.php?action=historique_annonces_publiees">
                    Voir
                </a>
            </div>

            <div class="card">
                <h3>Favoris</h3>
                <p>Retrouvez vos produits favoris.</p>
                <a class="btns" href="index.php?action=favorites">
                    Voir
                </a>
            </div>

        </div>
    </div>

</main>

<script src="templates/JS/OuverturePopUp.js"></script>
<script src="templates/JS/timer.js"></script>
<script src="templates/JS/Annonce_publie_client.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php include('preset/footer.php'); ?>

<?php $content = ob_get_clean(); ?>

<?php require('preset/layout.php'); ?>