<?php
require_once('src/lib/database.php');
require_once('src/model/product.php');
require_once('src/model/favorite.php');

$title = "Page d'accueil";
$style = "templates/style/index.css";
?>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<?php ob_start(); ?>

<?php include('preset/header.php'); ?>

<main>

    <?php
    $pdo = \DatabaseConnection::getConnection();
    $productRepository = new \ProductRepository($pdo);
    $favoriteRepository = new \FavoriteRepository($pdo);

    $products = $productRepository->getAllProduct();
    ?>

    <?php if (empty($products)): ?>
        <p>Aucune annonce disponible pour le moment.</p>
    <?php else: ?>

        <!-- ================= SWIPER ================= -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">

                <?php foreach ($products as $p): ?>
                    <?php if (new DateTime($p['end_date']) > new DateTime()): ?>

                        <?php
                        $priceRow = $productRepository->getLastPrice($p['id_product']);
                        $current_price = (!empty($priceRow) && isset($priceRow[0]['MAX(new_price)']))
                            ? $priceRow[0]['MAX(new_price)']
                            : $p['start_price'];

                        // FAVORIS STATE
                        $isFav = isset($_SESSION['user'])
                            ? $favoriteRepository->isProductFavorite($p['id_product'], $_SESSION['user']['id_user'])
                            : false;
                        ?>

                        <a href="index.php?action=product&id=<?= htmlspecialchars($p['id_product']) ?>" class="swiper-slide slide-link">

                            <div class="image-container">

                                <?php
                                $images = getImage($p['id_product']);
                                if (!empty($images)) {
                                    echo '<img src="' . htmlspecialchars($images[0]['url_image']) . '" alt="Image annonce">';
                                }
                                ?>

                                <div class="luxury-overlay">
                                    <h3><?= html_entity_decode(htmlspecialchars($p['title'])) ?></h3>

                                    <div class="info-row">
                                        <i class="fa-regular fa-clock icon-gold"></i>
                                        <span class="timer" data-end="<?= htmlspecialchars($p['end_date']) ?>"></span>
                                    </div>

                                    <div class="info-row price-box">
                                        <i class="fa-solid fa-money-bill-wave icon-white"></i>
                                        <span class="price"><?= htmlspecialchars($current_price) ?> €</span>
                                    </div>

                                    <div class="bid-button">Enchérir</div>
                                </div>
                            </div>

                        </a>

                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
        </div>

    <?php endif; ?>


    <!-- ================= ANNONCES ================= -->
    <div class="content">
        <h1>Nos dernières annonces</h1>

        <div class="annonces">

            <?php
            $count_displayed = 0;
            $max_to_display = 9;
            ?>

            <?php foreach ($products as $p): ?>

                <?php
                if ($count_displayed >= $max_to_display) break;
                if (new DateTime($p['end_date']) <= new DateTime()) continue;

                $isFav = isset($_SESSION['user'])
                    ? $favoriteRepository->isProductFavorite($p['id_product'], $_SESSION['user']['id_user'])
                    : false;
                ?>

                <div class="announce-card">

                    <div class="card-img-container">
                        <?php
                        $images = getImage($p['id_product']);
                        if (!empty($images)) {
                            echo '<img src="' . htmlspecialchars($images[0]['url_image']) . '" alt="Image annonce">';
                        }
                        ?>
                    </div>

                    <div class="card-body">

                        <h3><?= html_entity_decode(htmlspecialchars($p['title'])) ?></h3>

                        <p class="timer" data-end="<?= htmlspecialchars($p['end_date']) ?>"></p>

                        <div class="celebrity-row">
                            <div class="avatar-wrapper">
                                <img src="templates/Images/compte.png" class="celebrity-img" alt="">
                            </div>
                            <span class="celebrity-name">Célébrité</span>
                        </div>

                        <div class="action-row">

                            <!--  FAVORIS -->
                            <button class="wishlist-btn fav-btn"
                                data-id="<?= $p['id_product'] ?>"
                                data-isfav="<?= $isFav ? 'true' : 'false' ?>">

                                <i class="<?= $isFav ? 'fa-solid fa-heart' : 'fa-regular fa-heart' ?>"
                                    style="<?= $isFav ? 'color:red' : '' ?>"></i>

                            </button>

                            <a class="bid-btn" href="index.php?action=product&id=<?= $p['id_product'] ?>">
                                Enchérir
                            </a>

                        </div>

                    </div>
                </div>

                <?php $count_displayed++; ?>

            <?php endforeach; ?>

        </div>
    </div>

</main>

<?php include('preset/footer.php'); ?>


<!-- ================= SCRIPTS ================= -->

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="templates/JS/timer.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        new window.Swiper('.mySwiper', {
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            loop: true,
            slidesPerView: 1,
        });

        document.querySelectorAll('.timer').forEach(el => {
            startCountdown(el.dataset.end, el);
        });

        document.querySelectorAll('.fav-btn').forEach(btnFav => {

            btnFav.addEventListener("click", async (e) => {
                e.preventDefault();

                const idProduct = btnFav.dataset.id;
                let isFav = btnFav.dataset.isfav === "true";

                const url = isFav ?
                    "index.php?action=unfavorite&id=" + idProduct :
                    "index.php?action=favorite&id=" + idProduct;

                try {
                    const response = await fetch(url);
                    const data = await response.text();

                    if (data === "not_logged") {
                        window.location.href = "index.php?action=login";
                        return;
                    }

                    isFav = !isFav;

                    btnFav.innerHTML = isFav ?
                        '<i class="fa-solid fa-heart" style="color:red"></i>' :
                        '<i class="fa-regular fa-heart"></i>';

                    btnFav.dataset.isfav = isFav ? "true" : "false";

                } catch (err) {
                    console.error("Erreur favoris :", err);
                }
            });

        });

    });
</script>

<?php $content = ob_get_clean(); ?>

<?php require('preset/layout.php'); ?>