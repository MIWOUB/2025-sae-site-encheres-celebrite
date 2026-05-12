<?php
$title = "Panneau admin";
$style = "templates/Style/adminPannel.css";
$script = "";

$products = $productsWithMeta ?? [];
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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

        <button class="btn-frm-newsletter" onclick="newsletter()">Créer une newsletter</button>

        <div id="frm_new_newsletter" class="frm-new-newsletter" style="display: none;">
            <form action="index.php?action=sendNewsletter" method="POST">
                <div>
                    <label for="title_news">Titre</label>
                    <input type="text" id="title_news" name="title_news" placeholder="Titre de votre newsletter"
                        required>
                </div>
                <div>
                    <label for="content_mail_newsletter">Contenu</label>
                    <textarea id="content_mail_newsletter" name="content_mail_newsletter"
                        placeholder="Écrivez votre newsletter ici..." required></textarea>
                </div>
                <button type="submit" name="action" value="submit_new_newsletter">Envoyer</button>
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
                if ($count_displayed >= $max_to_display)
                    break;
                ?>
                <div class="announce-card">
                    <?php if (!empty($p['image_url'])): ?>
                        <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="Image annonce">
                    <?php else: ?>
                        <div class="announce-card-no-image">Aucune image disponible</div>
                    <?php endif; ?>

                    <div class="announce-card-content">
                        <h3><?= htmlspecialchars($p['title']) ?></h3>
                        <div class="announce-card-meta">
                            <span>
                                <span class="meta-label">Catégorie :</span>
                                <?= htmlspecialchars($p['category_name'] ?? 'Non specifiee') ?>
                            </span>
                            <span>
                                <span class="meta-label">Célébrité :</span>
                                <?= htmlspecialchars($p['celebrity_name'] ?? 'Non specifiee') ?>
                            </span>
                        </div>
                        <div class="admin-actions">
                            <button class="btn-valide"
                                onclick="alertConfirmation('Valider cette annonce ?', 'validateAnnouncement', <?= $p['id_product']; ?>)">
                                Valider
                            </button>
                            <button class="btn-supp"
                                onclick="alertConfirmation('Supprimer cette annonce ?', 'deleteProductAsAdmin', <?= $p['id_product']; ?>)">
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