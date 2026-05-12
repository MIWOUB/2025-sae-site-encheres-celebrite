<?php
$title = "Page du produit";
$style = "templates/style/product.css";
$script = "templates/JS/favorite.js";
?>

<?php ob_start(); ?>

<?php include('preset/header.php'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="templates/style/stylePopup.css" rel="stylesheet" />

<div id="popup"></div>
<div id="toastBox"></div>

<script src="https://kit.fontawesome.com/645d3e5fd2.js" crossorigin="anonymous"></script>

<?php
// 🔥 PRIX PROPRE (IMPORTANT)
$lastPrice = $productRepository->getLastPrice($p['id_product']);
$current_price = $lastPrice ?? (int)$p['reserve_price'];
?>

<main>
    <h1><?= htmlspecialchars($p['title']) ?></h1>

    <div class="product-header">

        <p class="timer" data-end="<?= htmlspecialchars($p['end_date']) ?>"></p>

        <div class="product-price">
            <p>
                Offre actuelle :<br>
                <span>
                    <?= number_format($current_price, 0, ',', ' ') ?> €
                </span>
            </p>
        </div>

        <button class="main_btn" id="bid_button" type="button"
            onclick="ouvrirPopup('BidForm')">
            Enchérir
        </button>
    </div>

    <div id="fav" data-is-fav="<?= $isFav ? 'true' : 'false' ?>">
        <?= $isFav
            ? '<i class="fa-solid fa-star"></i>'
            : '<i class="fa-regular fa-star"></i>'; ?>
    </div>

    <!-- 🔥 SOURCE UNIQUE DU PRIX -->
    <input id="currentPrice" type="hidden" value="<?= (int)$current_price ?>">
    <input id="idProduct" type="hidden" value="<?= (int)$p['id_product'] ?>">

    <div class="product-layout">

        <!-- IMAGES -->
        <div class="container">
            <?php if (empty($images)) { ?>
                <p>Aucune image disponible pour cette annonce.</p>
            <?php } else { ?>
                <div class="swiper mySwiper2">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) { ?>
                            <div class="swiper-slide">
                                <img src="<?= $image['url_image'] ?>">
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) { ?>
                            <div class="swiper-slide">
                                <img src="<?= $image['url_image'] ?>">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- DESCRIPTION -->
        <section id="product-description">
            <h2>Description</h2>
            <p><?= strip_tags($p['description']) ?></p>
        </section>

    </div>

    <!-- COMMENTAIRES -->
    <section class="product-title">
        <hr>
        <h2>Commentaires</h2>
        <hr>
    </section>

    <section id="product-comment">

        <?php $currentUserId = $_SESSION['user']['id_user'] ?? null; ?>

        <?php foreach ($comments as $comment) { ?>
            <div class="comment-item">

                <h3>
                    <a href="index.php?action=user&id=<?= $comment['id_user'] ?>">
                        <?= htmlspecialchars($comment['full_name']) ?>
                    </a>

                    <time data-local-datetime="<?= $comment['comment_date'] ?>"></time>
                </h3>

                <p><?= htmlspecialchars($comment['comment']) ?></p>

                <?php if ($currentUserId == $comment['id_user']) { ?>

                    <form method="POST" action="index.php?action=updateComment">
                        <input type="hidden" name="id" value="<?= $p['id_product'] ?>">
                        <input type="hidden" name="id_comment" value="<?= $comment['id_comment'] ?>">
                        <textarea name="comment"><?= htmlspecialchars($comment['comment']) ?></textarea>
                        <button type="submit">Modifier</button>
                    </form>

                    <form method="POST" action="index.php?action=deleteComment">
                        <input type="hidden" name="id" value="<?= $p['id_product'] ?>">
                        <input type="hidden" name="id_comment" value="<?= $comment['id_comment'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>

                <?php } ?>

            </div>
        <?php } ?>

        <form id="comment-form" method="POST" action="index.php?action=addComment">
            <input type="hidden" name="id" value="<?= $p['id_product'] ?>">
            <textarea name="comment" placeholder="Laissez un commentaire !" required></textarea>
            <button type="submit">Publier</button>
        </form>

    </section>

</main>

<?php include('preset/footer.php'); ?>

<!-- SWIPER -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
var swiper = new Swiper(".mySwiper", {
    spaceBetween: 10,
    slidesPerView: 4,
    freeMode: true,
    watchSlidesProgress: true,
});

var swiper2 = new Swiper(".mySwiper2", {
    spaceBetween: 10,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    thumbs: {
        swiper: swiper,
    },
});
</script>

<script src="templates/JS/OuverturePopUp.js"></script>
<script src="templates/JS/timer.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.timer').forEach(el => {
        const endDate = el.getAttribute('data-end');
        startCountdown(endDate, el);
    });
});
</script>

<script>
const toastBox = document.querySelector('#toastBox');

function showToast(type, msg) {
    const toast = document.createElement('div');
    toast.classList.add('toast');

    if (type === 1) {
        toast.classList.add('invalid');
    } else if (type > 1) {
        toast.classList.add('error');
    }

    toast.innerHTML = msg;
    toastBox.appendChild(toast);

    setTimeout(() => toast.remove(), 5000);
}
</script>

<?php $content = ob_get_clean(); ?>
<?php require('preset/layout.php'); ?>