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
$lastPrice = $productRepository->getLastPrice($p['id_product']);
$current_price = $lastPrice ?? (int) $p['reserve_price'];
?>

<main>
    <div style="text-align: center;">
        <h1><?= mb_convert_encoding(html_entity_decode($p['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8'), 'UTF-8', 'UTF-8') ?>
        </h1>
    </div>

    <div class="product-header">

        <div class="timer-box">

            <h3 class="header-title">Temps restant :</h3>

            <p class="timer" data-end="<?= htmlspecialchars($p['end_date']) ?>">
            </p>

            <p class="end-date">
                Fini le
                <time class="local-date" data-local-datetime="<?= htmlspecialchars($p['end_date']) ?>"></time>
            </p>

        </div>

        <div class="product-price">

            <?php if ($current_price === null)
                $current_price = $p['start_price']; ?>

            <h3 class="header-title">Offre actuelle :</h3>

            <span>
                <?= htmlspecialchars(number_format($current_price, 0, ',', ' ')) ?> €
            </span>

        </div>

        <button class="main_btn" id="bid_button" type="button" onclick="ouvrirPopup('BidForm')">
            Enchérir
        </button>

    </div>

    <input id="currentPrice" type="hidden" value="<?= (int) $current_price ?>">
    <input id="idProduct" type="hidden" value="<?= (int) $p['id_product'] ?>">

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

                <div thumbsSlider="" class="swiper mySwiper">
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
            <p><?= mb_convert_encoding(html_entity_decode(strip_tags($p['description']), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 'UTF-8', 'UTF-8') ?>
            </p>
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
            <div class="comment-item" data-comment-id="<?= $comment['id_comment'] ?>">

                <h3>
                    <a href="index.php?action=user&id=<?= $comment['id_user'] ?>">
                        <?= htmlspecialchars($comment['full_name']) ?>
                    </a>
                    <time data-local-datetime="<?= $comment['comment_date'] ?>"></time>
                </h3>

                <p class="comment-text"><?= htmlspecialchars(strip_tags($comment['comment'])) ?></p>

                <?php if ($currentUserId && (int) $currentUserId === (int) $comment['id_user']) { ?>
                    <div class="comment-actions">
                        <button class="btn-edit" onclick="enableEdit(this)">Modifier</button>
                        <form method="POST" action="index.php?action=deleteComment">
                            <input type="hidden" name="id" value="<?= $p['id_product'] ?>">
                            <input type="hidden" name="id_comment" value="<?= $comment['id_comment'] ?>">
                            <button class="btn-delete" type="submit">Supprimer</button>
                        </form>
                    </div>

                    <form class="edit-form" method="POST" action="index.php?action=updateComment">
                        <input type="hidden" name="id" value="<?= $p['id_product'] ?>">
                        <input type="hidden" name="id_comment" value="<?= $comment['id_comment'] ?>">
                        <textarea name="comment" required><?= htmlspecialchars($comment['comment']) ?></textarea>
                        <div class="edit-buttons">
                            <button type="submit" class="btn-save">Enregistrer</button>
                            <button type="button" class="btn-cancel" onclick="cancelEdit(this)">Annuler</button>
                        </div>
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

    const toastBox = document.querySelector('#toastBox');

    function showToast(numberValidation, msg) {
        const toast = document.createElement('div');
        toast.classList.add('toast');

        if (numberValidation === 1) {
            toast.classList.add('invalid');
            toast.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${msg}`;
        } else if (numberValidation > 1) {
            toast.classList.add('error');
            toast.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> ${msg}`;
        } else {
            toast.innerHTML = `<i class="fa-solid fa-circle-check"></i> ${msg}`;
        }

        toastBox.appendChild(toast);

        setTimeout(() => toast.remove(), 6000);
    }

    function enableEdit(btn) {
        const item = btn.closest('.comment-item');
        item.classList.add('edit-mode');
        item.querySelector('.comment-text').style.display = "none";
        item.querySelector('.comment-actions').style.display = "none";
        item.querySelector('.edit-form').style.display = "flex";
    }

    function cancelEdit(btn) {
        const item = btn.closest('.comment-item');
        item.classList.remove('edit-mode');
        item.querySelector('.comment-text').style.display = "";
        item.querySelector('.comment-actions').style.display = "flex";
        item.querySelector('.edit-form').style.display = "none";
    }
</script>

<?php $content = ob_get_clean(); ?>
<?php require('preset/layout.php'); ?>