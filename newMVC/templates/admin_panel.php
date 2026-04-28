<?php
$title = "Panneau admin";
$style = "templates/style/Accueil.css";
$script = "";

if (!isset($_SESSION['user'])) {
    header('Location: index.php?action=connection');
    exit();
}

require_once __DIR__ . '/../src/model/pdo.php';
require_once __DIR__ . '/../src/model/celebrity.php';
require_once __DIR__ . '/../src/model/product.php';

$pdo = DatabaseConnection::getConnection();
$celebrityRepository = new CelebrityRepository($pdo);
$productRepository   = new ProductRepository($pdo);

$user = $_SESSION['user'];
$products = getAllProduct_admin();
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .admin-page {
        width: 100%;
        padding: 30px 60px;
        font-family: 'Poppins', sans-serif;
    }

    .admin-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .admin-top h1 {
        font-size: 2.2rem;
        color: #111;
        margin: 0;
    }

    .admin-top p {
        margin: 6px 0 0;
        color: #666;
        font-size: 0.95rem;
    }

    .newsletter-box {
        background: #fff;
        border-radius: 18px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        margin-bottom: 40px;
    }

    .newsletter-box h2 {
        margin: 0 0 8px;
        color: #111;
    }

    .newsletter-box p {
        color: #666;
        margin-bottom: 18px;
    }

    .btn_frm_newsletter {
        background: #111;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .btn_frm_newsletter:hover {
        background: #000;
        transform: translateY(-2px);
    }

    .frm_new_newsletter {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .frm_new_newsletter form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .frm_new_newsletter label {
        font-weight: 600;
        color: #111;
        margin-bottom: 6px;
        display: block;
    }

    .frm_new_newsletter input,
    .frm_new_newsletter textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ddd;
        border-radius: 10px;
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        resize: none;
    }

    .frm_new_newsletter textarea {
        min-height: 140px;
    }

    .frm_new_newsletter button[type="submit"] {
        width: fit-content;
        background: #111;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
    }

    .admin-section-title {
        margin-bottom: 25px;
    }

    .admin-section-title h2 {
        font-size: 1.8rem;
        margin: 0;
        color: #111;
    }

    .admin-section-title p {
        color: #666;
        margin-top: 5px;
    }

    .annonces {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .announce-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        transition: 0.25s ease;
        display: flex;
        flex-direction: column;
    }

    .announce-card:hover {
        transform: translateY(-6px);
    }

    .announce-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .announce-card-content {
        padding: 18px;
    }

    .announce-card h3 {
        font-size: 1.1rem;
        margin: 0 0 12px;
        color: #111;
    }

    .announce-card p {
        margin: 6px 0;
        color: #555;
        font-size: 0.92rem;
    }

    .admin-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .btn_valide,
    .btn_supp {
        flex: 1;
        border: none;
        padding: 10px;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s ease;
    }

    .btn_valide {
        background: #111;
        color: white;
    }

    .btn_valide:hover {
        background: #000;
    }

    .btn_supp {
        background: #f3f3f3;
        color: #111;
    }

    .btn_supp:hover {
        background: #e5e5e5;
    }

    .empty-admin {
        background: white;
        padding: 30px;
        border-radius: 18px;
        text-align: center;
        color: #666;
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
    }

    /* POPUP */
    .popup-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .popup-box {
        background: white;
        padding: 25px;
        border-radius: 16px;
        min-width: 320px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .popup-box button {
        margin: 12px 6px 0;
        padding: 10px 18px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-weight: 600;
    }

    .popup-box #btnConfirm {
        background: #111;
        color: white;
    }

    .popup-box #btnCancel {
        background: #f3f3f3;
        color: #111;
    }
</style>

<?php ob_start(); ?>
<?php include __DIR__ . '/preset/header.php'; ?>

<main class="admin-page">

    <div class="admin-top">
        <div>
            <h1>Panneau administrateur</h1>
            <p>Gérez les newsletters et validez les annonces publiées.</p>
        </div>
    </div>

    <!-- NEWSLETTER -->
    <div class="newsletter-box">
        <h2>Créer une newsletter</h2>
        <p>Envoyez une newsletter à tous les abonnés de la plateforme.</p>

        <button class="btn_frm_newsletter" onclick="newsletter()">Créer une newsletter</button>

        <div id="frm_new_newsletter" class="frm_new_newsletter" style="display: none;">
            <form action="index.php?action=sendNewsletter" method="POST">
                <div>
                    <label for="title_news">Titre</label>
                    <input type="text" id="title_news" name="title_news" placeholder="Titre de votre newsletter" required>
                </div>

                <div>
                    <label for="content_mail_newsletter">Contenu</label>
                    <textarea id="content_mail_newsletter" name="content_mail_newsletter" placeholder="Écrivez votre newsletter ici..." required></textarea>
                </div>

                <button type="submit" name="action" value="submit_new_newsletter">
                    Envoyer
                </button>
            </form>
        </div>
    </div>

    <!-- PRODUITS -->
    <div class="admin-section-title">
        <h2>Annonces à vérifier</h2>
        <p>Validez ou supprimez les annonces proposées par les utilisateurs.</p>
    </div>

    <div class="annonces">
        <?php if (empty($products)): ?>
            <div class="empty-admin">
                <p>Aucune annonce disponible pour le moment.</p>
            </div>
        <?php else: ?>

            <?php
            $count_displayed = 0;
            $max_to_display = 12;
            ?>

            <?php foreach ($products as $p): ?>
                <?php
                if ($count_displayed >= $max_to_display) break;

                $images = getImage($p['id_product']);
                $cate   = $productRepository->getCategoryFromAnnoncement($p['id_product']);
                $cele   = $celebrityRepository->getCelebrityFromAnnoncement($p['id_product']);
                ?>

                <div class="announce-card">
                    <?php if (!empty($images)): ?>
                        <img src="<?= htmlspecialchars($images[0]['url_image']) ?>" alt="Image annonce">
                    <?php else: ?>
                        <div style="height:220px;display:flex;align-items:center;justify-content:center;background:#f5f5f5;">
                            Aucune image disponible
                        </div>
                    <?php endif; ?>

                    <div class="announce-card-content">
                        <h3><?= htmlspecialchars($p['title']) ?></h3>

                        <p>
                            <strong>Catégorie :</strong>
                            <?= ($cate && isset($cate['name'])) ? htmlspecialchars($cate['name']) : 'Non spécifiée'; ?>
                        </p>

                        <p>
                            <strong>Célébrité :</strong>
                            <?= ($cele && isset($cele['name'])) ? htmlspecialchars($cele['name']) : 'Non spécifiée'; ?>
                        </p>

                        <div class="admin-actions">
                            <button class="btn_valide"
                                onclick="alertConfirmation('Valider cette annonce ?', 'validateAnnoncement', <?= $p['id_product']; ?>)">
                                Valider
                            </button>

                            <button class="btn_supp"
                                onclick="alertConfirmation('Supprimer cette annonce ?', 'deleteProductAdmin', <?= $p['id_product']; ?>)">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <?php $count_displayed++; ?>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</main>

<script src="templates/JS/Annonce_publie_client.js" defer></script>
<script src="templates/JS/Newsletter.js" defer></script>

<?php include __DIR__ . '/preset/footer.php'; ?>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/preset/layout.php'; ?>