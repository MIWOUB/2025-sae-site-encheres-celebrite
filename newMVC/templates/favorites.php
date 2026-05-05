<?php
$title = "Mes favoris";
$style = "templates/style/favorites.css";

$user = $_SESSION['user'] ?? null;
?>

<?php ob_start(); ?>

<?php
if (!$user) {
    echo "<p>Vous devez être connecté.</p>";
    include('preset/footer.php');
    $content = ob_get_clean();
    require('preset/layout.php');
    exit;
}

require_once __DIR__ . '/../src/lib/database.php';
require_once __DIR__ . '/../src/model/favorite.php';
require_once __DIR__ . '/../src/model/product.php';

$pdo = DatabaseConnection::getConnection();

$favoriteRepository = new FavoriteRepository($pdo);
$productRepository = new ProductRepository($pdo);

$favorites = $favoriteRepository->getUserFavorites($user['id_user']);
?>

<main id="favoris">

    <h1>Mes favoris</h1>

    <?php if (empty($favorites)): ?>
        <p>Vous n'avez aucun favori pour le moment.</p>
    <?php endif; ?>

    <div class="annonces-list-cards">

        <?php foreach ($favorites as $p): ?>

            <?php
            $priceRow = $productRepository->getLastPrice($p['id_product']);
            $current_price = (!empty($priceRow) && isset($priceRow[0]['MAX(new_price)']))
                ? $priceRow[0]['MAX(new_price)']
                : ($p['reserve_price'] ?? 0);
            ?>

            <div class="card">

                <!-- IMAGE -->
                <div class="card-img">
                    <?php
                    $images = getImage($p['id_product']);
                    if (!empty($images)): ?>
                        <img src="<?= htmlspecialchars($images[0]['url_image']) ?>" alt="image">
                    <?php else: ?>
                        <div class="no-image">Aucune image</div>
                    <?php endif; ?>
                </div>

                <h3><?= htmlspecialchars($p['title']) ?></h3>

                <!-- BAS DE CARTE -->
                <div class="card-bottom">
                    <p class="timer" data-end="<?= htmlspecialchars($p['end_date'] ?? '') ?>"></p>
                    <p>Prix actuel : <?= htmlspecialchars($current_price) ?> €</p>
                    <a class="main-btn" href="index.php?action=product&id=<?= $p['id_product'] ?>">
                        Voir
                    </a>
                </div>

            </div>

        <?php endforeach; ?>

    </div>

</main>

<script src="templates/JS/timer.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.timer').forEach(el => {
            startCountdown(el.getAttribute('data-end'), el);
        });
    });
</script>

<?php $content = ob_get_clean(); ?>

<?php require('preset/layout.php'); ?>